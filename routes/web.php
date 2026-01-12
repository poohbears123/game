Route::resource('categories', App\Http\Controllers\CategoryController::class);
=======
    Route::resource('games', App\Http\Controllers\GameController::class);
    Route::get('games-trash', [App\Http\Controllers\GameController::class, 'trash'])->name('games.trash');
    Route::patch('games/{id}/restore', [App\Http\Controllers\GameController::class, 'restore'])->name('games.restore');
    Route::delete('games/{id}/force-delete', [App\Http\Controllers\GameController::class, 'forceDelete'])->name('games.forceDelete');

    Route::resource('categories', App\Http\Controllers\CategoryController::class);
    Route::patch('categories/{id}/restore', [App\Http\Controllers\CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('categories/{id}/force-delete', [App\Http\Controllers\CategoryController::class, 'forceDelete'])->name('categories.forceDelete');
