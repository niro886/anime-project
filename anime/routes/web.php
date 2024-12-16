<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\CheckForAuth;


/*Route::get('/', function () {
    return view('welcome');
});*/

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');




Route::group(['prefix' => 'shows'], function () {
    Route::get('/show-details/{id}', action: [App\Http\Controllers\Anime\AnimeController::class, 'animeDetails'])->name('anime.details');
    //comments
    Route::post('/insert-comments/{id}', [App\Http\Controllers\Anime\AnimeController::class, 'insertComments'])->name('anime.insert.comments');

    //following
    Route::post('/follow/{id}', [App\Http\Controllers\Anime\AnimeController::class, 'follow'])->name('anime.follow');

    //Episodes
    Route::get('/anime-watching/{show_id}/{episode_id}', [App\Http\Controllers\Anime\AnimeController::class, 'animeWatching'])->name('anime.watching');

    //Categories
    Route::get('/category/{category_name}', [App\Http\Controllers\Anime\AnimeController::class, 'category'])->name('anime.category');

    //search shows
    Route::any('/search', [App\Http\Controllers\Anime\AnimeController::class, 'searchShows'])->name('anime.search.shows');


});


//users 'users followed shows'
Route::get('users/followed-shows', [App\Http\Controllers\Users\UsersController::class, 'followedShows'])->name('users.followed.shows')->middleware('auth:web');

//admin panel
Route::get('admin/login', [App\Http\Controllers\Admins\AdminsController::class, 'viewLogin'])->name('view.login')->middleware(CheckForAuth::class);
Route::post('admin/login', [App\Http\Controllers\Admins\AdminsController::class, 'checkLogin'])->name('check.login');

Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function () {

    Route::get('/index', [App\Http\Controllers\Admins\AdminsController::class, 'index'])->name('admins.dashboard');

    //admins
    Route::get('/all-admins', [App\Http\Controllers\Admins\AdminsController::class, 'allAdmins'])->name('admins.all');
    Route::get('/create-admins', action: [App\Http\Controllers\Admins\AdminsController::class, 'createAdmins'])->name('admins.create');
    Route::post('/create-admins', action: [App\Http\Controllers\Admins\AdminsController::class, 'storeAdmins'])->name('admins.store');

    //shows
    Route::get('/all-shows', [App\Http\Controllers\Admins\AdminsController::class, 'allShows'])->name('shows.all');
    Route::get('/create-shows', action: [App\Http\Controllers\Admins\AdminsController::class, 'createShows'])->name('shows.create');
    Route::post('/create-shows', action: [App\Http\Controllers\Admins\AdminsController::class, 'storeShows'])->name('shows.store');
    Route::get('/delete-shows/{id}', action: [App\Http\Controllers\Admins\AdminsController::class, 'deleteShows'])->name('shows.delete');


    //genres
    Route::get('/all-genres', [App\Http\Controllers\Admins\AdminsController::class, 'allGenres'])->name('genres.all');
    Route::get('/delete-genres/{id}', action: [App\Http\Controllers\Admins\AdminsController::class, 'deleteGenres'])->name('genres.delete');

});
