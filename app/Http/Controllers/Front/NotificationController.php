<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Models\Notification;
use Auth;

class NotificationController extends Controller
{
    //
    // NotificationController.php
public function markAsRead()
{
    Notification::where('user_id', Auth::user()->id)->update(['read'=>1]);
    return response()->json(['status' => 'success']);
}

}
