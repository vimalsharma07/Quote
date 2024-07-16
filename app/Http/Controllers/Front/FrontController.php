<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Quote;
use App\Models\Like;
use App\Models\Tag;

use Auth;

class FrontController extends Controller
{
    public  function  index(){
        $quotes= Quote::all();
        return view('front.index', ['quotes'=>$quotes]);

    }


    public function likedquotes(Request $request){
        $user = Auth::user();

      $quotes_id=   Like::where('all_users', 'LIKE', '%'.$user->id.'%')->select('quote_id')->get();
    $quotes=   Quote::whereIn('id', $quotes_id)->get();
      return view('front.quotes.quotes',['quotes'=>$quotes]);
    }

    public function yourquotes(Request $request){
        $user = Auth::user();
     $quotes=    Quote::where('user_id', $user->id)->get();
     return view('front.quotes.quotes',['quotes'=>$quotes]);

        
    }

    public function searchquotes(Request $request, $tag){

     $quotes=  Quote::where('tags', 'Like', '%'.$tag.'%')->orwhere('discription', 'Like', '%'.$tag.'%')->get();
     return view('front.quotes.quotes',['quotes'=>$quotes]);

    }


    public function suggest(Request $request)
{
    $query = $request->get('query');
    $tags = Tag::where('name', 'LIKE', '%' . $query . '%')->pluck('name');
    return response()->json(['tags' => $tags]);
}



public function uploadCapturedImage(Request $request)
{
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('public/captured-images');
        $url = Storage::url($path);

        return response()->json(['success' => true, 'image_url' => url($url)]);
    }

    return response()->json(['success' => false, 'message' => 'Image upload failed.']);
}

public function downloadCapturedImage(Request $request)
{
    $this->validate($request, [
        'image' => 'required|image',
        'quote_id' => 'required|integer|exists:quotes,id',
    ]);

    $image = $request->file('image');
    $quoteId = $request->input('quote_id');

    // Handle image processing (if needed)
    $path = $image->store('public/download-images');
    $downloadUrl = Storage::url($path);

    return response()->json([
        'success' => true,
        'download_url' => $downloadUrl
    ]);
}

public  function  quote($id){
   $quote=  Quote::where('id', $id)->get();
   return view('front.quotes.quotes',['quotes'=>$quote]);

}
    
}
