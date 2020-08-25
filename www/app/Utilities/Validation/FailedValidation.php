<?php


namespace App\Utilities\Validation;
use App\Http\Response\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;


trait FailedValidation {
    public function failedValidation(Validator $validator)
    {
        $errors = $validator->getMessageBag()->getMessages();
        $response =  ApiResponse::sendResponse([], trans('validate.error'),
            false, JsonResponse::HTTP_BAD_REQUEST, $errors
        );

        throw new HttpResponseException($response);
    }
}
