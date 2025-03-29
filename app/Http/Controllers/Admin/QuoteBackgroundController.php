<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuoteBackground;
use Illuminate\Http\Request;

class QuoteBackgroundController extends Controller
{
    public function index()
    {
        $quoteBackgrounds = QuoteBackground::all();
        return view('admin.quotes.backgrounds', compact('quoteBackgrounds'));
    }

    public function create()
    {
        return view('admin.quotes.create_background');
    }

    public function store(Request $request)
{
    $request->validate([
        'background' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    try {
        $image = $request->file('background');
        $filename = time() . '_' . $image->getClientOriginalName(); // Unique filename
        $destinationPath = public_path('assets/quotes/background'); // Directly in the public folder

        // Move the uploaded file
        $image->move($destinationPath, $filename);

        // Save to database
        $quoteBackground = new QuoteBackground();
        $quoteBackground->filename = $filename;
        $quoteBackground->path = 'assets/quotes/background/' . $filename; // Public path
        $quoteBackground->save();

        return redirect()->back()->with('success', 'Background image uploaded successfully!');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Failed to upload image. Error: ' . $e->getMessage());
    }
}


    public function show($id)
    {
        $quoteBackground = QuoteBackground::findOrFail($id);
        return view('admin.quote_backgrounds.show', compact('quoteBackground'));
    }

    public function destroy($id)
    {
        $quoteBackground = QuoteBackground::findOrFail($id);
        $quoteBackground->delete();
        return redirect()->route('admin.quote.background.index')->with('success', 'Background image deleted successfully!');
    }
}
