<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Topic;
use Illuminate\Auth\Access\HandlesAuthorization;

class TopicPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Topic $topic)
    {
    return $user->id === $topic->user_id || $user->role === 'admin';
    }

    public function delete(User $user, Topic $topic)
    {
        return $user->id === $topic->user_id || $user->role === 'admin'; // Autoriser le propriétaire ou l'administrateur
    }
}

