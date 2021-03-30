<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function handle(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $hasExist = User::whereEmail($credentials['email'])->exists();

        if (!$hasExist) {
            return response()->jsonError(402, [
                'message' => 'Your email has not exist.'
            ]);
        }

        if (!Auth::attempt($credentials)) {
            return response()->jsonError(402, [
                'message' => 'Your password invalid'
            ]);
        }


        $jwt = Auth::user()->generateToken();

        return response()->jsonSuccess(200, [
            'token' => $jwt,
            'message' => 'Login successfully'
        ]);
    }
}
