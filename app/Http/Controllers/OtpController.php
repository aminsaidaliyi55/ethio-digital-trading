<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OtpController extends Controller
{
    //
    public function showVerifyForm()
{
    return view('verify-otp');
}

}
