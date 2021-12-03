<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\IssueController;
use App\Models\Comment;
use App\Models\Issue;
use App\Models\User;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});


Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::group(['prefix' => 'issues'], function () {
        Route::get('/', [IssueController::class, 'listForUser'])->name('issuesListForUser');
        Route::get('/not-attached', [IssueController::class, 'listNotAttached'])->name('issuesListNotAttached')->middleware('can:viewNotAttached,' . Issue::class);
        Route::post('/{issue}/attach', [IssueController::class, 'attach'])->where(['issue' => '[0-9]{1,18}'])->name('issuesAttach')->middleware('can:attach,issue');
        Route::get('/{issue}', [IssueController::class, 'show'])->where(['issue' => '[0-9]{1,18}'])->name('issuesShow')->middleware('can:view,issue');
        Route::get('/create', [IssueController::class, 'create'])->name('issuesCreate')->middleware('can:create,' . Issue::class);
        Route::post('/', [IssueController::class, 'store'])->name('issuesStore')->middleware('can:create,' . Issue::class);
        Route::delete('/{issue}', [IssueController::class, 'destroy'])->where(['issue' => '[0-9]{1,18}'])->name('issuesDestroy')->middleware('can:delete,issue');
    });

    Route::group(['prefix' => 'comments'], function () {
        Route::get('/{comment}', [CommentController::class, 'show'])->where(['comment' => '[0-9]{1,18}'])->name('commentsShow')->middleware('can:view,comment');
        Route::post('/', [CommentController::class, 'store'])->name('commentsStore')->middleware('can:create,' . Comment::class);
    });

    Route::prefix('admin')->middleware('can:accessAdmin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('adminIndex');
        Route::get('/edit-user/{user}', [AdminController::class, 'editUser'])->name('adminEditUser');
        Route::put('/update-user/{user}', [AdminController::class, 'updateUser'])->name('adminUpdateUser');
        Route::get('/register-user', [AdminController::class, 'createUser'])->name('adminRegisterUser');
        Route::post('/store-user', [AdminController::class, 'storeUser'])->name('adminStoreUser');
        Route::delete('/delete-user/{user}', [AdminController::class, 'deleteUser'])->name('adminDeleteUser');
    });

});



