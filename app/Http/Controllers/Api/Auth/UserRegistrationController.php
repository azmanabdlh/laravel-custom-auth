<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserRegistrationController extends Controller
{
    public function handle(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed'
        ]);

        try {
            $fields['password'] = Hash::make($fields['password']);
            $user = User::create($fields);

            return response()->jsonSuccess(201, [
                'message' => 'User created successfully.',
                'data' => $user
            ]);
        }catch (ElequentException $err) {
            return response()->jsonError(500, [
                'message' => 'Failed!, try again later.'
            ]);
        }
    }
}
