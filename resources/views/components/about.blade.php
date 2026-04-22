<div class="card mb-3">

    <!-- TITLE -->
    <div class="card-body text-center">
        <h5 class="fw-bold text-uppercase">
            Profil
        </h5>
    </div>

    <!-- LIST -->
    <div class="list-group list-group-flush ">

        <div class="list-group-item">
            <i class="fa-solid fa-user me-2 "></i>
            {{ ucfirst(auth()->user()->nom) }}
        </div>

        <div class="list-group-item">
            <i class="fa-solid fa-envelope me-2 "></i>
            {{ auth()->user()->email }}
        </div>

        <div class="list-group-item">
            <i class="fa-solid fa-map-location-dot me-2 "></i>
            {{ auth()->user()->adresse }}
        </div>

        <div class="list-group-item">
            <i class="fa-solid fa-phone me-2 "></i>
            {{ auth()->user()->telephone }}
        </div>

        <div class="list-group-item">
            <i class="fa-solid fa-cake-candles me-2"></i>
            {{ auth()->user()->date_naissance }}
        </div>

    </div>

</div>