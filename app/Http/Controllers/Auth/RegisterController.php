<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


/**
 * @group Auth
 * Authentication API
 */
class RegisterController extends Controller
{

     /**
     * Register a user
     *
     * Registers a new user and returns an access token
     *
     * @bodyParam name string required The user's name
     * @bodyParam email string required The user's email address
     * @bodyParam password string required The user's password
     * @bodyParam password_confirmation string required The confirmed password
     *
     * @response 200 {
     *     "token": "access_token"
     * }
     * @response 422 {
     *     "error": "Problem in the validation!",
     *     "validation": {
     *         "name": [
     *             "The name field is required."
     *         ],
     *         "email": [
     *             "The email field is required.",
     *             "The email has already been taken."
     *         ],
     *         "password": [
     *             "The password field is required.",
     *             "The password must be at least 8 characters."
     *         ],
     *         "password_confirmation": [
     *             "The password confirmation does not match."
     *         ]
     *     }
     * }
     */
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()
                ->json([
                    "erro" => "Problem in the validation!",
                    "validacao" => $validator->getMessageBag()
                ])
                ->setStatusCode(422);
        }

      

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $token = $user->createToken('Personal Access Token', ['access-api'])->accessToken;

        return response()->json(['token' => $token], 200);
    }
}