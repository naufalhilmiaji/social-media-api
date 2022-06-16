<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FriendRequest extends Model
{
    use HasFactory;

    protected $table = 'friend_requests';
    protected $fillable = [
        'user_id',
        'user_email',
        'requestor_id',
        'requestor_email',
        'status',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
