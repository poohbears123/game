<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Game;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Game::with('category');

        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Apply category filter
        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }

        $games = $query->get();
        $totalGames = Game::count();
        $totalCategories = Category::count();
        $totalUsers = \App\Models\User::count(); // Static or dynamic third card

        $categories = Category::all();

        return view('dashboard', compact('games', 'totalGames', 'totalCategories', 'totalUsers', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'release_year' => 'required|integer|min:1900|max:' . date('Y'),
            'category_id' => 'nullable|exists:categories,id',
        ]);

        Game::create($validated);

        return redirect()->route('dashboard')->with('success', 'Game added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $game = Game::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'release_year' => 'required|integer|min:1900|max:' . date('Y'),
            'category_id' => 'nullable|exists:categories,id',
            'photo' => 'nullable|image|mimes:jpg,png|max:2048', // 2MB max
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($game->photo && \Storage::disk('public')->exists($game->photo)) {
                \Storage::disk('public')->delete($game->photo);
            }
            $photoPath = $request->file('photo')->store('games', 'public');
            $validated['photo'] = $photoPath;
        }

        $data = $validated;

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($game->photo && \Storage::disk('public')->exists($game->photo)) {
                \Storage::disk('public')->delete($game->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $game->update($data);

        return redirect()->route('dashboard')->with('success', 'Game updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        $game = Game::findOrFail($id);
        $game->delete(); // Soft delete

        return redirect()->route('dashboard')->with('success', 'Game deleted successfully!');
    }

    /**
     * Display trashed games.
     */
    public function trash(): View
    {
        $trashedGames = Game::onlyTrashed()->with('category')->get();
        $trashedCategories = Category::onlyTrashed()->get();

        return view('trash', compact('trashedGames', 'trashedCategories'));
    }

    /**
     * Restore the specified resource from trash.
     */
    public function restore(string $id): RedirectResponse
    {
        $game = Game::withTrashed()->findOrFail($id);
        $game->restore();

        return redirect()->route('games.trash')->with('success', 'Game restored successfully!');
    }

    /**
     * Permanently delete the specified resource from storage.
     */
    public function forceDelete(string $id): RedirectResponse
    {
        $game = Game::withTrashed()->findOrFail($id);

        // Delete photo if exists
        if ($game->photo && \Storage::disk('public')->exists($game->photo)) {
            \Storage::disk('public')->delete($game->photo);
        }

        $game->forceDelete();

        return redirect()->route('games.trash')->with('success', 'Game permanently deleted!');
    }

    /**
     * Export games to PDF.
     */
    public function export(Request $request)
    {
        $query = Game::with('category');

        // Apply same filters as index
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }

        $games = $query->get();

        $pdf = Pdf::loadView('pdf.games', compact('games'));
        return $pdf->download('games.pdf');
    }
}
