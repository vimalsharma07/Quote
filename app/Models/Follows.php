<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follows extends Model
{
    use HasFactory;

    // Specify the table associated with the model
    protected $table = 'follows';

    // Define the attributes that are mass assignable
    protected $fillable = ['user_id', 'followers', 'following'];

    // If you do not have a primary key column named 'id', specify it
    protected $primaryKey = 'id';

    // Since the 'id' column is not auto-incrementing, set it to false (if it is auto-incrementing, you can omit this line)
    public $incrementing = true;

    // Ensure that your timestamps are being managed
    public $timestamps = true;

    // Define any additional methods or relationships as needed
}
