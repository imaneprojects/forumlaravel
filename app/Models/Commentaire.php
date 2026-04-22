<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    protected $fillable = ['user_id', 'topic_id', 'id_parent', 'content'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function replies()
    {
        return $this->hasMany(Commentaire::class, 'id_parent')->orderBy('created_at', 'asc');
    }
     public function parent()
    {
        return $this->belongsTo(Commentaire::class, 'id_parent');
    }
}
