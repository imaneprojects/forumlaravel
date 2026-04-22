<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('fontawesome-free-6.5.2-web/css/all.min.css') }}">
    <style>
        .edit-button2{
            border: 0.5px solid rgb(33, 97, 140); /* Bordure bleue */
            background-color: rgb(33, 97, 140); /* Arrière-plan blanc */
            color: white; /* Texte bleu */
            padding: 6px 8px; /* Réduction du padding */
            transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease; /* Transition pour le survol */
            border-radius: 4px; /* Angles arrondis */
            font-size: 0.875rem; /* Réduction de la taille de la police */
            box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.3); /* Ombre plus prononcée au survol */
            width: 120px;
        }
        .edit-button2:hover{
            background-color: white;

        }
        #icons {
            color: rgb(33, 97, 140);
            margin-left: 8px;
        }
        .bold-font {
            font-weight: bold !important;
        }
        .line-spacing {
            margin-bottom: 10px; /* Espace entre les lignes */
        }
        .custom-button {
            border: 0.5px solid rgb(33, 97, 140); /* Bordure bleue */
            background-color: white; /* Arrière-plan blanc */
            color: rgb(33, 97, 140); /* Texte bleu */
            padding: 5px 8px; /* Réduction du padding */
            cursor: pointer; /* Curseur pointeur */
            transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease; /* Transition pour le survol */
            border-radius: 8px; /* Angles arrondis */
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2); /* Ombre sous le bouton */
            font-size: 0.875rem; /* Réduction de la taille de la police */
        }
        .custom-button:hover {
            background-color: rgb(33, 97, 140); /* Arrière-plan bleu au survol */
            color: white; /* Texte blanc au survol */
            box-shadow: 4px 4px 10px rgba(0, 0, 0, 0.3); /* Ombre plus prononcée au survol */
        }
        .custom-button:focus {
            outline: none; /* Supprimer le contour par défaut */
            box-shadow: 0 0 0 4px rgba(26, 82, 118, 0.3); /* Ajout d'une ombre pour le focus */
        }
        .profile-container2 {
            position: relative;
            text-align: center;
        }
        .profile-container2 img.profile-photo2 {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            position: absolute;
            bottom: -70px;
            left: center;
            transform: translateX(-50%);
            border: 2px solid white;
        }
        .profile-container {
            position: relative;
            text-align: center;
        }
        .profile-container img.cover-photo {
            width: 100%;
            height: 260px;
            object-fit: cover;
        }
        .profile-container img.profile-photo {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            position: absolute;
            bottom: -25px;
            left: 9.5%;
            transform: translateX(-50%);
            border: 2px solid white;
        }
        .edit-cover-icon, .edit-profile-icon {
            position: absolute;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 8px;
            border-radius: 50%;
            cursor: pointer;
            width: 40px;
            
        }
        .edit-cover-icon {
            top: 10px;
            right: 10px;
        }
        .edit-profile-icon {
            margin-bottom:-40px;
            bottom: 20px;
            left: calc(22% - 40px);
            transform: translateX(-50%);
        }
        .edit-cover-input, .edit-profile-input {
            display: none;
        }
        .edit-buttons {
            margin-top: 60px;
            padding-left: 8px;
            padding-top: 10px;
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 10px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .profile-info {
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #ddd;
            padding-top: 20px;
            padding-right: 10px;
            padding-bottom:10px ;
        }
        .user-details {
            display: flex;
            flex-direction: column;
        }
        .user-name {
            font-weight: 600;
            padding-left: 12px;
        }
        .edit-button, .follow-button {
            padding: 5px 10px;
            font-size: 16px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
        }
        .user-role {
            font-size: 13px;
            padding-left: 12px;
            color: #6c757d;
        }
                .image-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 5px;
            overflow: hidden;
        }
        .image-container img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }
    </style>
</head>

<body class="bg-light">
@extends('layouts.app')
@section('content')

