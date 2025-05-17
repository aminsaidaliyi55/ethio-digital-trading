<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // ✅ Correct placement
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class CustomerController extends Controller
{
    public function create(): View
    {
        return view('Customer.create');
    }


public function register(Request $request)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        
'password' => [
        'required',
        'string',
        'min:8',             // Minimum 8 characters
        'regex:/[a-z]/',      // Must contain at least one lowercase letter
        'regex:/[A-Z]/',      // Must contain at least one uppercase letter
        'regex:/[0-9]/',      // Must contain at least one digit
        'regex:/[@$!%*?&#]/', // Must contain a special character
        'confirmed',          // Must match password_confirmation field
    ],

]);

    $validatedData['password'] = Hash::make($validatedData['password']);
    $user = User::create($validatedData);

    // Assign 'Customer' role if none provided or authenticated user is a guest
    if (Auth::guest() || !$request->filled('roles')) {
        $user->assignRole('Customer');
    } else {
        $user->assignRole($request->input('roles'));
    }

    Auth::login($user);

    return redirect()->route('home')->withSuccess('Welcome! You have registered successfully.');
}


}