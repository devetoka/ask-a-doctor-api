<?php

namespace App\Http\Controllers\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Response\ApiResponse;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Utilities\Enum\Encryption\Encryption;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
        $this->middleware('verification')->only('verify');
//        $this->middleware('throttle')->only('verify', 'resend');
    }

    public function verify(Request $request)
    {
        $token = Encryption::explode($request->token);
        $email = preg_grep("/(.+)@(.+)\.(.+)/", $token);
        $user = User::whereEmail($email[0])->first();
        if($user){
            if ($user->hasVerifiedEmail()) {
                return ApiResponse::sendResponse([], trans('verification.existing'),
                    true, ApiResponse::HTTP_OK
                );
            }

            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
                return ApiResponse::sendResponse($user, trans('verification.successs'),
                    true, ApiResponse::HTTP_OK
                );
            }
        }

        return ApiResponse::sendResponse($user, trans('verification.error'),
            false, ApiResponse::HTTP_BAD_REQUEST
        );

    }
}
