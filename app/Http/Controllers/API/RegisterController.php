<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{

    public function register(Request $request): JsonResponse{
        // Validation rules
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate token
        $token = $user->createToken('MyApp')->plainTextToken;

        // Prepare success response
        $success = [
            'token' => $token,
            'name' => $user->name,
        ];

        return $this->sendResponse($success, 'User registered successfully.');
    }



    public function login(Request $request): JsonResponse{

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed
            $user = Auth::user();
            $token = $user->createToken('MyApp')->plainTextToken;

            // Prepare success response
            $success = [
                'token' => $token,
                'name' => $user->name,
            ];

            return $this->sendResponse($success, 'User logged in successfully.');
        } else {
            // Authentication failed
            return $this->sendError('Unauthorized.', ['error' => 'Unauthorized'], 401);
        }
    }


    protected function sendResponse($result, $message): JsonResponse{
        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => $message,
        ], 200);
    }


    protected function sendError($error, $errorMessages = [], $code = 404): JsonResponse{
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
