<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id', // Add this line
        'quote_id',
        'all_users'
    ];

    public function quote()
{
    return $this->belongsTo(Quote::class);
}

}
