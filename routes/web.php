<?php

use App\Http\Controllers\QuizController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TelegramController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Photo;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

Route::middleware(['auth:sanctum', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/quizzez', function () {
        return inertia('Admin/Quizzez', [
            'quizzez' => Quiz::all(['id', 'name'])
        ]);
    })->name('quizzez'); 

    Route::get('/create/quiz', function (Request $request) {
        return inertia('Admin/QuizCreateEdit');
    })->name('create.quiz');

    Route::get('/edit/quiz/{id}', function (QuizController $controller, int $id) {
        $data = $controller->getQuizWithVariants($id);
        $d = [
            'quiz' => ['id' => 22, 'name' => 'test', 'questions' => [
                0 => ['id' => 10, 'name' => 'test_question', 'variants' => [0 => ['name' => 'dfdf']]]
            ]]
        ];
        //dd($data);
        return Inertia::render('Admin/QuizCreateEdit', ['quiz' => $data]);
    })->name('edit.quiz');

    Route::post('/quizzez', function (Request $request) {
        $validated_data = $request->validate([
            'name' => ['required']
        ]);
        Quiz::create($validated_data);

        return to_route('admin.quizzez');
    })->name('quizzez.store');

    Route::get('/settings', function () {
        return inertia('Admin/Settings');
    })->name('settings'); 
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

    Route::post('/variant/toggle/{id}', function (int $id) {
        $variant = Variant::find($id);
        $variant->is_right = !$variant->is_right;

        return $variant->save();
    })->name('variant');

    Route::delete('/variant/{id}', function ($id) {
        return Variant::destroy($id);
    })->name('variant');

    //SETTINGS
    Route::get('/settings/localhost', function (SettingsController $controller) {
        return $controller->getNgrockUri();
    })->name('settings');

    //TELEGRAM
    Route::post('/telegram', function (TelegramController $controller) {
        return $controller->index();
    })->name('telegram')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
});
