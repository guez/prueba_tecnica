<?php

namespace App\Exceptions;

use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use League\Flysystem\FileNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
            $this->renderable(function (NotFoundHttpException $e, $request) {
                if ($request->is('api/*')) {
                    return response()->json([
                        "errors" => [
                            'message' => 'Recurso no encontrado.'
                        ]
                    ], 404);
                }
            });

            
            $this->renderable(function (DisponibilityException $e, $request) {
                if ($request->is('api/*')) {
                    return response()->json([
                        "errors" => [
                            'message' => 'No hay suficiente capacidad en el Evento.'
                        ]
                    ], 417);
                }
            });

        

        $this->reportable(function (Throwable $e) {
            // dd("safdsf");
        });
    }
}
