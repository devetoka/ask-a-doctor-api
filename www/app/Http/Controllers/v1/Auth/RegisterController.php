<?php

namespace App\Http\Controllers\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Response\ApiResponse;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Utilities\Enum\Status;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
/**
 * @group  Authentication
 *
 * Registers a new user
 */
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * Creates a user into the application
     * @bodyParam  first_name string required User's first name. Example: Francis
     * @bodyParam  last_name string required User's last name. Example: Etoka
     * @bodyParam  username string required User's username. Example: etoks
     * @bodyParam  email string required User's email. Example: francis.dretoka@gmail.com
     * @bodyParam  password string required User's password. Example: 12345678
     * @bodyParam  password_confirmation string required User's password confirmation. Example: 12345678
     * @response  {
     *  "status": true,
     *  "message": "Successful created",
     *  "data": {}
     * }
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        //validate
        if($errors = $this->validator($request->all())->getMessageBag()->getMessages()){
            return ApiResponse::sendResponse([], trans('register.validate.error'),
                false, JsonResponse::HTTP_BAD_REQUEST, $errors
            );
        }
        //create user
        $user = $this->create($request->all());
        if (!$user) {
            return ApiResponse::sendResponse([], trans('register.registered.error'),
                false, JsonResponse::HTTP_BAD_REQUEST, $errors
            );
        }
        //trigger event
        event(new Registered($user));
        return $this->registered($request, $user);
    }

    protected function registered(Request $request, $user)
    {
        return ApiResponse::sendResponse($user,trans('register.registered.success'));
    }

}
