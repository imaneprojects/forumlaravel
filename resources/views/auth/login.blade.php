    @extends('layouts.app')

    @section('content')
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div style="display: flex; justify-content: center;"><img src="{{ asset('images/logo_yool.png') }}" alt="Description de votre photo"></div>
                
                <div class="col-md-6 mt-3 justify-content-center " style="display: flex;">
                    <div class="card" width="400px">
                        <div class="card-body">
                            <div class="text-center">se connecter</div>
                            <div class="text-center text-secondary small mb-3"><p> Accès à notre tableau de bord</p></div>
                            
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

                            <form action="/login" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="email" class="form-label">Adresse e-mail</label>
                                    <input type="text" id="email" name="email" class="form-control bg-light" value="{{ old('email') }}" autofocus>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label " >Mot de passe</label>
                                    <input type="password" id="password" name="password" class="form-control bg-light">
                                </div>
                                <br>
                                <div class="mb-3 btn" >
                                    <button class="button" style="width: 350px; height:39px" type="submit">Se connecter</button>
                                </div>
                                <div class="mb-3 text-center " >
                                    <p>Vous n'avez pas de compte? <a style="color:rgb(249, 143, 39); text-decoration:none;" href="{{ route('register') }}">  s'inscrire</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

