<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Photo;
use App\Models\Quiz;
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

    Route::get('/quizzez', function () {
        //dd(Quiz::all());
     
        return inertia('Admin/Quizzez', [
        'quizzez' => Quiz::all(['id', 'name'])
        ]);
    })->name('quizzez'); // This will respond to requests for admin/photos and have a name of admin.photos

    Route::get('/photos/create', function () {
        return inertia('Admin/PhotosCreate');
    })->name('photos.create');

    Route::post('/quizzez', function (Request $request) {
        //dd('I will handle the form submission')  
        
        //dd($request->all());
        $validated_data = $request->validate([
            'name' => ['required']
        ]);
       
        // $path = Storage::disk('public')->put('photos', $request->file('path'));
        // $validated_data['path'] = '/storage/' . $path;
        //dd($validated_data);
        Quiz::create($validated_data);
        return to_route('admin.quizzez');
    })->name('quizzez.store');
});

Route::middleware(['auth:sanctum', 'verified'])->prefix('api')->name('admin.')->group(function () {

    Route::delete('/quiz/{id}', function ($id) {
        return Quiz::destroy($id);
    })->name('quiz');

    Route::put('/quiz', function (Request $request) {
        $validated_data = $request->validate([
            'name' => ['required']
        ]);
      
        return Quiz::create($validated_data);
    })->name('quiz');

    Route::post('/quiz/{id}', function (Request $request, $id) {
        $quiz = Quiz::find($id);
        $quiz->update($request->all());
    })->name('quiz');

    Route::delete('/quiz/{id}', function (Request $request, $id) {
        Quiz::destroy($id);
    })->name('quiz');
});
