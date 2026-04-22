@extends('layouts.app')

@section('content')

<div class="container-fluid bg-light py-3" >   
    <div class="row">

        <!-- 🟦 GAUCHE -->
        <div class="col-md-3">
            @auth
                @include('components.sidebar')
            @endauth
        </div>

        <!-- 🟨 CENTRE -->
        <div class="col-md-6">

            <h4 class="mb-3 fw-bold">🔔 Notifications</h4>

            @forelse ($notifications as $notification)

                @php
                    $link = '#';
                    $icon = 'fa-bell';
                    $color = 'secondary';

                    if ($notification->type === 'follow') {
                        $link = route('profile.showUser', $notification->from_user_id);
                        $icon = 'fa-user-plus';
                        $color = 'primary';
                    } elseif ($notification->type === 'comment') {
                        $link = route('topics.show', $notification->topic_id);
                        $icon = 'fa-comment';
                        $color = 'success';
                    } elseif ($notification->type === 'reply') {
                        $link = route('topics.show', $notification->topic_id);
                        $icon = 'fa-reply';
                        $color = 'info';
                    } elseif ($notification->type === 'topic') {
                        $link = route('topics.show', $notification->topic_id);
                        $icon = 'fa-pen';
                        $color = 'warning';
                    }
                @endphp

                <a href="{{ $link }}" class="text-decoration-none text-dark">

                    <div class="notif-card d-flex align-items-center position-relative {{ !$notification->read ? 'notif-unread' : '' }}">

                        <!-- Avatar -->
                        <img src="{{ $notification->fromUser && $notification->fromUser->profil 
                            ? asset('storage/profil/' . $notification->fromUser->profil) 
                            : asset('default-profile.png') }}"
                             class="rounded-circle notif-avatar me-3">

                        <!-- Content -->
                        <div class="flex-grow-1">
                            <div>
                                <i class="fa-solid {{ $icon }} text-{{ $color }} me-2"></i>
                                {{ $notification->message }}
                            </div>
                            <small class="text-muted">
                            {{ $notification->created_at->translatedFormat('d M Y à H:i') }}                            </small>
                        </div>

                        <!-- Dot -->
                        @if(!$notification->read)
                            <div class="notif-dot"></div>
                        @endif

                    </div>

                </a>

            @empty
                <div class="text-center text-muted mt-5">
                    😴 Aucune notification
                </div>
            @endforelse

            <div class="custom-pagination">
    {{ $notifications->links('pagination::simple-default') }}
</div>

        </div>

        <!-- 🟩 DROITE -->
        <div class="col-md-3">
            @auth
                @include('components.create-post')
            @endauth
        </div>

    </div>
</div>

@endsection