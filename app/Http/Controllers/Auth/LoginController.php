<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * @group Auth
 * Authentication API
 */

class LoginController extends Controller
{

     /**
     * Login a user
     *
     * Logs in a user and returns an access token
     *
     * @bodyParam email string required The user's email address
     * @bodyParam password string required The user's password
     *
     * @response 200 {
     *     "token": "access_token"
     * }
     * @response 422 {
     *     "error": "Problem in the validation!",
     *     "validation": {
     *         "email": [
     *             "The email field is required."
     *         ],
     *         "password": [
     *             "The password field is required."
     *         ]
     *     }
     * }
     * @response 401 {
     *     "message": "Unauthorized"
     * }
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()
                ->json([
                    "erro" => "Problem in the validation!",
                    "validacao" => $validator->getMessageBag()
                ])
                ->setStatusCode(422);
        }
    
        $credentials = request(['email', 'password']);
    
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    
        $user = $request->user();
        $token = $user->createToken('Personal Access Token', ['access-api'])->accessToken;
    
        return response()->json(['token' => $token], 200);
    }


    /**
     * Logout a user
     *
     * Logs out a user and revokes their access token
     *
     * @response 200 {
     *     "message": "Successfully logged out"
     * }
     */

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}