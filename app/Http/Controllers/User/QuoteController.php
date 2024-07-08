<?php 

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Models\QuoteBackground;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuoteController extends Controller
{
    public function create()
    {
        $backgrounds = QuoteBackground::all();
        return view('front.quotes.create', compact('backgrounds'));
    }

    public function storeQuote(Request $request)
    {
        // $request->validate([
        //     'text' => 'required|string',
        //     'background_image' => 'required|string',
        //     'text_x' => 'required|numeric',
        //     'text_y' => 'required|numeric',
        //     'text_align' => 'required|string|in:left,right,center',
        // ]);

        // Create a new quote
        $quote = new Quote();
        $quote->user_id = auth()->user()->id;  // Assuming the user is authenticated
        $quote->text = $request->input('text');
        $quote->background_image = $request->input('background_image');
        $quote->text_x = $request->input('text_x');
        $quote->text_y = $request->input('text_y');
        $quote->text_align = $request->input('text_align');
        $quote->likes = 0;  // Default value
        $quote->comment = null;  // Default value
        $quote->save();

        return redirect()->route('create-quote')->with('success', 'Quote created successfully.');
    }


    public function show($id)
{
    $quote = Quote::findOrFail($id);
    return view('quotes.show', compact('quote'));
}


}
