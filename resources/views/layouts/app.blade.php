<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">   
    <link rel="stylesheet" href="{{ asset('fontawesome-free-6.5.2-web/css/all.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</head>

<body class="bg-light ">
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">       
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('storage/images/logo_yool.png') }}" alt="Logo" height="25" class="ms-2" >
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="small collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav w-100 d-flex justify-content-center ">
                    @auth
                    <li class="nav-item ps-3" >
                        <a class="nav-link" href="{{ route('topics.index2') }}">Accueil</a>
                    </li>
                    <li class="nav-item ps-3" >
                        <a class="nav-link" href="{{ route('profile.show') }}">Profil</a>
                    </li>
                    @endauth
                    <li class="ps-3 pt-lg-0 pt-3 ">
                        <form action="{{ route('topics.search') }}" method="GET" class="d-flex ">
                            <input class="form-control border-warning me-2 " type="search" placeholder="Rechercher" aria-label="Search" name="query">
                            <button class="btn btn-outline-warning" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </form>
                    </li>
                    @auth
                    <li class="nav-item ps-3 mt-3 mt-lg-0">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#notificationsModal">
                            <span class="position-relative">
                                <i class="fa-solid fa-bell" style="font-size:18px;"></i>
                                <span id="notifCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" >
                                    0
                                </span>
                            </span>
                            
                        </a>
                    </li>
                    @endauth
                </ul>
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 ps-3 ">
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fa-solid fa-arrow-right-to-bracket small"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            <i class="fa-solid fa-user-plus small"></i> s'inscrire
                        </a>
                    </li>
                    @endguest
                    @auth
                    <li class="nav-item ">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="small btn btn-sm  nav-link ">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i> Déconnexion
                            </button>
                        </form>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    
    @if(session()->has('success'))
        <div class="custom-toast success-toast">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="custom-toast error-toast">
            <i class="fa-solid fa-circle-exclamation me-2"></i>
            {{ session('error') }}
        </div>
    @endif
    @yield('content')

    <!-- Modal Notifications -->
    <div class="modal fade" id="notificationsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header">
                    <h5 class="modal-title">🔔 Notifications</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body p-0">
                    <ul class="list-group list-group-flush" id="notificationsList">
                        <li class="list-group-item text-center text-muted">
                            Chargement...
                        </li>
                    </ul>
                </div>

                <!-- FOOTER LINK -->
                <div class="justify-content-center d-flex my-2 ">
                    <a href="/notifications" class="text-decoration-none ">
                        Voir toutes les notifications →
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        fetchNotifications();
        setInterval(fetchNotifications, 5000);

    document.getElementById('notificationsModal')
        .addEventListener('show.bs.modal', function () {

            // 🔥 supprimer badge immédiatement
            const badge = document.getElementById('notifCount');
            badge.innerText = 0;
            badge.style.display = "none";

            // 🔥 marquer toutes comme lues (backend)
            fetch("/notifications/read-all", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            });

            // 🔄 refresh notifications
            fetchNotifications();
        });
        });

    function fetchNotifications() {
        fetch("{{ route('notifications.json') }}")
            .then(res => res.json())
            .then(data => {

                const list = document.getElementById('notificationsList');
                const badge = document.getElementById('notifCount');

                let unread = data.filter(n => !n.read).length;

                badge.innerText = unread;
                badge.style.display = unread > 0 ? "inline-block" : "none";

                list.innerHTML = '';

                if (data.length === 0) {
                    list.innerHTML = `
                        <li class="list-group-item text-center text-muted">
                            Aucune notification
                        </li>
                    `;
                    return;
                }

                data.forEach(n => {

                    let link = "#";
                    let icon = "🔔";

                    if (n.type === 'follow') {
                        link = "/profile/" + n.from_user_id;
                        icon = "👤";
                    } 
                    else if (n.type === 'comment' || n.type === 'reply') {
                        link = "/topics/" + n.topic_id;
                        icon = "💬";
                    }
                    else if (n.type === 'topic') {
                        link = "/topics/" + n.topic_id;
                        icon = "📝";
                    }

                    // ✅ convertir la date proprement
                    let date = new Date(n.created_at);
                    let formattedDate = date.toLocaleString('fr-FR', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    const li = document.createElement('li');
                    li.className = "list-group-item"
                        + (!n.read ? " notif-unread" : "");

                    li.innerHTML = `
                        <a href="${link}" class="notif-link d-block px-2 py-1" >
                            <div>
                                ${icon} ${n.message}
                            </div>
                            <small class="text-muted">${formattedDate}</small>
                        </a>
                    `;

                    list.appendChild(li);
                });
            });
    }

    </script>

@stack('scripts')

</body>
</html>
