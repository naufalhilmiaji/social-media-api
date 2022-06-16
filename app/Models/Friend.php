<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    use HasFactory;

    protected $table = 'friends';
    protected $fillable = [
        'user_id',
        'user_email',
        'friend_id',
        'friend_email',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
