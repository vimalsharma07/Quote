<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'users'; // Ensure this is set

    protected $fillable = [
        'name', 'email', 'phone', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->password = Hash::make($user->password);
        });
    }

    // ✅ Corrected: Get the list of users the current user is following
    public function getFollowingListAttribute()
    {
        $data = Follows::where('user_id', $this->id)->first();
        return $data ? json_decode($data->following, true) ?? [] : [];
    }

    // ✅ Corrected: Get the list of users following the current user
    public function getFollowersListAttribute()
    {
        $data = Follows::where('user_id', $this->id)->first();
        return $data ? json_decode($data->followers, true) ?? [] : [];
    }

    // ✅ Check if the user is following another user
    public function isFollowing($userId)
    {
        return in_array($userId, $this->followingList);
    }

    // ✅ Follow a user
    public function follow($userId)
    {
        $currentUser = Auth::user();
        $currentUser->increment('following');

        $euser = User::find($userId);
        $euser->increment('followers');

        // Create notification
        Notification::create([
            'user_id' => $userId,
            'type' => 'follow',
            'link' => '/profile/view/' . $currentUser->id,
            'data' => $currentUser->name . ' started following you'
        ]);

        // ✅ Corrected: Update Following List (JSON)
        $follows = Follows::firstOrCreate(['user_id' => $currentUser->id]);
        $following = json_decode($follows->following, true) ?? [];
        $following[] = (int) $userId;
        $follows->following = json_encode(array_unique($following));
        $follows->save();

        // ✅ Corrected: Update Followers List (JSON)
        $follower = Follows::firstOrCreate(['user_id' => $userId]);
        $followers = json_decode($follower->followers, true) ?? [];
        $followers[] = (int) $currentUser->id;
        $follower->followers = json_encode(array_unique($followers));
        $follower->save();
    }

    // ✅ Fixed: Unfollow a user
    public function unfollow($userId)
    {
        $currentUser = Auth::user();
        if ($currentUser->following > 0) {
            $currentUser->decrement('following');
        }

        $euser = User::find($userId);
        if ($euser->followers > 0) {
            $euser->decrement('followers');
        }

        // ✅ Corrected: Remove from following list
        $follows = Follows::where('user_id', $currentUser->id)->first();
        if ($follows) {
            $following = json_decode($follows->following, true) ?? [];
            $following = array_values(array_filter($following, fn($id) => $id != $userId));
            $follows->following = json_encode($following);
            $follows->save();
        }

        // ✅ Corrected: Remove from followers list
        $follower = Follows::where('user_id', $userId)->first();
        if ($follower) {
            $followers = json_decode($follower->followers, true) ?? [];
            $followers = array_values(array_filter($followers, fn($id) => $id != $currentUser->id));
            $follower->followers = json_encode($followers);
            $follower->save();
        }
    }

    // ✅ Relationship with notifications
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
