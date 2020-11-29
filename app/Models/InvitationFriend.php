<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitationFriend extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'email_receiver',
        'status'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'email_receiver', 'email');
    }
}
