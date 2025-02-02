<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function login(Request $request)
    {

        // log the input data
        Log::info('LoginController: login method called', ['request' => $request->all()]);

        // Validate the incoming request data
        try {
            $request->validate([
                'username' => 'required|string|max:100',
                'password' => 'required|string|min:8',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('LoginController: Validation failed', ['errors' => $e->validator->errors()]);
            return redirect()->back()->withErrors($e->validator)->withInput();
        }

        // log the validation data
        Log::info('LoginController: Validation passed', ['request' => $request->all()]);

        // Attempt to authenticate the user
        if (auth()->attempt(['username' => $request->username, 'password' => $request->password])) {
            // If successful, redirect to the home page
            return redirect()->route('home');
        }
        
                // log the failed attempt
                Log::info('LoginController: Failed login attempt', ['request' => $request->all()]);
        
                // If unsuccessful, redirect back with an error message
        return redirect()->back()->withErrors(['login' => 'Invalid username or password.'])->withInput();
    }
}
