<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'date',
        'hour',
        'place'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invitations()
    {
        return $this->hasMany(InvitationEvent::class);
    }
}
