<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;



class AuthenticationController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json( [ 'error' => 'Email or password invalid' ], 400);
            }
        } catch (JWTException $e) {
            return response()->json( ['error' => 'Failed to generate token'], 500);
        }

        $user = \Auth::user();

        return response()->json([ 'result' => compact('token', 'user') ], 200);
    }


    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {

                return response()->json(['Token not found'], 404);
            }

        } catch ( TokenExpiredException $e ) {

            return response()->json([ 'error' => 'Token expired' ], $e->getStatusCode());

        } catch ( TokenInvalidException $e ) {

            return response()->json([ 'error' => 'Token invalid' ], $e->getStatusCode());

        } catch ( JWTException $e ) {

            return response()->json([ 'error' => 'Token not found' ], $e->getStatusCode());
        }

        return response()->json( compact('user') );
    }


    public function logout()
    {
        JWTAuth::invalidate();

        return response()->json(['message' => 'Successfully log out']);
    }

}
