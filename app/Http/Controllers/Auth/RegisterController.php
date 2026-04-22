<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'nom' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'role' => 'nullable|string', // Ajout de la validation pour le champ "role"
            'photo' => 'nullable|image|max:2048', // Ajout de la validation pour le champ "photo"
            'date_naissance' => 'nullable|date', // Ajout de la validation pour le champ "date_naissance"
            'adresse' => 'nullable|string', // Ajout de la validation pour le champ "adresse"
            'telephone' => 'nullable|string', // Ajout de la validation pour le champ "telephone"
        ]);

        // Assurer d'enregistrer le fichier photo correctement
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('photos', $filename); // Enregistrer le fichier dans le répertoire "photos" ou tout autre répertoire approprié
        } else {
            $filename = null; // Si aucun fichier n'est téléchargé, définissez-le sur null ou sur une valeur par défaut appropriée
        }

        // Enregistrez les données de l'utilisateur
        $user = User::create([
            'nom' => $request->nom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'photo' => $filename, // Enregistrez le nom du fichier de la photo dans la base de données
            'date_naissance' => $request->date_naissance,
            'adresse' => $request->adresse,
            'telephone' => $request->telephone,
        ]);

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function create()
    {
        return view('auth.register');
    }
}
?>
