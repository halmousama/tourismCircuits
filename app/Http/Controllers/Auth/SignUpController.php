<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // Import the Log facade

class SignUpController extends Controller
{
    public function signup(Request $request)
    {
        Log::info('SignUpController: signup method called', ['request' => $request->all()]);
        // Log::info('SignUpController: Before validation', ['request' => $request->all()]);
    
        try {
            // Validate the incoming request data
            $request->validate([
                'username' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:100|unique:users',
                'password' => 'required|string|min:8',
            ]);
            Log::info('SignUpController: Validation passed', ['request' => $request->all()]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('SignUpController: Validation failed', ['errors' => $e->validator->errors()]);
            return redirect()->back()->withErrors($e->validator)->withInput();
        }
    
        // Check if the username or email already exists
        $existingUser = User::where('username', $request->username)
            ->orWhere('email', $request->email)
            ->first();

        if ($existingUser) {
            return redirect()->back()->withErrors(['username' => 'Username or email already taken.'])->withInput();
        }

        // Create the user if they do not exist
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        Log::info('SignUpController: User created', ['user_id' => $user->id], ['user' => $request->all()]);
    
        return redirect('/login')->with('success', 'Registration successful. You can now log in.');
    }
}
