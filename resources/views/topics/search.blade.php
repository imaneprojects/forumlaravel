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

            <h4 class="fw-bold mb-3">🔍 Résultats de recherche</h4>

            @forelse ($topics as $topic)

                <div class="card mb-3 p-3">

                    <!-- USER -->
                    <div class="d-flex align-items-center mb-2">

                        <img src="{{ $topic->user->profil 
                            ? asset('storage/profil/' . $topic->user->profil) 
                            : asset('default-profile.png') }}"
                            class="rounded-circle me-2"
                            width="40"
                            height="40">

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

                    <!-- TITLE -->
                    <div>
                        <a href="{{ route('topics.show', $topic->id) }}"
                           class="topic-title">
                            {{ ucfirst($topic->title) }}
                        </a>

                        <!-- THEMES -->
                        <div class="petit">
                            @foreach($topic->themes as $theme)
                                <span class="badge text-secondary small">
                                    {{ ucfirst($theme->nom) }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- CONTENT -->
                    <p class="small mt-2">
                        {!! ucfirst($topic->content) !!}
                    </p>

                    <!-- IMAGES -->
                    @if ($topic->images && $topic->images->count() > 0)
                        <div class="mb-2">
                            @foreach ($topic->images as $image)
                                <img src="{{ asset('storage/images/' . $image->path) }}"
                                     class="img-fluid mb-2 rounded">
                            @endforeach
                        </div>
                    @endif

                    <!-- STATS -->
                    <div class="d-flex align-items-center gap-3 text-muted small">

                        <span>
                            <i class="fa-regular fa-eye me-1"></i>
                            {{ $topic->views_count }}
                        </span>

                        @php
                            $likeColor = (auth()->check() && method_exists($topic, 'isLikedBy') && $topic->isLikedBy(auth()->user()))
                                ? 'red'
                                : 'gray';
                        @endphp

                        <form action="{{ route('topics.like', $topic->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    style="border:none; background:none; color: {{ $likeColor }};">
                                <i class="fa-solid fa-heart me-1 "></i>
                                {{ $topic->likes ? $topic->likes->count() : 0 }}
                            </button>
                        </form>

                    </div>

                </div>

            @empty

                <div class="text-center text-muted mt-5">
                    😴 Aucun résultat trouvé
                </div>

            @endforelse

        </div>

        <!-- 🟦 GAUCHE -->
        <div class="col-md-3 order-md-first">

            @auth
                @include('components.sidebar')
            @endauth

            @guest
                <div class="card mt-3 text-center">

                    <div class="card-body">

                        <h5 class="pb-2 mb-3 border-bottom fw-bold text-uppercase">
                            Bienvenue
                        </h5>

                        <p class="small text-muted">
                            Connectez-vous pour publier et interagir.
                        </p>

                        <div class="d-flex justify-content-center mt-3">
                            <a href="{{ route('login') }}" class="button">
                                Se connecter
                            </a>
                        </div>

                        <div class="mt-2">
                            <a href="{{ route('register') }}" class="text-decoration-none">
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