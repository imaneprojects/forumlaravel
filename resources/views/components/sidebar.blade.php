<div class="card mb-3">

    <div class="card-body text-center">

        <h5 class="pb-3 border-bottom text-uppercase">
            Tableau de bord
        </h5>

        @if(auth()->user()->profil)
            <img src="{{ asset('storage/profil/' . auth()->user()->profil) }}"
                 class="rounded-circle mt-2"
                 width="80" height="80">
        @else
            <img src="{{ asset('default-profile.png') }}"
                 class="rounded-circle mt-2"
                 width="80">
        @endif

        <div class="mt-2 text-uppercase fw-bold">
            {{ ucfirst(auth()->user()->nom) }}
        </div>

        <small class="text-muted">
            {{ ucfirst(auth()->user()->role) }}
        </small>

    </div>

    <div class="list-group list-group-flush">

        <a href="{{ route('profile.edit', auth()->id()) }}" class="list-group-item">
            <i class="me-1 fa-solid fa-pen-to-square"></i> Editer le profil
        </a>

        @if(auth()->user()->role === 'admin')
        <a href="{{ route('themes.create') }}" class="list-group-item">
            <i class="me-1 fa-solid fa-circle-plus"></i> Ajouter un thème
        </a>
        @endif

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="list-group-item border-0 text-start">
                <i class="me-1 fa-solid fa-arrow-right-from-bracket"></i>
                Se déconnecter
            </button>
        </form>

    </div>

</div>