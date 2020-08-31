<?php

namespace App\Http\Controllers\v1\Auth;

use App\Http\Controllers\v1\Controller;
use App\Http\Response\ApiResponse;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Send a reset link to the given user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function sendResetLinkEmail(Request $request)
    {
        //validate
        if($errors = $this->validator($request->all())->getMessageBag()->getMessages()){
            return ApiResponse::sendResponse([], trans('forgot.validate.error'),
                false, ApiResponse::HTTP_BAD_REQUEST, $errors
            );
        }

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );

        if($response == Password::RESET_LINK_SENT)
            return  ApiResponse::sendResponse([], trans($response),
                true, ApiResponse::HTTP_OK);

        return ApiResponse::sendResponse([], trans($response),
            false, ApiResponse::HTTP_NOT_FOUND, [trans('forgot.invalid')]);


    }

    private function validator(array $all)
    {
        return Validator::make($all,['email' => 'required']);
    }
}
