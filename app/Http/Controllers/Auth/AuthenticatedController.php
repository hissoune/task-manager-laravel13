<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $user = $request->user();
        $tokens = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['tokens' => $tokens]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {

      $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }
}
