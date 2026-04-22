<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'content', 'user_id', 'views_count'
    ];

    protected $table="topics";
    protected $primaryKey="id";

    public function incrementViewCount()
    {
        $this->views_count++;
        $this->save();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commentaires()
    {
        return $this->hasMany(Commentaire::class);
    }

    public function themes()
    {
        return $this->belongsToMany(Theme::class);
    }
    public function getFollowersAttribute()
    {
        return $this->user->followers;
    }
    public function images()
    {
        return $this->hasMany(TopicImage::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function isLikedBy(User $user) 
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

}
