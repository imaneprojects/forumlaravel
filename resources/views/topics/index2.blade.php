@extends('layouts.app')

@section('content')

<div class="container-fluid bg-light py-3">
    <div class="row">

        <!-- 🟩 DROITE -->
        <div class="col-md-3 order-md-last">

            <!-- THEMES -->
            @include('components.themes', ['themes' => $themes])

            <!-- CREATE POST -->
            @auth
                @include('components.create-post')
            @endauth

        </div>

        <!-- 🟨 CENTRE -->
        <div class="col-md-6">

            @foreach ($topics as $topic)

                <div class="card mb-3 p-3">

                    <!-- USER -->
                    <div class="d-flex align-items-center mb-2">

                        <img src="{{ $topic->user->profil 
                            ? asset('storage/profil/' . $topic->user->profil)
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

                    <!-- TITLE + THEMES -->
                    <div class="d-flex justify-content-between align-items-center ">
                        <a href="{{ route('topics.show', $topic->id) }}"
                           class="topic-title ms-2">
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

                    <!-- CONTENT -->
                    <p class="content mt-2">
                        {!! ucfirst($topic->content) !!}
                    </p>

                    <!-- IMAGES -->
                    @if ($topic->images->count() > 0)
                        <div class="mb-2">
                            @foreach ($topic->images as $image)
                                <img src="{{ asset('storage/images/' . $image->path) }}"
                                     class="img-fluid mb-2 rounded">
                            @endforeach
                        </div>
                    @endif

                    <!-- STATS -->
                    <div class="d-flex align-items-center gap-3 text-muted small">

                        <span class="small">
                            <i class="fa-regular fa-eye me-1"></i>
                            {{ $topic->views_count }}
                        </span>

                        @php
                            $likeColor = (auth()->check() && $topic->isLikedBy(auth()->user())) ? 'red' : 'gray';
                        @endphp

                        <form action="{{ route('topics.like', $topic->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="small"
                                    style="border:none; background:none; color: {{ $likeColor }};">
                                <i class="fa-solid fa-heart me-1"></i>
                                {{ $topic->likes->count() }}
                            </button>
                        </form>

                    </div>

                </div>

            @endforeach

        </div>

        <!-- 🟦 GAUCHE -->
<div class="col-md-3 order-md-first">

    @auth
        @include('components.sidebar')
    @endauth

    @guest
        <div class="card mt-3 text-center" ">

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