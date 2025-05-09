<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidationValidator;

class userController extends Controller
{
    //
    public function register(Request $request) {

        // return "Register User";
        $validator = Validator::make($request->all(),[
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

        $user = new User;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->gender = $request->gender;
        $user->role = $request->role;
        $user->password = $request->password;
        $user->save();
        return response()->json([
            'user' => $user,
            'message' => 'Registered Successfully'
        ], 201);
    }


    public function login(Request $request) {
        try {

            // $credentials = $request->validate([
            //     'email' => 'required|email',
            //     'password' => 'required'
            // ]);
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);
            
            if($validator->fails()) {
                return response()->json([
                    'message' => 'Validation Fails',
                    'errors' => $validator->errors(),
                ], 400);
            };

            if (Auth::attempt($validator)) {
                $user = Auth::user();

                return response()->json([
                    'message' => 'login successfully',
                    'user' => $user
                ], 200);
            }
    
            // return response()->json([
            //     'message' => 'Invalid Credentials',
            //     'error' => $credentials
            // ], 400);
        } catch(\Exception $error) {
            return response()->json([
                'message' => 'Error occured' . $error,
                'error' => $error,
            ], 500);
        }
    }
}
