<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'message',
        'read',
        'type',
        'topic_id',
        'from_user_id'
    ];
    public static function createFollowNotification($followedUserId, $followerId)
    {
        // Récupérer les informations sur user suiveur
        $follower = User::findOrFail($followerId);
        $followerName = $follower->nom;

        $notification = new Notification();
        $notification->user_id = $followedUserId;
        $notification->action = 'follow';
        $notification->message = $followerName . ' vous suit.';
        $notification->read = false;
        $notification->save();
    }

    public function markAsRead()
    {
        $this->read = true;
        $this->save();
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }
}
