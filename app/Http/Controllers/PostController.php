<?php

namespace App\Http\Controllers;

use App\Models\Commentaire;
use App\Models\Notification;
use App\Models\Theme;
use App\Models\Topic;
use App\Models\TopicImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index2(Request $request)
    {
        $themeId = $request->input('theme_id');//récupérer theme_id
        $topicsQuery = Topic::query();//SELECT * FROM topics ms pas encore executee

        if ($themeId) {
            $theme = Theme::find($themeId);//Cherche thème dans table themes.

            if ($theme) {

                /* récupérer les IDs des topics liés à ce thème,
                 $theme->topics() : relation many-to-many (themes ↔ topics)
                 pluck('topics.id') récupérer seulement les IDs
                */

                $topicIds = $theme->topics()->pluck('topics.id')->toArray();
               
                /* ajoute condition SQL
                WHERE id IN (2,5,9)
                pour récupérer seulement les topics du thème
                */

                $topicsQuery->whereIn('id', $topicIds);
            }
        }
        
        $topics = $topicsQuery->get();//récupèrer les données finales
        $themes = Theme::withCount('topics')->get();//le nombre de topics pour chaque thème
        $user = auth()->user();// récupère utilisateur connecté,pour afficher profil,gerer permissions, notifications

        return view('topics.index2', compact('topics', 'themes', 'user'));
    }

    public function create()
    {   
        //récupère tous les thèmes depuis la base de données
        $allThemes = Theme::all();
        //récupère l’utilisateur connecté
        $user = auth()->user();
        return view('topics.create', compact('allThemes', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'content'    => 'nullable|string',
            'theme_id'   => 'required|array|min:1',
            'theme_id.*' => 'exists:themes,id',
            'images.*'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        ]);

        $user = auth()->user();

        $topic = Topic::create([
            'title'   => $request->title,
            'content' => $request->content,
            'user_id' => $user->id,
        ]);

        $topic->themes()->sync($request->theme_id);//sync(): remplacer toutes les relations actuelles par celles envoyées.

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {

                $fileName = time() . '_' . uniqid() . '_' . $image->getClientOriginalName();//uniqid()id unique

                $image->storeAs('public/images', $fileName);//stochage de fichier

                $topic->images()->create([
                    'path' => $fileName
                ]);//crée un enregistrement dans la table images
            }
        }

        foreach ($user->followers ?? [] as $follower) {

            Notification::create([
                'user_id'      => $follower->id,
                'from_user_id' => $user->id,
                'message'      => $user->nom . ' a publié un nouveau topic',
                'type'         => 'topic',
                'topic_id'     => $topic->id,
                'read'         => false
            ]);
        }

        return redirect()
            ->route('topics.index2')
            ->with('success', 'Sujet créé avec succès!');
    }

    public function show($id)
    {
        $topic = Topic::findOrFail($id);//cherche topic dans la bd
        if (!$topic) {
            return redirect()->route('topics.index2')->with('error', 'Sujet non trouvé.');
        }
        $topic->incrementViewCount();
        $themes = Theme::all();//récupère tous les thèmes de la base
        $images = $topic->images; // Récupérer les images associées au sujet
        $user = auth()->user();

        return view('topics.show', compact('topic', 'themes', 'user', 'images'));
    }

    public function edit($id)
    {
        $topic = Topic::findOrFail($id);
        $this->authorize('update', $topic); // Vérifie l'autorisation pour modifier ce topic
        $themes = Theme::all();
        $user = auth()->user();
        return view('topics.edit', compact('topic', 'themes', 'user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'theme_id' => 'required|array|min:1',
            'theme_id.*' => 'exists:themes,id',
        ]);

        $topic = Topic::findOrFail($id);
        $this->authorize('update', $topic); 
        $topic->title = $request->title;
        $topic->content = $request->content;
        $topic->save();

        $topic->themes()->sync($request->theme_id);

        // Vérifier si de nouvelles images ont été soumises
        if ($request->hasFile('images')) {
            // Supprimer les anciennes images
            foreach ($topic->images as $image) {
                Storage::delete('public/images/' . $image->path);
                $image->delete();
            }
            // Ajouter les nouvelles images
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('public/images', $imageName);
                $topic->images()->create(['path' => $imageName]);
            }
        }

        return redirect()->route('topics.show', $topic->id)->with('success', 'Topic mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $topic = Topic::findOrFail($id);
        $this->authorize('delete', $topic);
        // Supprimer les images associées au topic
        foreach ($topic->images as $image) {
            Storage::delete('public/images/' . $image->path);
            $image->delete();
        }
        $topic->delete();
        return redirect()->route('topics.index2')->with('success', 'Topic supprimé avec succès!');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $topics = Topic::where('title', 'like', "%$query%")
            ->orWhere('content', 'like', "%$query%")
            ->orWhereHas('user', function ($userQuery) use ($query) {
                $userQuery->where('nom', 'like', "%$query%");
            })
            ->get();

        $themes = Theme::withCount('topics')->get();

        $user = auth()->user();

        return view('topics.search', compact('topics', 'themes', 'user'));
    }

    public function toggleLike($id)
    {
        $topic = Topic::findOrFail($id);
        $user = auth()->user();

        $like = $topic->likes()->where('user_id', $user->id)->first();//first:Prend le premier résultat trouvé

        if ($like) {
            // Déliker
            $like->delete();
            $message = 'Like retiré';
        } else {
            // Liker
            $topic->likes()->create(['user_id' => $user->id]);
            $message = 'Topic liké';
        }
        return redirect()->back()->with('success', $message);
    }
}
