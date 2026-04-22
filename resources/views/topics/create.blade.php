@extends('layouts.app')
@section('content')


<div class="container-fluid bg-light">
<div class="row">

    <!-- 🟩 DROITE -->
    <div class="col-md-3 order-md-last mt-3">    
        @auth
            @include('components.about')
        @endauth
    </div>
    
    <!-- 🟨 CENTRE -->
    <div class="col-md-6 mt-3">
        
        <div class="card p-3">
            <form action="{{ route('topics.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- TITLE -->
            <div class="m-3">
                <label class="fw-bold">Titre du contenu ici...</label>
                <input type="text"
                       class="form-control border-0 mt-2"
                       name="title"
                       required
                       placeholder="Ajouter un Titre...">
            </div>

            <!-- CONTENT -->
            <textarea class="form-control border-0 custom-textarea"
                      name="content"
                      placeholder="Écrivez le contenu de votre message ici"></textarea>

            <!-- PREVIEW IMAGES -->
            <div class="selected-images pb-3" id="selected-images"></div>

            <!-- ACTIONS -->
            <div class="form-group">
                <div class="d-flex justify-content-between align-items-center">

                    <!-- BUTTON -->
                    <button type="submit" class="button">
                        Publish
                    </button>

                    <!-- IMAGE -->
                    <label for="images" style="cursor:pointer;">
                        <i class="fa-solid fa-paperclip"></i> Image
                    </label>
                    <input type="file" name="images[]" id="images" class="d-none" multiple>

                    <!-- THEME DROPDOWN -->
                    <div class="position-relative">

                        <button type="button" id="theme-toggle" class="bg-transparent border-0">
                            <i class="fa-solid fa-shapes"></i> Thème
                        </button>
                        
                        @error('theme_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror

                        <div id="theme-options" class="theme-dropdown">
                            @foreach($allThemes as $theme)
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="theme_id[]"
                                           value="{{ $theme->id }}">

                                    <label class="form-check-label">
                                        {{ $theme->nom }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                    </div>

                </div>
            </div>

            </form>
        </div>
        
    </div>

    <!-- 🟦 GAUCHE -->
    <div class="col-md-3 order-md-first mt-3">
        @auth
            @include('components.sidebar')
        @endauth
    </div>

</div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    // THEME DROPDOWN
    const themeToggle = document.getElementById('theme-toggle');
    const themeOptions = document.getElementById('theme-options');

    if (themeToggle && themeOptions) {

        themeToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            themeOptions.style.display =
                themeOptions.style.display === "block" ? "none" : "block";
        });

        // fermer si clique ailleurs
        document.addEventListener('click', function (e) {
            if (!themeToggle.contains(e.target) && !themeOptions.contains(e.target)) {
                themeOptions.style.display = "none";
            }
        });
    }

    // PREVIEW IMAGE
    const input = document.getElementById('images');
    const preview = document.getElementById('selected-images');

    if (input && preview) {
        input.addEventListener('change', function () {

            preview.innerHTML = '';

            Array.from(input.files).forEach(file => {

                const reader = new FileReader();

                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;

                    img.style.width = "70px";
                    img.style.height = "70px";
                    img.style.objectFit = "cover";
                    img.style.margin = "5px";
                    img.style.borderRadius = "6px";

                    preview.appendChild(img);
                };

                reader.readAsDataURL(file);
            });

        });
    }

});
</script>
@endpush