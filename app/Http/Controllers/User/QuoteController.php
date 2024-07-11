<?php 

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Models\Like;
use App\Models\QuoteBackground;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Image;


use DB;


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
        $quote->tags = $request->input('tags');
        $quote->discription = $request->input('description');
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


public function like(Request $request, $id)
{
    $quote = Quote::findOrFail($id);
    $user = auth()->user();

    DB::transaction(function () use ($quote, $user) {
        $like = Like::where('quote_id', $quote->id)->lockForUpdate()->first();
        $totalUsers = $like ? json_decode($like->all_users, true) : [];
        
        // Check if the user has already liked the quote
        if (in_array($user->id, $totalUsers)) {
            // Remove the like
            $quote->likes= $quote->likes-1;
            

            // Update the total users
            $totalUsers = array_diff($totalUsers, [$user->id]);
            if (count($totalUsers) > 0) {
                $like->all_users = json_encode(array_values($totalUsers));
                $like->update();
            } else {
                $like->delete();
            }
        } else {
            
            // Add a new like
            $quote->likes = $quote->likes+1;
            if ($like) {
               
                
                $totalUsers[] = $user->id;
                $like->all_users = json_encode(array_values(array_unique($totalUsers)));
                $like->update();
            } else {
                
                try {
                    
                    $newLike = new Like;
                    $newLike->user_id = $quote->user_id;
                    $newLike->quote_id = $quote->id;
                    $newLike->all_users = json_encode([$user->id]);
                    $newLike->save();
                } catch (\Illuminate\Database\QueryException $ex) {
                    // Handle duplicate entry error
                    if ($ex->errorInfo[1] == 1062) {
                        // Duplicate entry detected, refresh the like object
                        $like = Like::where('quote_id', $quote->id)->lockForUpdate()->first();
                        $totalUsers = json_decode($like->all_users, true);
                        $totalUsers[] = $user->id;
                        $like->all_users = json_encode(array_values(array_unique($totalUsers)));
                        $like->update();
                    } else {
                        throw $ex;
                    }
                }
            }
        }
    });
  $quote->update();
    return response()->json($quote);
}




// public function download($id)
// { 
//     $quote = Quote::findOrFail($id);

//     $text = $quote->text; // Assuming the text of the quote is stored in the 'text' column
//     $imagePath = public_path('storage/' . $quote->background_image);

//     // Define the text position as percentages, font size, and color
//     $fontSize = 5; // GD built-in font size
//     $xPercent = $quote->text_x*100; // x percentage
//     $yPercent = $quote->text_y*100; // y percentage
//     $color = [255, 255, 255]; // White color for the text
//     $align = $quote->text_align; // Text alignment

//     try {
//         // Call the function to add text to the image
//         $newImagePath = addTextToImage($imagePath, $text, $fontSize, $xPercent, $yPercent, $color, $align);

//         // Return the modified image for download
//         return response()->download($newImagePath);
//     } catch (\Exception $e) {
//         return response()->json(['error' => $e->getMessage()], 500);
//     }
// }




}
