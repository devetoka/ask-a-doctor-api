<?php


namespace App\Exceptions\Custom;


use Throwable;

class CustomException extends  \Exception
{
    public function __construct($message = "", $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}