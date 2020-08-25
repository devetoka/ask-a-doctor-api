<?php

namespace App\Http\Controllers\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Response\ApiResponse;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;


    public function reset(ResetPasswordRequest $request)
    {
        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
            $this->resetPassword($user, $password);
        }
        );

        if($response == Password::PASSWORD_RESET)
            return  ApiResponse::sendResponse([], trans($response),
                true, ApiResponse::HTTP_OK);
        else if($response == Password::INVALID_USER)
            return  ApiResponse::sendResponse([], trans($response),
                false, ApiResponse::HTTP_BAD_REQUEST, [trans($response)]);
        else if($response == Password::INVALID_TOKEN)
            return  ApiResponse::sendResponse([], trans($response),
                false, ApiResponse::HTTP_UNAUTHORIZED, [trans($response)]);
        return  ApiResponse::sendResponse([], trans($response),
            true, ApiResponse::HTTP_INTERNAL_SERVER_ERROR);


    }

}
