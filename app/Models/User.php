<?php 

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Auth;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function boot() {
        parent::boot();
        static::creating(function ($user) {
            $user->password = Hash::make($user->password);
        });
    }


    // User.php

    public function following()
    {
        return $this->hasOne(Follows::class, 'user_id', 'id')
            ->select(['user_id', 'following'])
            ->withDefault(['following' => '']);
    }

    // Define the relationship to get the list of followers
    public function followers()
    {
        return $this->hasOne(Follows::class, 'user_id', 'id')
            ->select(['user_id', 'followers'])
            ->withDefault(['followers' => '']);
    }

    public function getFollowingListAttribute()
    {
        return $this->following ? explode(',', $this->following) : [];
    }

    public function getFollowersListAttribute()
    {
        return $this->followers ? explode(',', $this->followers) : [];
    }

    public function isFollowing($userId)
    {
        $followingList = $this->followingList;
        return in_array($userId, $followingList);
    }

// Follow a user
public function follow($userId)
{
    $currentuser = Auth::user();
    $currentuser = User::find($currentuser->id);
    
    // Increase the following count for the current user
    $currentuser->following += 1; // Increment the following count
    $currentuser->save(); // Save the changes
    
    // Increase the followers count for the user being followed
    $euser = User::find($userId);
    $euser->followers += 1; // Increment the followers count
    $euser->save(); // Save the changes
    

    $follows = $this->following()->firstOrCreate(['user_id' => $this->id]);
    $following = $follows->following ? explode(',', $follows->following) : [];
    $following[] = $userId;
    $follows->following = implode(',', array_unique($following));
    $follows->save();

    // Add the user to the followers list of the followed user
    Follows::firstOrCreate(['user_id' => $userId], ['followers' => ""]);
    $follower = Follows::where('user_id', $userId)->first();
    $followers = $follower->followers ? explode(',', $follower->followers) : [];
    $followers[] = $this->id;
    $follower->followers = implode(',', array_unique($followers));
    $follower->save();
}

// Unfollow a user
public function unfollow($userId)
{

    $currentuser = Auth::user();
     $currentuser = User::find($currentuser->id);

// Decrease the following count for the current user
if ($currentuser->following > 0) {
    $currentuser->following -= 1; // Decrement the following count
    $currentuser->save(); // Save the changes
}

// Decrease the followers count for the user being unfollowed
$euser = User::find($userId);

if ($euser->followers > 0) {
    $euser->followers -= 1; // Decrement the followers count
    $euser->save(); // Save the changes
}


    $follows = $this->following()->where('user_id', $this->id)->first();
    $following = $follows->following ? explode(',', $follows->following) : [];
    $following = array_diff($following, [$userId]);
    $follows->following = implode(',', $following);
    $follows->save();

    // Remove the user from the followers list of the followed user
    $follower = Follows::where('user_id', $userId)->first();
    $followers = $follower->followers ? explode(',', $follower->followers) : [];
    $followers = array_diff($followers, [$this->id]);
    $follower->followers = implode(',', $followers);
    $follower->save();
}


}
