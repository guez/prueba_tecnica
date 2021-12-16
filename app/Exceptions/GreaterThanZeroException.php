<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class GreaterThanZeroException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return new JsonResponse([
            "status"=>false,
            "error"=>"El número debe ser mayor a 0",
        ]);
    }
}
