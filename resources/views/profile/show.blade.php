@extends('layouts.app')
@section('content')

<div class="container-fluid mt-3">
    <div class="row">

        {{-- LEFT SIDEBAR --}}
        <div class="col-md-3 ">
            @include('components.sidebar')
        </div>

        {{-- CENTER --}}
        <div class="col-md-6 ">
        
        <div class="profile-container text-center position-relative ">
            @if ($user->cover)
                <img src="{{ asset('storage/cover/' . $user->cover) }}" alt="Photo de couverture" class="cover-photo">
            @else
                <img src="{{ asset('images/default_cover.jpeg') }}" alt="Photo de couverture par défaut" class="cover-photo">
            @endif
            @if ($user->profil)
                <img src="{{ asset('storage/profil/' . $user->profil) }}" alt="Photo de profil" class="profile-photo">
            @else
                <img src="{{ asset('images/default_profil.jpeg') }}" alt="Photo de profil par défaut" class="profile-photo">
            @endif

            @if(Auth::check() && Auth::id() == $user->id)
            <div class="edit-cover-icon" onclick="document.getElementById('edit-cover-input').click();">
                <i class="fa-solid fa-camera"></i>
            </div>
            <form action="{{ route('profile.updateImage', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" class="edit-cover-input" id="edit-cover-input" name="cover" onchange="this.form.submit();">
            </form>
            <div class="edit-profile-icon" onclick="document.getElementById('edit-profile-input').click();">
                <i class="fa-solid fa-camera"></i>
            </div>
            <form action="{{ route('profile.updateImage', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" class="edit-profile-input" id="edit-profile-input" name="profil" onchange="this.form.submit();">
            </form>
            @endif
        </div>
        <div class="bg-white pt-4 pb-2 pe-2 d-flex align-items-center justify-content-between border">
            <div class="ps-3">
                <div class="fw-bold">{{ ucfirst($user->nom) }}</div>
                <div class="small text-muted">{{ ucfirst($user->role) }}</div>
            </div>
            @if(Auth::check() && Auth::id() == $user->id)
                <a href="{{ route('profile.edit', $user->id) }}" class="small custom-button text-decoration-none" >Edit profile</a>
            @endif
        </div>

        <!--partie 2-->

        <div class="row">
            <div class="mt-2">
                <a type="button" class="btn btn-light active bold-font" id="topics-tab" onclick="showContent('topics-content', 'topics-tab')">Posts </a>
                <a type="button" class="btn btn-light" id="followers-tab" onclick="showContent('followers-content', 'followers-tab')"> Followers </a>
                <a type="button" class="btn btn-light" id="followed-tab" onclick="showContent('followed-content', 'followed-tab')"> Suivi(e)s</a>
            </div>
            <div class="card-body ">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="topics-content">
                    @foreach($topics as $topic)
                        <div class="topic-item mb-4 bg-white">
                            @if($topic->images->isNotEmpty())
                                <div class=" mb-2">
                                    @foreach($topic->images as $image)
                                        <img src="{{ Storage::url('images/' . $image->path) }}" class="img-fluid" alt="Image">
                                    @endforeach
                                </div>
                            @endif
                                <div class="d-flex justify-content-between align-items-center ">
                                    <a href="{{ route('topics.show', $topic->id) }}"
                                    class="topic-title ms-2 ">
                                        {{ ucfirst($topic->title) }}
                                    </a>
                                    <div class="d-flex gap-1 flex-wrap petit">
                                        @foreach($topic->themes as $theme)
                                            <span class="badge text-secondary small">
                                                {{ ucfirst($theme->nom) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>

                                <p class="content mx-2 small mt-1">{{ ucfirst($topic->content) }}</p>
                                <p class="text-secondary d-flex justify-content-between ">
                                    <span class="d-flex align-items-center petit ms-2 mb-2" >
                                        <i class="fa-regular fa-eye me-1"></i>
                                        {{ $topic->views_count }}
                                    </span>
                                    <span class="d-flex align-items-center justify-content-center petit me-2 mb-2" >
                                        <i class="fa-regular fa-clock me-1"></i>
                                        {{ $topic->created_at->diffForHumans() }}
                                    </span>
                                </p> 
                        </div>
                    @endforeach
                    </div>
                    <div class="card tab-pane fade bg-white" id="followers-content">
                        <div class="list-unstyled m-0 p-0">
                            @foreach($followers as $follower)
                            <div class="d-flex justify-content-between align-items-center m-2">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('storage/profil/' . $follower->profil) }}" alt="Photo de profil" class="rounded-circle me-2" width="40" height="40">
                                    <a href="{{ route('profile.showUser', $follower->id) }}" class="text-decoration-none user-topic">{{ ucfirst($follower->nom) }}</a>
                                </div>
                                <form method="POST" action="{{ route('profile.removeFollower', $follower->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="custom-button ">Supprimer</button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="card tab-pane fade bg-white" id="followed-content">
                        <div class="list-unstyled m-0 p-0">
                            @foreach($followed as $followedUser)
                            <div class="d-flex justify-content-between align-items-center m-2">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('storage/profil/' . $followedUser->profil) }}" alt="Photo de profil" class="rounded-circle me-2" width="40" height="40">
                                    <a href="{{ route('profile.showUser', $followedUser->id) }}" class="text-decoration-none user-topic" >{{ ucfirst($followedUser->nom) }}</a>
                                </div>
                                <form method="POST" action="{{ route('profile.unfollow', $followedUser->id) }}" >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="custom-button" >ne plus suivre</button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT SIDEBAR --}}
    <div class="col-md-3 ">
        @include('components.topic-author')
    </div>

    </div>
</div>

<script>
    function showContent(tabId, buttonId) {
        // Masquer tous les contenus des onglets
        document.querySelectorAll('.tab-pane').forEach(function(tabContent) {
            tabContent.classList.remove('show', 'active');
        });
        // Supprimer la classe active et bold-font de tous les boutons
        document.querySelectorAll('.btn').forEach(function(tabButton) {
            tabButton.classList.remove('active', 'bold-font');
        });
        // Afficher le contenu de l'onglet sélectionné
        document.getElementById(tabId).classList.add('show', 'active');
        // Marquer le bouton sélectionné comme actif et appliquer le style gras
        document.getElementById(buttonId).classList.add('active', 'bold-font');
    }
</script>

@endsection
