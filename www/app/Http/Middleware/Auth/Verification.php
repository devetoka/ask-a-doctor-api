<?php

namespace App\Http\Middleware\Auth;

use App\Utilities\Enum\Encryption\Encryption;
use Closure;
use Illuminate\Routing\Exceptions\InvalidSignatureException;

class Verification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->token;
        if(!Encryption::verifySignature($token)) throw new InvalidSignatureException();
        return $next($request);
    }
}
