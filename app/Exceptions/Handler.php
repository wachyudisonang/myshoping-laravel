<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
		// https://stackoverflow.com/questions/43245853/how-to-produce-api-error-responses-in-laravel-5-4#answer-43246463
        if ($request->wantsJson()) {
			return $this->renderExceptionAsJson($request, $exception);
		}
		
		return parent::render($request, $exception);
	}
	
	/**
	 * Render an exception into a JSON response
	 *
	 * @param $request
	 * @param Exception $exception
	 * @return SymfonyResponse
	 */
	protected function renderExceptionAsJson($request, Exception $exception)
	{
		$exception = $this->prepareException($exception);

		if ($exception instanceof \Illuminate\Http\Exception\HttpResponseException) {
			return $exception->getResponse();
		}
		if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
			return $this->unauthenticated($request, $exception);
		}
		if ($exception instanceof \Illuminate\Validation\ValidationException) {
			return $this->convertValidationExceptionToResponse($exception, $request);
		}

		$response = [];
		if (method_exists($exception, 'getStatusCode')) {
			$statusCode = $exception->getStatusCode();
		} else {
			$statusCode = 500;
		}

		switch ($statusCode) {
			case 404:
				$response['error'] = 'Not Found';
				break;

			case 403:
				$response['error'] = 'Forbidden';
				break;

			default:
				$response['error'] = $exception->getMessage();
				break;
		}

		if (config('app.debug')) {
			$response['trace'] = $exception->getTrace();
			$response['code'] = $exception->getCode();
		}

		return response()->json($response, $statusCode);
	}
}
