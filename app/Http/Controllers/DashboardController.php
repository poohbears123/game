<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Category;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalGames = Game::count();
        $totalCategories = Category::count();
        $totalUsers = User::count();

        $games = Game::with('category')->get();
        $categories = Category::all();

        return view('dashboard', compact('totalGames', 'totalCategories', 'totalUsers', 'games', 'categories'));
    }
}
