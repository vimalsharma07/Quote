<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Import the Storage facade

use DB;

class SettingController extends Controller
{
    public function media()
    {
        $media = DB::table('media')->first();
        return view('admin.media.edit', compact('media'));
    }

    public function updatemedia(Request $request)
    {

        $id= $request->id;
        $request->validate([
            'logo' => 'nullable|file|mimes:png,jpg,jpeg|max:2048',
            'instagram' => 'nullable|url',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'whatsapp' => 'nullable|string',
        ]);
    
        $media = DB::table('media')->where('id', $id)->first();
    
        // Handle the file upload for logo
        if ($request->hasFile('logo')) {
            // Delete the old logo if exists
            if ($media->logo) {
                Storage::disk('public')->delete($media->logo);
            }
    
            // Store the new logo
            $logoPath = $request->file('logo')->store('media/logos', 'public');
            DB::table('media')->where('id', $id)->update(['logo' => $logoPath]);
        }
    
        // Update other fields
        DB::table('media')->where('id', $id)->update([
            'instagram' => $request->input('instagram'),
            'facebook' => $request->input('facebook'),
            'twitter' => $request->input('twitter'),
            'whatsapp' => $request->input('whatsapp'),
        ]);
    
        return redirect()->back()->with('success', 'Media updated successfully.');
    }
}
