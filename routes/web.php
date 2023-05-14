<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {

    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('photos', function () {
    return Inertia::render('Guest/Photos', [
        'photos' => Photo::all(), ## 👈 Pass a collection of photos, the key will become our prop in the component
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // other admin routes here
    Route::get('/photos', function () {
        
        return inertia('Admin/Photos', [
            'photos' => Photo::all()
        ]);
    })->name('photos'); // This will respond to requests for admin/photos and have a name of admin.photos

    Route::get('/photos/create', function () {
        return inertia('Admin/PhotosCreate');
    })->name('photos.create');

    Route::post('/photos', function (Request $request) {
        //dd('I will handle the form submission')  
        
        //dd(Request::all());
        $validated_data = $request->validate([
            'path' => ['required', 'image', 'max:2500'],
            'description' => ['required']
        ]);
       
        $path = Storage::disk('public')->put('photos', $request->file('path'));
        $validated_data['path'] = '/storage/' . $path;
        //dd($validated_data);
        Photo::create($validated_data);
        return to_route('admin.photos');
    })->name('photos.store');
});
