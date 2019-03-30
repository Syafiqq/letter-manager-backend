<?php

namespace App\Exceptions;

use App\Model\Popo\PopoMapper;
use App\Model\Util\HttpStatus;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        Log::error('[' . $exception->getCode() . '] "' . (strlen($exception->getMessage()) < 1 ? get_class($exception) : $exception->getMessage()) . '" on line ' . (@$exception->getTrace()[0]['line'] ?? $exception->getLine()) . ' of file ' . (@$exception->getTrace()[0]['file'] ?? $exception->getFile()));
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->expectsJson())
        {
            $statusCode =
                method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() :
                    (property_exists($exception, 'status') ? $exception->status : HttpStatus::INTERNAL_SERVER_ERROR);
            switch (get_class($exception))
            {
                case ModelNotFoundException::class :
                    return response()->json(PopoMapper::alertResponse(HttpStatus::NOT_FOUND, 'Resource Not Found'), HttpStatus::NOT_FOUND);
                case ValidationException::class :
                    /** @var ValidationException $exception */
                    return response()->json(PopoMapper::jsonResponse($statusCode, strlen($exception->getMessage()) < 1 ? 'Invalid Data' : $exception->getMessage(), $exception->errors(), [], ['Invalid form request']), $statusCode);
                case AuthorizationException::class :
                    /** @var AuthorizationException $exception */
                    return response()->json(PopoMapper::alertResponse(HttpStatus::NOT_FOUND, strlen($exception->getMessage()) < 1 ? 'Unknown Request' : $exception->getMessage()), HttpStatus::FORBIDDEN);
                default :
                    return response()->json(PopoMapper::alertResponse($statusCode, strlen($exception->getMessage()) < 1 ? 'Unknown Request' : $exception->getMessage()), $statusCode);
            }
        }
        else
        {
            return parent::render($request, $exception);
        }
    }
}
