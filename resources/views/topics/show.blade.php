@extends('layouts.app')
@section('content')

<div class="container-fluid bg-light py-3">
<div class="row" >

    <!-- 🟩 DROITE -->
    <div class="col-md-3 order-md-last">      
        @include('components.topic-author', ['user' => $topic->user])        
    </div>

    <!-- 🟨 CENTRE -->
    <div class="col-md-6">

        <div class="card mb-3 p-2">

            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center">

                <div class="d-flex align-items-center">
                    <img src="{{ $topic->user->profil 
                        ? asset('storage/profil/'.$topic->user->profil)
                        : asset('default-profile.png') }}"
                        class="rounded-circle me-2"
                        width="40" height="40">

                    <div>
                        <a href="{{ route('profile.showUser', $topic->user->id) }}"
                           class="user-topic text-decoration-none">
                            {{ ucfirst($topic->user->nom) }}
                        </a>
                        <div class="text-muted petit">
                            <i class="fa-regular fa-clock me-1"></i>
                            {{ $topic->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
                @auth
                <div class="options-dropdown position-relative">
                    <button class="btn btn-sm">
                        <i class="fa-solid fa-ellipsis"></i>
                    </button>

                    <div class="options-list">
                        @can('update', $topic)
                        <a href="{{ route('topics.edit', $topic->id) }}">
                            ✏️ Modifier
                        </a>
                        @endcan

                        @can('delete', $topic)
                        <form action="{{ route('topics.destroy', $topic->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit">🗑️ Supprimer</button>
                        </form>
                        @endcan
                    </div>
                </div>
                @endauth
            </div>

            <!-- TITLE -->
            <h5 class="fw-bold small mt-3">
                {{ ucfirst($topic->title) }}
            </h5>

            <!-- THEMES -->
            <div class="mb-2 petit">
                @foreach($topic->themes as $theme)
                    <span class="badge text-secondary small">
                        {{ ucfirst($theme->nom) }}
                    </span>
                @endforeach
            </div>

            <!-- CONTENT -->
            <p class="small ps-2">
                {!! $topic->content !!}
            </p>

            <!-- IMAGES -->
            @foreach($images as $image)
                <img src="{{ asset('storage/images/'.$image->path) }}"
                     class="img-fluid rounded mb-2">
            @endforeach

        

        <!-- COMMENT INPUT -->
        @auth
        
            <form action="{{ route('commentaires.store',['topic'=>$topic->id]) }}" method="POST">
                @csrf
                <div class="comment-input-container">
                            <div class="d-flex align-items-center">
                                @if ($topic->user->profil)
                                    <img src="{{ asset('storage/profil/' .$user->profil) }}" alt="Photo de profil" class="rounded-circle me-2" width="38" height="38">
                                @else
                                    <img src="{{ asset('default-profile.png') }}" alt="Photo de profil par défaut" class="rounded-circle me-2" width="38" height="38">
                                @endif
                            </div>
                    <textarea name="content" class="comment-input pb-0 " placeholder="Écrire un commentaire..." required></textarea>
                    <button class="comment-button ">
                        <i class="fa-regular fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        
        @endauth

        <!-- COMMENTS -->
        @forelse($topic->commentaires->where('id_parent', null) as $commentaire)
        <div class="d-flex my-3">

            <img src="{{ $commentaire->user->profil 
                ? asset('storage/profil/'.$commentaire->user->profil)
                : asset('default-profile.png') }}"
                class="rounded-circle me-2  "
                width="40" height="40">

            <div class="flex-grow-1 small">

                <div class="comment-box position-relative ">

                    <strong>{{ ucfirst($commentaire->user->nom) }}</strong>
                    <div class="text-muted petit">
                        {{ $commentaire->created_at->diffForHumans() }}
                    </div>

                    <div class="small mt-1">
                        {{ ucfirst($commentaire->content) }}
                    </div>

                    <!-- DELETE -->
                    @can('delete', $commentaire)
                    <form action="{{ route('commentaires.destroy',['topic'=>$topic->id,'commentaire'=>$commentaire->id]) }}"
                          method="POST"
                          class="position-absolute top-0 end-0 m-2">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm text-danger">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                    @endcan

                </div>

                <!-- REPLIES -->
                @foreach($commentaire->replies as $reply)

                <div class="d-flex mt-2 ms-4">

                    <img src="{{ $reply->user->profil 
                        ? asset('storage/profil/'.$reply->user->profil)
                        : asset('default-profile.png') }}"
                        class="rounded-circle me-2"
                        width="35" height="35">

                    <div class="comment-box flex-grow-1 position-relative ">

                        <strong>{{ ucfirst($reply->user->nom) }}</strong>

                        <div class="text-muted petit">
                            {{ $reply->created_at->diffForHumans() }}
                        </div>

                        <div class="small mt-1">
                            {{ ucfirst($reply->content) }}
                        </div>

                        <!-- DELETE REPLY -->
                        @can('delete', $reply)
                        <form action="{{ route('commentaires.destroy',['topic'=>$topic->id,'commentaire'=>$reply->id]) }}"
                              method="POST"
                              class="position-absolute top-0 end-0 m-2">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm text-danger">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                        @endcan

                    </div>

                </div>

                @endforeach

                @auth
                <div class="ms-5 pt-3">
                    <form action="{{ route('commentaires.store', ['topic' => $topic->id]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_parent" value="{{ $commentaire->id }}">
                        <div class="comment-input-container">
                            <div class="d-flex align-items-center">
                                @if ($topic->user->profil)
                                    <img src="{{ asset('storage/profil/' .$user->profil) }}" alt="Photo de profil" class="rounded-circle me-2" width="35" height="35">
                                @else
                                    <img src="{{ asset('default-profile.png') }}" alt="Photo de profil par défaut" class="rounded-circle me-2" width="35" height="35">
                                @endif
                            </div>
                            <textarea class="comment-input" placeholder="Écrivez une réponse" aria-label="Écrivez une réponse" name="content" required></textarea>
                            <button type="submit" class="comment-button " >
                                <i class="fa-regular fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
                @endauth

            </div>
        </div>

        @empty
        <div class="text-center text-muted my-2 small  ">
            💬 Aucun commentaire pour le moment
        </div>
        @endforelse

    </div>
    
</div>

    <!-- 🟦 GAUCHE -->
    <div class="col-md-3 order-md-first">
        @auth
            @include('components.sidebar')
        @endauth

        @guest
        <div class="card text-center" ">

            <div class="card-body">

                <!-- Titre comme sidebar -->
                <h5 class="pb-2 mb-3 border-bottom fw-bold text-uppercase">
                    Bienvenue
                </h5>

                <p class="small text-muted">
                    Créez des sujets et participez à la discussion.
                </p>

                <!-- Bouton style create-post -->
                <div class="d-flex justify-content-center mt-3">
                    <a href="{{ route('login') }}" class="button">
                        Se connecter
                    </a>
                </div>

                <!-- optionnel -->
                <div class="mt-2">
                    <a href="{{ route('register') }}" class="route-a text-decoration-none">
                        S’inscrire
                    </a>
                </div>

            </div>

        </div>
    @endguest
    </div>

</div>
</div>

@endsection