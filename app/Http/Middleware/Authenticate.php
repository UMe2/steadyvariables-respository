<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        $response = [
            "responseCode" => 401,
            'success' => false,
            'message' => "Unauthorized access",

        ];
         return response()->json($response, 401);
    }
}
