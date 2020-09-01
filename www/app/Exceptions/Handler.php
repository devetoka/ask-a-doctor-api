<?php

namespace App\Exceptions;

use App\Exceptions\Custom\CustomException;
use App\Http\Response\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     *
     * Custom error messages
     */

    protected $errorCodes = [
        '404' => 'exception.not_found',
        '500' => 'exception.internal_server_error',
        '400' => 'exception.invalid_request',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        logger($exception->getMessage());
        if($exception instanceof ThrottleRequestsException) {
            return ApiResponse::sendResponse([], $exception->getMessage(),
                false, ApiResponse::HTTP_TOO_MANY_REQUESTS
            );
        }
        if($exception instanceof InvalidSignatureException) {
            return ApiResponse::sendResponse([], $exception->getMessage(),
                false, $exception->getStatusCode()
            );
        }
//        if($exception instanceof QueryException) {
//
//            return ApiResponse::sendResponse([], trans('exception.database'),
//                false, ApiResponse::HTTP_INTERNAL_SERVER_ERROR
//            );
//        }
        if($exception instanceof AuthenticationException){
            return ApiResponse::sendResponse([], trans('exception.unauthenticated'),
                false, ApiResponse::HTTP_UNAUTHORIZED
            );
        }
        if($exception instanceof ModelNotFoundException){
            return ApiResponse::sendResponse([], trans('exception.model.not_found'),
                false, ApiResponse::HTTP_NOT_FOUND
            );
        }
        if($exception instanceof AuthorizationException){
            return ApiResponse::sendResponse([], trans('exception.authorization'),
                false, ApiResponse::HTTP_FORBIDDEN
            );
        }

        if($exception instanceof CustomException){

            return ApiResponse::sendResponse([], trans('exception.custom'),
                false, $exception->getCode()
            );
        }

//        if(array_key_exists($exception->getStatusCode(), $this->errorCodes)){
//            return ApiResponse::sendResponse([], trans($this->errorCodes[$exception->getStatusCode()]),
//                false, $exception->getStatusCode()
//            );
//        }

//        dd($exception);
//        return ApiResponse::sendResponse([], $exception->getMessage(),
//            false, ApiResponse::HTTP_INTERNAL_SERVER_ERROR
//        );

        return parent::render($request, $exception);
    }
}
