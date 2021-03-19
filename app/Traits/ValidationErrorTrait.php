<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait ValidationErrorTrait
{
    /**
     * The response builder callback.
     *
     * @var \Closure
     */
    protected static $responseBuilder;

    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        if (isset(static::$responseBuilder)) {
            return (static::$responseBuilder)($request, $errors);
        }

        return response()->json([
            'status' => 'error',
            'message' => $errors
        ],
        422);
    }

}
