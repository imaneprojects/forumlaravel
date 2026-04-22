<?php
namespace App\Http\Controllers;

//Importer les modèles
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;//Permet de récupérer les données du formulaire :
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class ProfilController extends Controller
{
    
    public function show()
    {
        $user = auth()->user();//recuperer utilisateur connecte
        $topics = $user->topics()->with('themes')->get();//tous les topics de l’utilisateur, chaque topic avec ses thèmes
        $followers = $user->followers()->distinct()->get();//Liste des gens qui le suivent
        $followed = $user->followed()->distinct()->get();//Liste des gens qu’il suit.
        
        return view('profile.show', compact('user', 'topics', 'followers', 'followed'));//Envoie données vers Blade
    }

    public function showUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('profile.show');
        }
        $topics = $user->topics()->with('themes')->get();
        $followers = $user->followers()->distinct()->get();
        $followed = $user->followed()->distinct()->get();
        return view('profile.showUser', compact('user', 'topics', 'followers', 'followed'));
    }

    public function follow(User $user)
    {
        $authUser = auth()->user();

        if (!$authUser->followed()->where('followed_id', $user->id)->exists()) {

            $authUser->followed()->attach($user->id);//attach():ajouter une nouvelle relation sans supprimer les anciennes

            $authUser->increment('followed_count');
            $user->increment('followers_count');

            //créer une notification quand un utilisateur suit un autre utilisateur
            Notification::create([
                'user_id' => $user->id,
                'from_user_id' => $authUser->id,
                'message' => $authUser->nom . ' vous suit.',
                'type' => 'follow',
                'topic_id' => null,
                'read' => false
            ]);

            return back()->with('success', 'Vous suivez maintenant ' . $user->nom);
        }

        return back()->with('error', 'Vous suivez déjà cet utilisateur.');
    }

    public function unfollow(User $user)
    {
        $authUser = auth()->user();

        // vérifier si l'utilisateur est déjà suivi
        if ($authUser->followed()->where('followed_id', $user->id)->exists()) {

            // supprimer la relation
            $authUser->followed()->detach($user->id);

            // mise à jour des compteurs
            $authUser->decrement('followed_count');
            $user->decrement('followers_count');

            return back()->with('success', 'Vous ne suivez plus ' . $user->nom);
        }

        return back()->with('error', 'Vous ne suivez pas cet utilisateur.');
    }

        //supprimer un follower de user connecte
    public function removeFollower($follower)
    {
        $authUser = auth()->user();

        $exists = DB::table('followers')
            ->where('follower_id', $follower)
            ->where('followed_id', $authUser->id)
            ->exists();

        if ($exists) {
            DB::table('followers')
                ->where('follower_id', $follower)
                ->where('followed_id', $authUser->id)
                ->delete();

            return back()->with('success', 'Follower supprimé');
        }

        return back()->with('error', 'Aucun follower trouvé');
    }

    //afficher le formulaire de modification du profil utilisateur
    public function edit(User $user)
    {
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if (auth()->id() != $user->id && auth()->user()->role !== 'admin') {
            return back()->with('error', 'Non autorisé');
        }

        //validation formulaire
        $request->validate([
            'nom' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,//(Laravel vérifie si l’email existe déjà dans la base de données, mais il exclut l’utilisateur actuel ($user->id) de cette vérification pour lui permettre de conserver son propre email sans erreur s’il ne le modifie pas.)
        ]);

        //remplir données(Prend valeurs formulaire :<input name="nom">..et les place dans objet user)
        $user->nom = $request->nom;
        $user->email = $request->email;
        $user->telephone = $request->telephone;
        $user->adresse = $request->adresse;
        $user->description = $request->description;
        $user->date_naissance = $request->date_naissance;
        $user->sexe = $request->sexe;

        //Si champ password non vide->si vide ne change rien
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);//bcrypt:hash securise
        }

        if (auth()->user()->role == 'admin') {
            $user->role = $request->role;
        }

        $user->save();

        return redirect()->route('profile.show')
            ->with('success', 'Profil modifié');
    }


    public function updateImage(Request $request, User $user)
    {
        $this->validate($request, [
            'cover' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'profil' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('cover')) {//Signifie :est-ce que user a choisi une nouvelle image couverture, Si NON On ignore cette partie.
            $coverPath = $request->file('cover')->store('cover', 'public');//resultat:$coverPath = "cover/abc123.jpg"
            //supprimer ancienne cover
            if ($user->cover && $user->cover != 'default_cover.jpeg') {
                Storage::disk('public')->delete('cover/' . $user->cover);
            }
            //enregistrer nouveau nom en base
            $user->cover = basename($coverPath);
        }

        if ($request->hasFile('profil')) {
            $profilPath = $request->file('profil')->store('profil', 'public');
            if ($user->profil && $user->profil != 'default_profil.jpeg') {
                Storage::disk('public')->delete('profil/' . $user->profil);
            }
            $user->profil = basename($profilPath);
        }

        $user->save();

        return redirect()->back()->with('success', 'Images updated successfully.');
    }

}