<div id="app">
    <div class="row">
        <div class="col-md-3 order-md-last">
            <div class=" ">
                <div><p></p></div>
                @auth
                <div class="card mb-3 bg-white" style=" box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15); ">
                    <div class="card-body" style="font-size: 14px;" >
                        <h4 class="fs-6" style="color:rgb(33, 97, 140);font-weight: bold;margin-bottom : 18px;">About</h4>
                        @if(Auth::check() && Auth::user()->id !== $user->id)
                            @if(Auth::user()->followed->contains($user))
                                <form action="{{ route('profile.unfollow', $user->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="border: 0.5px solid rgb(192, 57, 43); background-color: rgb(192, 57, 43);color: white;padding: 5px 101.5px; border-radius: 5px; ">Unfollow</button>
                                </form>
                            @else
                                <form action="{{ route('profile.follow', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" style="border: 0.5px solid rgb(33, 97, 140); background-color: rgb(33, 97, 140);color: white;padding: 5px 110px; border-radius: 5px; ">Follow</button>
                                </form>
                            @endif
                        @endif
                        <br>
                        <i id="icons" class="fa-solid fa-user me-2" style="padding-bottom: 8px;  "></i> {{ ucfirst($user->nom )}}
                        <div style="border-top: 1px solid #ddd ;padding-top: 8px; "></div>
                        <i id="icons" class="fa-solid fa-envelope me-2" style="padding-bottom: 8px; "></i> {{ ucfirst($user->email) }}
                        <div style="border-top: 1px solid #ddd;padding-top: 8px; "></div>
                        <i id="icons" class="fa-solid fa-map-location-dot me-2" style="padding-bottom: 8px; "></i> {{ ucfirst($user->adresse) }}
                        <div style="border-top: 1px solid #ddd;padding-top: 8px; " ></div>
                        <i id="icons" class="fa-solid fa-phone me-2" style="padding-bottom: 8px; "></i> {{ $user->telephone }}
                        <div style="border-top: 1px solid #ddd;padding-top: 8px; " style="padding-bottom: 8px; "></div>
                        <i id="icons" class="fa-solid fa-cake-candles me-2"></i> {{ $user->date_naissance }}</p>
                    </div>
                </div>
                @endauth
            </div>
        </div>
        <div class="col-md-6">
            <div><p></p></div>
            <!-- Deuxième partie -->
                    <div class="profile-container">
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
        <div class="profile-info">
        <div class="user-details">
            <div class="user-name" style="font-weight: 700px; ">{{ ucfirst($user->nom) }}</div>
            <div class="user-role">{{ ucfirst($user->role) }}</div>
        </div>
                @if(Auth::check() && Auth::user()->role == 'admin')
                    <a href="{{ route('profile.edit', $user->id) }}"  class="edit-button2" style="background-color: rgb(33, 97, 140); text-align:center; text-decoration:none; color:white; ">Edit Profile</a>
                @endif
        </div>
            <div class="row mt-3 bg-light justify-content-center">
                <div>
                    <a style="margin-left: 20px; margin-top:7px;" type="button" class="btn btn-light active bold-font" id="topics-tab" onclick="showContent('topics-content', 'topics-tab')">Posts </a>
                    <a style="margin-top:7px;" type="button" class="btn btn-light" id="followers-tab" onclick="showContent('followers-content', 'followers-tab')"> Followers </a>
                    <a style="margin-top:7px;" type="button" class="btn btn-light" id="followed-tab" onclick="showContent('followed-content', 'followed-tab')"> Following</a>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="topics-content">
                            @foreach($topics as $topic)
                            <div class="card mb-3">
                                    @if($topic->images->isNotEmpty())
                                        <div class="image-container mb-2">
                                            @foreach($topic->images as $image)
                                                <img src="{{ Storage::url('images/' . $image->path) }}" alt="Image">
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center" style="padding-left: 30px; ">
                                        <a href="{{ route('topics.show', $topic->id) }}" class="text-decoration-none">
                                            <h5 class="card-title">{{ $topic->title }}</h5>
                                        </a>
                                        <p class="card-text">
                                            @foreach($topic->themes as $theme)
                                            <span class="badge text-secondary">{{ $theme->nom }}</span>
                                            @endforeach
                                        </p>
                                    </div>
                                    <p style="font-family: Times, 'Times New Roman', Georgia, serif;padding-left: 10px; ">{{ ucfirst($topic->content) }}</p>
                                    <p class="card-text text-secondary d-flex justify-content-between" style="font-size: 12px;padding-left:10px;padding-bottom: 6px;padding-right:10px; ">
                                        <span class="d-flex align-items-center">
                                            <i class="fa-regular fa-eye me-1"></i>
                                            {{ $topic->views_count }}
                                        </span>
                                        <span class="d-flex align-items-center justify-content-center">
                                            <i class="fa-regular fa-clock me-1"></i>
                                            {{ $topic->created_at->diffForHumans() }}
                                        </span>
                                    </p>
                                
                            </div>
                            @endforeach
                        </div>
                        <div class="card tab-pane fade bg-white" id="followers-content" style="padding: 10px;">
                            <ul>
                                @foreach($followers as $follower)
                                <div class="d-flex justify-content-between align-items-center" style="padding-bottom:10px;">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('storage/profil/' . $follower->profil) }}" alt="Photo de profil" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                                        <a href="{{ route('profile.showUser', $follower->id) }}" class="text-decoration-none" style="color:black">{{ ucfirst($follower->nom) }}</a>
                                    </div>
                                </div>
                                @endforeach
                            </ul>
                        </div>
                        <div class="card tab-pane fade bg-white" id="followed-content" style="padding: 10px;">
                            <ul>
                                @foreach($followed as $followedUser)
                                <div class="d-flex justify-content-between align-items-center" style="padding-bottom:10px;">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('storage/profil/' . $followedUser->profil) }}" alt="Photo de profil" class="rounded-circle" style="width: 40px; height: 40px;margin-right:8px;">
                                        <a href="{{ route('profile.showUser', $followedUser->id) }}" style="color: black;text-decoration: none; ">{{ ucfirst($followedUser->nom) }}</a>
                                    </div>

                                </div>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 order-md-first">
            <div><p></p></div>
    @auth
    <div class="card mb-3 bg-white" style="margin-left: 10px;">
        <div class="card-body">
            <h4 class="fs-6" style="color:rgb(33, 97, 140);font-weight: bold;">Tableau de bord</h4>
        </div>
            <p style="border-top: 1px solid #ccc;"></p>
            <div class="user-details" style="text-align: center;">
                <div class="profile-container2">
                    <img src="{{ asset('storage/profil/' . auth()->user()->profil) }}" alt="Photo de profil" class="profile-photo2">
                </div>
                <div class="user-name"style="padding-top: 70px;color:rgb(33, 97, 140);text-transform: uppercase;" >{{ ucfirst(auth()->user()->nom) }}</div>
                <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
                <div style="border-top: 1px solid #ccc;margin-top: 20px;"></div>
                <div style="text-align: left; padding:10px;padding-left:20px;"><a href="{{ route('profile.edit', auth()->user()->id) }}" style=" color:rgb(33, 97, 140); text-decoration:none; font-size:13px; " ><i class="fa-solid fa-pen-to-square"></i> Editer le profil</a>
                </div><div style="border-top: 1px solid #ccc;"></div>
                <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link" style="text-align: left;padding:10px; color:rgb(33, 97, 140); text-decoration:none; font-size:13px; padding-left:20px;">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                Déconnexion
                            </button>
                </form>
            </div>
    </div>
    @endauth
        </div>
    </div>
</div>

@endsection
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
</body>
</html>
