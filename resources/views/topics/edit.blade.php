@extends('layouts.app')

@section('content')

<div class="container-fluid bg-light" style="min-height: 100vh;">
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

            <form action="{{ route('topics.update', $topic->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- TITLE -->
                <div class="m-3">
                    <label class="fw-bold">Modifier le titre...</label>
                    <input type="text"
                           name="title"
                           class="form-control border-0 mt-2"
                           value="{{ $topic->title }}">
                </div>

                <!-- CONTENT -->
                <textarea class="form-control border-0 custom-textarea"
                          id="task-text"
                          name="content">{{ $topic->content }}</textarea>

                <!-- IMAGES -->
                <div class="selected-images pb-3 mt-2" id="image-container">
                    @foreach($topic->images as $image)
                        <img src="{{ asset('storage/images/'.$image->path) }}"
                             style="width:70px;height:70px;object-fit:cover;margin:5px;border-radius:6px;">
                    @endforeach
                </div>

                <!-- ACTIONS -->
                <div class="form-group mt-2">
                    <div class="d-flex justify-content-between align-items-center">

                        <!-- BUTTON -->
                        <button type="submit" class="button">
                            Modifier
                        </button>

                        <!-- IMAGE -->
                        <label for="images" style="cursor:pointer;">
                            <i class="fa-solid fa-paperclip"></i> Image
                        </label>
                        <input type="file" name="images[]" id="images" class="d-none" multiple>

                        <!-- THEME -->
                        <div class="position-relative">

                            <button type="button" id="theme-toggle" class="bg-transparent border-0">
                                <i class="fa-solid fa-shapes"></i> Thème
                            </button>

                            <div id="theme-options" class="theme-dropdown">
                                @foreach($themes as $theme)
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="theme_id[]"
                                               value="{{ $theme->id }}"
                                               {{ $topic->themes->contains($theme->id) ? 'checked' : '' }}>

                                        <label class="form-check-label">
                                            {{ ucfirst($theme->nom) }}
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

    const themeToggle = document.getElementById('theme-toggle');
    const themeOptions = document.getElementById('theme-options');

    // toggle dropdown
    if (themeToggle && themeOptions) {
        themeToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            themeOptions.style.display =
                themeOptions.style.display === "block" ? "none" : "block";
        });
    }

    // fermer si clic ailleurs
    document.addEventListener('click', function (e) {
        if (!themeToggle.contains(e.target) && !themeOptions.contains(e.target)) {
            themeOptions.style.display = "none";
        }
    });

    // preview images
    const input = document.getElementById('images');
    const preview = document.getElementById('image-container');

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