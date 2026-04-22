<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThemeController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Valider le type et la taille de l'image
        ]);

        // Stocker l'image dans le répertoire 'public/images' et obtenir son chemin
        $imagePath = $request->file('image')->store('images', 'public');

        // Créer un nouveau thème avec le nom et le chemin de l'image
        $theme = new Theme();
        $theme->nom = $request->nom;
        $theme->image_path = $imagePath; // Stocker le chemin de l'image dans la base de données
        $theme->save();

        return redirect()->back()->with('success', 'Le thème a été créé avec succès.');   
    }

    public function create()
    {
        $user = Auth::user(); // ✅ récupérer utilisateur connecté
        return view('themes.create', compact('user'));
    }

}
