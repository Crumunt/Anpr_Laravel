<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class ApplicantNotFoundException extends Exception
{
    //
    public function __construct(
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function render($request)
    {
        // Define how this exception should be rendered to the user
        // For example, return a JSON response for API calls or a view for web requests
        if ($request->expectsJson()) {
            return response()->json(["error" => $this->getMessage()], 400);
        }

        return redirect()
            ->back()
            ->withErrors(["error" => $this->getMessage()]);
    }
}
