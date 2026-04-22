<?php

namespace App\Http\Controllers;

use App\Models\Commentaire;
use App\Models\Notification;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentaireController extends Controller
{
    public function store(Request $request, Topic $topic)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $commentaire = new Commentaire();
        $commentaire->content = $request->content;
        $commentaire->user_id = Auth::id();
        $commentaire->topic_id = $topic->id;

        if ($request->has('id_parent')) {
            $commentaire->id_parent = $request->input('id_parent');
        }

        $commentaire->save();

        // UNE SEULE gestion des notifications
        $this->sendReplyNotification($commentaire);

        return redirect()->route('topics.show', $topic->id)
            ->with('success', 'Commentaire ajouté avec succès!');
    }

    protected function sendReplyNotification($commentaire)
    {
        $parentComment = $commentaire->parentComment;
        $topicOwner = $commentaire->topic->user;

        // 1. Notification pour le parent du commentaire
        if ($parentComment && $parentComment->user_id !== Auth::id()) {
            $message = "Votre commentaire sur '{$commentaire->topic->title}' a reçu une réponse de " . Auth::user()->nom;
            $this->sendNotificationToUser(
                $parentComment->user,
                $message,
                'reply',
                $commentaire->topic->id
            );
        }

        // 2. Notification pour le propriétaire du topic (éviter doublon)
        if (
            $topicOwner &&
            $topicOwner->id !== Auth::id() &&
            (!$parentComment || $parentComment->user_id !== $topicOwner->id)
        ) {
            $message = "Nouveau commentaire sur votre sujet '{$commentaire->topic->title}' par " . Auth::user()->nom;
            $this->sendNotificationToUser(
                $topicOwner,
                $message,
                'comment',
                $commentaire->topic->id
            );
        }
    }

    protected function sendNotificationToUser($user, $message, $type = null, $topicId = null)
    {
        if ($user->id === Auth::id()) {
            return; // ne jamais notifier soi-même
        }

        Notification::create([
            'user_id' => $user->id,
            'message' => $message,
            'read' => false,
            'type' => $type,
            'topic_id' => $topicId,
            'from_user_id' => Auth::id()
        ]);
    }

public function destroy($topicId, $commentaireId)
{
    $topic = Topic::findOrFail($topicId);
    $commentaire = Commentaire::findOrFail($commentaireId);

    $this->authorize('delete', $commentaire);

    $commentaire->delete();

    return redirect()->route('topics.show', $topic->id)->with('success', 'Commentaire supprimé avec succès!');
}

}
