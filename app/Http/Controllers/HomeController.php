<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    } 
      public function logout()
    {
    // Log the user out and invalidate the session
    Auth::logout();

    // Regenerate the session to prevent session fixation
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    // Redirect to the login page
    return redirect()->route('login')->with('message', 'You have been logged out successfully.');
}
}
