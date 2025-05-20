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
        'role' => 'required|in:Customer,Owners',
        'password' => [
            'required',
            'string',
            'min:8',
            'regex:/[a-z]/',
            'regex:/[A-Z]/',
            'regex:/[0-9]/',
            'regex:/[@$!%*?&#]/',
            'confirmed',
        ],
    ]);

    // Create user
    $validatedData['password'] = Hash::make($validatedData['password']);
    $user = User::create([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'password' => $validatedData['password'],
    ]);

    // Assign role
    $role = $request->input('role');
    $user->assignRole($role);

    // If Owner, redirect to payment page
    if ($role === 'Owners') {
        // Mark user as unapproved (if you want admin approval)
        $user->update(['is_approved' => 0]); // Add this column to your `users` table

        // Send verification email
        // Redirect to payment page
        Auth::login($user);


        return redirect()->route('owner.payment')->with('message', 'Please pay 1000 Birr to complete registration.');
    }

    // If Customer, auto-login and redirect
    Auth::login($user);
    return redirect()->route('dashboard')->with('success', 'Registration successful!');
}

protected function authenticated(Request $request, $user)
{
    if (!$user->is_approved) {
        Auth::logout();

        return redirect()->route('login')->withErrors([
            'email' => 'Your account is awaiting approval by the admin.',
        ]);
    }
}

}