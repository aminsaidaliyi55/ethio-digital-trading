<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show');
    }




    public function update(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255',
        'password' => 'nullable|string|min:8|confirmed',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Image validation rules
    ]);

    $user = Auth::user();
    $user->name = $request->input('name');
    $user->email = $request->input('email');

    // Handle image upload
    if ($request->hasFile('image')) {
        // Delete old image if exists
        if ($user->image && Storage::exists('public/profile_images/' . $user->image)) {
            Storage::delete('public/profile_images/' . $user->image);
        }

        // Generate a new image name based on the user's name
        $image = $request->file('image');
        $imageExtension = $image->getClientOriginalExtension();
        $imageName = $user->name . '.' . $imageExtension;

        // Ensure the filename is unique
        $count = 1;
        while (Storage::exists('public/profile_images/' . $imageName)) {
            $imageName = $user->name . '_' . $count . '.' . $imageExtension;
            $count++;
        }

        $path = $image->storeAs('public/profile_images', $imageName);
        $user->image = $imageName;
    }

    // Update password if provided
    if ($request->filled('password')) {
        $user->password = Hash::make($request->input('password'));
    }

    $user->save();

    return redirect()->route('login')->with('success', 'Profile updated successfully.');
}









    public function download($file)
    {
        $path = storage_path('app/public/build/assets/images/' . $file);

        if (!file_exists($path)) {
            abort(404, 'File not found');
        }

        return response()->download($path);
    }
}
