<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Rollbar\Rollbar;
use Rollbar\Payload\Level;
use Config;
use Illuminate\Session\TokenMismatchException;

//use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler {

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
//    protected $dontReport = [
//        'Symfony\Component\HttpKernel\Exception\HttpException'
//    ];        

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        if ($this->shouldReport($e)) {
            app('sentry')->captureException($e);
        }
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e) {        
        /* newcode */
        if ($e instanceof TokenMismatchException) {
            return redirect($request->fullUrl())->with('csrf_error', "Opps! Seems you couldn't submit form for a longtime. Please try again");
        }
        /* end */
        if ($this->isHttpException($e)) {
            return $this->renderHttpException($e);
        }

        return parent::render($request, $e);
    }

}
