<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->namespace('v1')->group(function (){

    /**
     *
     *
     * Authentication routes
     */
    Route::prefix('auth')->group(function(){
        Route::post('register', 'Auth\RegisterController@register');
        Route::post('login', 'Auth\LoginController@login')->middleware(['throttle'])->name('login');
        Route::get('verify/{token}', 'Auth\verificationController@verify');
        Route::post('password-reset', 'Auth\ForgotPasswordController@sendResetLinkEmail');
        Route::post('reset', 'Auth\ResetPasswordController@reset');
    });

    /**
     *
     *
     * End of authentication routes
     *
     */

    /**
     *
     * unprotected routes
     */
    Route::get('/categories', 'CategoryController@index')->name('category.index');
    Route::get('/questions/{question_id}', 'QuestionController@show')->name('question.show'); // show question
    Route::get('/replies/{reply_id}', 'ReplyController@show')->name('reply.show'); // show reply

    /**
     * end of unprotected routes
     */


    /**
     *
     * Protected routes
     */
    Route::middleware('auth:api')->group(function(){

        //user routes
        Route::prefix('users')->group(function(){

            //user-categories routes
            Route::post('categories','CategoryUserController@store')
                ->name('user.categories.create');
            Route::delete('categories','CategoryUserController@destroy')
                ->name('user.categories.destroy');
            // end of user-categories routes
        });


        // categories routes
        Route::prefix( 'categories')->group(function(){
            Route::post('/', 'CategoryController@store')->name('category.store');
            Route::put('/{category_id}', 'CategoryController@update')->name('category.update');
            Route::get('/{category_id}', 'CategoryController@show')->name('category.show');
            Route::delete('/{category_id}', 'CategoryController@destroy')->name('category.destroy');
        });
        //end of categories route


        // questions routes
        Route::prefix( 'questions')->group(function(){
            Route::post('/', 'QuestionController@store')->name('question.store');
            Route::put('/{question_id}', 'QuestionController@update')->name('question.update');
            Route::delete('/{question_id}', 'QuestionController@destroy')->name('question.destroy');
        });
        //end of questions routes


        //reply routes
        Route::prefix( 'replies')->group(function(){
            Route::put('/{reply_id}', 'ReplyController@update')->name('reply.update');
            Route::post('/{reply_id}/replies', 'ReplyController@store')
                ->name('reply.question.store');
            Route::delete('/{reply_id}', 'ReplyController@destroy')->name('reply.destroy');
        });

        //reply to a question
        Route::post('/questions/{question_id}/replies', 'ReplyController@replyToQuestion')
            ->name('reply.question.store');

        //reply to a reply
        Route::post('/replies/{reply_id}/replies', 'ReplyController@store')
            ->name('reply.store');
        //end of reply routes

    });

    /**
     *
     * End of protected routes
     */
});

//protected routes

