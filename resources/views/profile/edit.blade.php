@extends('layouts.app')

@section('content')

<div class="container-fluid mt-3">
    <div class="row">

        {{-- LEFT SIDEBAR --}}
        <div class="col-md-3">
            @include('components.sidebar')
        </div>

        {{-- CENTER --}}
        <div class="col-md-6">

            {{-- FORM --}}
            <div class="card-body bg-white mt-2">

                <form action="{{ route('profile.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nom</label>
                        <input type="text" name="nom" class="form-control" value="{{ $user->nom }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" class="form-control">
                        <small class="text-muted">Laissez vide si vous ne voulez pas changer</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirmer mot de passe</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    @if(Auth::user()->role === 'admin')
                    <div class="mb-3">
                        <label class="form-label">Rôle</label>
                        <select name="role" class="form-control">
                            <option value="etudiant" {{ $user->role=='etudiant' ? 'selected' : '' }}>Étudiant(e)</option>
                            <option value="enseignant" {{ $user->role=='enseignant' ? 'selected' : '' }}>Enseignant(e)</option>
                            <option value="admin" {{ $user->role=='admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Date naissance</label>
                        <input type="date" name="date_naissance" class="form-control" value="{{ $user->date_naissance }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Adresse</label>
                        <input type="text" name="adresse" class="form-control" value="{{ $user->adresse }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="telephone" class="form-control" value="{{ $user->telephone }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control">{{ $user->description }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sexe</label>
                        <select name="sexe" class="form-control">
                            <option value="">Choisir</option>
                            <option value="homme" {{ $user->sexe=='homme' ? 'selected' : '' }}>Homme</option>
                            <option value="femme" {{ $user->sexe=='femme' ? 'selected' : '' }}>Femme</option>
                        </select>
                    </div>

                    <button type="submit" class="custom-button w-100">
                        Enregistrer les modifications
                    </button>

                </form>

            </div>
        </div>

        {{-- RIGHT SIDEBAR --}}
        <div class="col-md-3">
            @include('components.topic-author', ['user' => $user])
        </div>

    </div>
</div>

@endsection