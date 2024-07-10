<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Auth;

class UserProfileController extends Controller
{
    public function show()
    {

        if(Auth::check()){

        $user= Auth::user();
        $user = User::find($user->id);
        return view('user.profile.show', compact('user'));
        }else{
            return  redirect()-> route('login');
        }
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('user.profile.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = User::find($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->description = $request->input('description');

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo) {
                Storage::delete('public/photos/' . $user->photo);
            }
            $photo = $request->file('photo');
            $photoName = time() . '.' . $photo->getClientOriginalExtension();
            $photo->storeAs('public/photos', $photoName);
            $user->photo = $photoName;
        }

        $user->save();

        return redirect()->route('profile.show', $id)->with('success', 'Profile updated successfully.');
    }
}
