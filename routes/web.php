<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Posts\Posts;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::get('/test-auth', function () {
    $post = App\Models\Post::first();
    $user = auth()->user();

    if (Gate::allows('update', $post)) {
        return "Usuário pode atualizar o post!";
    }

    return "Usuário NÃO pode atualizar o post!";
})->middleware('auth');


Route::get('/posts', Posts::class)->middleware('auth');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__ . '/auth.php';
