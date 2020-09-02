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


        // questions routes
        Route::prefix( 'questions')->group(function(){
            Route::post('/', 'QuestionController@store')->name('question.store');
            Route::put('/{question_id}', 'QuestionController@update')->name('question.update');
            Route::get('/{question_id}', 'QuestionController@show')->name('question.show');
            Route::delete('/{question_id}', 'QuestionController@destroy')->name('question.destroy');
        });

    });

    /**
     *
     * End of protected routes
     */
});

//protected routes

