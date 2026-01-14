<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    Route::resource('games', App\Http\Controllers\GameController::class);
    Route::get('games/export', [App\Http\Controllers\GameController::class, 'export'])->name('games.export');
    Route::get('games-trash', [App\Http\Controllers\GameController::class, 'trash'])->name('games.trash');
    Route::patch('games/{id}/restore', [App\Http\Controllers\GameController::class, 'restore'])->name('games.restore');
    Route::delete('games/{id}/force-delete', [App\Http\Controllers\GameController::class, 'forceDelete'])->name('games.forceDelete');
    Route::resource('categories', App\Http\Controllers\CategoryController::class);
    Route::patch('categories/{id}/restore', [App\Http\Controllers\CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('categories/{id}/force-delete', [App\Http\Controllers\CategoryController::class, 'forceDelete'])->name('categories.forceDelete');
});