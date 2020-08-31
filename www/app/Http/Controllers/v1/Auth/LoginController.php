<?php

namespace App\Http\Controllers\v1\Auth;

use App\Http\Controllers\v1\Controller;
use App\Http\Response\ApiResponse;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }


    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        //validate
        if($errors = $this->validateLogin($request)){
            return ApiResponse::sendResponse([], trans('login.validate.error'),
                false, JsonResponse::HTTP_BAD_REQUEST, $errors
            );
        }
        //get credentials
        $credentials = $this->getCredentials($request);
        //attempt login by checking if credentials are valid
        if(!$user = $this->attemptToLogin($request, $credentials)){
            return ApiResponse::sendResponse([], trans('login.error'), false,
                JsonResponse::HTTP_UNAUTHORIZED, 'Invalid credentials'
            );
        }

        // return unauthorized if email not verified TODO:
        if(!$user->email_verified_at) return $this->notVerifiedResponse();
        //create token
        $token = $user->createToken($user->username);
        $data = [
            'access_token' => $token->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $token->token->expires_at
            )->toDateTimeString()
        ];
        return ApiResponse::sendResponse($data, trans('login.success'),
            true, JsonResponse::HTTP_OK
        );


    }

    public function notVerifiedResponse()
    {
        return ApiResponse::sendResponse([], trans('login.email_not_verified'),
            true, JsonResponse::HTTP_UNAUTHORIZED
        );
    }


    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
       return $this->validator($request->all())->getMessageBag()->getMessages();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
    }


    /**
     * @param Request $request
     * @return array
     */
    private function getCredentials(Request $request): array
    {
        $credentials = [
            $this->username($request->username) => $request->username,
            'password' => $request->password
        ];
        return $credentials;
    }

    public function username($username)
    {
        return filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    }

    private function attemptToLogin(Request $request, $credentials)
    {
        $user = User::where([
                $this->username($request->username) => $credentials[$this->username($request->username)]
            ]
        )->first();
        if($user){
            return Hash::check($credentials['password'],$user->password) ? $user : false;
        }
        return false;
    }


}
