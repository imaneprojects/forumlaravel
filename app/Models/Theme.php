<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = ['nom', 'image_path']; // Ajoutez 'image_path' ici

    public function topics()
    {
        return $this->belongsToMany(Topic::class);
    }
    
    public function getImageUrlAttribute()
    {
        // Retourne l'URL complète de l'image si 'image_path' est défini
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }
}
