<?php


namespace App\Http\Response;



use Illuminate\Http\JsonResponse;

class ApiResponse extends JsonResponse
{
    public  static  function sendResponse($data, $message = 'success', $status = true, $status_code = self::HTTP_OK, $errors = [])
    {
       return response()->json(self::format($data, $message, $status, $errors), $status_code);
    }

    private static function format($data, $message, $status, $errors)
    {
        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'errors' => $errors
        ];

        if(!empty($response['errors'])) unset($response['data']);
        else unset($response['errors']);
        return $response;


    }
}