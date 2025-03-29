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
                $oldPhotoPath = public_path('photos/' . basename($user->photo)); // Extract filename from stored path
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }
        
            // Handle new photo upload
            $photo = $request->file('photo');
            $photoName = time() . '.' . $photo->getClientOriginalExtension();
            $destinationPath = public_path('photos'); // Public directory
        
            // Move the uploaded file
            $photo->move($destinationPath, $photoName);
        
            // Save the full path in the database
            $user->photo = asset('photos/' . $photoName); // Stores full URL
        }
        
        

        $user->save();

        return redirect()->route('profile.show', $id)->with('success', 'Profile updated successfully.');
    }


    public function profileview(Request $request, $id){
       
        $loginuser= Auth::user();
        
        if(  isset($loginuser)  && isset($id)){

            $isFollowing = $loginuser->isFollowing($id);
            $user = User::find($id);
            return view('user.profile.show', compact('user', 'isFollowing'));
            }else{
                return  redirect()-> route('login' );
            }
    }

    public function follow($id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();
    
        if ($currentUser->id === $user->id) {
            return response()->json(['success' => false], 400);
        }
    
        $currentUser->follow($user->id);
    
        return redirect()->back()->with(['success' => true]);
    }
    
    public function unfollow($id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();
    
        if ($currentUser->id === $user->id) {
            return response()->json(['success' => false], 400);
        }
    
        $currentUser->unfollow($user->id);
    
        return redirect()->back()->with(['success' => true]);
    }

    
}
