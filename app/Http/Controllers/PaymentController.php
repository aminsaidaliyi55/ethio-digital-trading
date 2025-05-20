<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function showPaymentPage()
    {
        return view('payment.owner');
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'screenshot' => 'required|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $file = $request->file('screenshot');
        $user = Auth::user();

        // Make username safe for folder name (remove special chars except space, dash, underscore)
        $username = preg_replace('/[^A-Za-z0-9 _-]/', '', $user->name);

        // Create filename with timestamp and original filename
        $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());

        $folderPath = 'payment_screenshots/' . $username;

        // Store the file in storage/app/public/payment_screenshots/{username}
        $file->storeAs('public/' . $folderPath, $filename);

        // Save relative path to the database for later retrieval
        $user->payment_screenshot = $folderPath . '/' . $filename;
        $user->is_approved = 0; // Reset approval on new upload
        $user->save();

        return view('payment.owner')->with('message', 'Payment submitted and pending approval.');
    }

    public function index(Request $request)
    {
        $query = User::role('Owners');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status') && in_array($request->status, ['0', '1'])) {
            $query->where('is_approved', $request->status);
        }

        $users = $query->paginate(10);
        

        return view('payment.index', compact('users'));
    }

    public function approve(User $user)
    {
        $user->is_approved = 1;
        $user->save();

        return redirect()->back()->with('success', 'Payment approved successfully.');
    }
}
