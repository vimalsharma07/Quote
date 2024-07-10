<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;

class TagsController extends Controller
{
    public function index(){
        return view ('admin.tag.index');
    }


    public function create(){
        return view ('admin.tag.create');

    }

    public function store(Request $request){
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
        ]);
    
        // Create a new tag instance
        $tag = new Tag;
        $tag->name = $request->name;
        $tag->status = $request->status; // Assuming you want to set the default status to active (1)
        $tag->save();
    
        // Redirect back with a success message
        return redirect()->back()->with('success', 'Tag created successfully!');
    }
    
}
