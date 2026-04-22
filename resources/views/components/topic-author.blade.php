<div class="card mb-3">

    <!-- USER -->
    <div class="d-flex align-items-center p-2">

        <img src="{{ $user->profil 
            ? asset('storage/profil/' . $user->profil)
            : asset('default-profile.png') }}"
            class="rounded-circle me-2"
            width="50" height="50">

        <div>
            <a href="{{ route('profile.showUser', $user->id) }}"
               class="fw-bold text-decoration-none text-dark">
                {{ ucfirst($user->nom) }}
            </a>
        </div>

    </div>

    <!-- FOLLOW -->
    @auth
    <div class="mx-3">
        @if(Auth::id() !== $user->id)

            @if(Auth::user()->followed->contains($user->id))
                <form action="{{ route('profile.unfollow', $user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm w-100">
                        Ne plus suivre
                    </button>
                </form>
            @else
                <form action="{{ route('profile.follow', $user->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-primary btn-sm w-100">
                        Suivre
                    </button>
                </form>
            @endif

        @endif
    </div>
    @endauth

    <!-- INFOS -->
    <ul class="list-group list-group-flush mt-3 small">
        <li class="list-group-item">
            <i class="fa-solid fa-envelope me-2"></i>
            {{ $user->email }}
        </li>

        <li class="list-group-item">
            <i class="fa-solid fa-map-location-dot me-2"></i>
            {{ $user->adresse }}
        </li>

        <li class="list-group-item">
            <i class="fa-solid fa-phone me-2"></i>
            {{ $user->telephone }}
        </li>

        <li class="list-group-item">
            <i class="fa-solid fa-cake-candles me-2"></i>
            {{ $user->date_naissance }}
        </li>
    </ul>

</div>