<?php

use App\Http\Controllers\QuizController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Photo;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Variant;
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

    Route::get('/create/quiz', function (Request $request) {
        return inertia('Admin/QuizCreateEdit');
        
    })->name('create.quiz');

    Route::get('/edit/quiz/{id}', function (QuizController $controller, int $id) {
        $data = $controller->getQuizWithVariants($id);
        
        return Inertia::render('Admin/QuizCreateEdit', [
            'quiz' => $data
        ]);  
    })->name('edit.quiz');

    Route::post('/quizzez', function (Request $request) {
        $validated_data = $request->validate([
            'name' => ['required']
        ]);
        Quiz::create($validated_data);

        return to_route('admin.quizzez');
    })->name('quizzez.store');
});

Route::prefix('api')->group(function () {
    //QUIZ
    Route::delete('/quiz/{id}', function ($id) {
        return Quiz::destroy($id);
    })->name('quiz');

    Route::put('/quiz', function (Request $request, Quiz $quiz) {
        
        return Quiz::create($request->all());
    })->name('quiz')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

    Route::post('/quiz', function (Request $request) {
        $id = $request->id;
        $quiz = Quiz::find($id);

        $quiz->name = $request->name;
        return $quiz->save();
    })->name('quiz');

    Route::delete('/quiz/{id}', function (Request $request, $id) {
        Quiz::destroy($id);
    })->name('quiz');

    //QUESTION
    Route::put('/question', function (Request $request, Question $question) {
        return Question::create($request->all());
    })->name('question');

    Route::delete('/question/{id}', function ($id) {
        return Question::destroy($id);
    })->name('question');

     //VARIANT
     Route::put('/variant', function (Request $request, Variant $question) {
        return Variant::create($request->all());
    })->name('variant');

    Route::delete('/variant/{id}', function ($id) {
        return Variant::destroy($id);
    })->name('variant');
});
