<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

// use Illuminate\Validation\Validator as ValidationValidator;

class userController extends Controller
{
    //
    public function register(Request $request)
    {

        // return "Register User";
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string',
            'gender' => 'in:male,female,other',
            'role' => 'in:user,vendor,admin',
            'password' => 'string|min:8|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])[a-zA-Z0-9]{8,}$/'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
                'message' => "registration Failed"
            ], 400);
        }
        try {
            $verification_code = rand(100000, 999999);
            $user = new User;
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->email = $request->email;
            $user->phone_number = $request->phone_number;
            $user->gender = $request->gender;
            $user->role = $request->role;
            $user->password = $request->password;
            $user->save();

            Mail::to($user->email)->send( new
                \App\Mail\userEmailVerification($user));
            return response()->json([
                'user' => $user,
                'message' => 'Registered Successfully',
                // 'message' => `A verification code has been sent to $user->email please verify`,
            ], 201);
        } catch (\Exception $error) {
            return response()->json([
                'message' => 'registration failed',
                'errors' => $error,
            ], 500);
        }
    }

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'code' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }
        try {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json([
                    'message' => $request->email . 'do not exist',
                ], 400);
            } elseif ($user->verification_code !== $request->code) {
                return response()->json([
                    'message' => 'verification failed',
                ], 400);
            } elseif (
                $user->email && $user->email ===
                $request->email
            ) {
                User::where('email', $request->email)->update([
                    'email_verified_at' => now(),
                    'verification_code' => null,
                ]);
                $user->save();

                return response()->json([
                    'message' => "Verified successfully",
                ], 200);
            }
        } catch (\Exception $error) {
            return response()->json([
                'message' => "Verification Failed",
                'errors' => $error,
            ], 500);
        }
    }


    public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            return response()->json([
                'user' => $user,
                'message' => 'login successfully'
            ], 200);
        }

        return response()->json([
            'message' => 'Invalid Credentials',
        ], 400);
    }
    // try {

    //     $credentials = $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required'
    //     ]);
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required|email',
    //         'password' => 'required'
    //     ]);

    //     if($validator->fails()) {
    //         return response()->json([
    //             'message' => 'Validation Fails',
    //             'errors' => $validator->errors(),
    //         ], 400);
    //     };

    //     if (Auth::attempt($credentials)) {
    //         $user = Auth::user();

    //         return response()->json([
    //             'message' => 'login successfully',
    //             'user' => $user
    //         ], 200);
    //     }

    // return response()->json([
    //     'message' => 'Invalid Credentials',
    //     'error' => $credentials
    // ], 400);
    // } catch(\Exception $error) {
    //     return response()->json([
    //         'message' => 'Error occured' . $error,
    //         'error' => $error,
    //     ], 500);
    // }

}
