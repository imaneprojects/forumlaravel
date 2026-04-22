@extends('layouts.app')

@section('content')
    <div class="container mt-3">
        <div class="row justify-content-center">
            <div style="display: flex; justify-content: center;"><img src="{{ asset('images/logo_yool.png') }}" alt="Description de votre photo"></div>
                
                <div class="col-md-6 mt-3 justify-content-center " style="display: flex;">
                    <div class="card" width="400px">
                        <div class="card-body"> 
                            <div class="text-center">s'inscrire</div>
                            <div class="text-center text-secondary small mb-3"><p>Accès à notre tableau de bord</p></div>
                            
                                @if($errors->any())
                                    <div class="alert alert-danger">
                                        <div>Quelque chose s'est mal passé !</div>
                                        <ul>
                                            @foreach($errors->all() as $error)
                                                <li>{{$error}}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="/register" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="nom" class="form-label">Nom</label>
                                        <input type="text" id="nom" name="nom" class="form-control bg-light" value="{{ old('nom') }}" autofocus>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">E-mail</label>
                                        <input type="text" id="email" name="email" class="form-control bg-light " value="{{ old('email') }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">mot de passe</label>
                                        <input type="password" id="password" name="password" class="form-control bg-light">
                                    </div>
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Confirmation mot de passe</label>
                                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control bg-light">
                                    </div>
                                    <div>
                                        <button class="button" style="width: 350px; height:39px" type="submit">s'inscrire</button>
                                    </div>
                                    <div class="text-center mt-4 ">
                                        <p>Vous avez deja un compte? <a style="color:rgb(249, 143, 39); text-decoration:none;" href="{{ route('login') }}">  Se connecter</a></p>
                                    </div>
                                </form>
                        </div>
                    </div>
                </div>
            </div>
    </div>    
@endsection

