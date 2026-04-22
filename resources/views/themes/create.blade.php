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

            <form method="POST" action="{{ route('themes.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- TITLE -->
                <div class="m-3">
                    <label class="fw-bold">Nom du thème...</label>
                    <input type="text"
                           name="nom"
                           class="form-control border-0 mt-2"
                           placeholder="Ajouter un thème..."
                    required>
                </div>

                <!-- IMAGE PREVIEW -->
                <div id="preview" class="pb-3 text-center"></div>

                <!-- ACTIONS -->
                <div class="form-group">
                    <div class="d-flex justify-content-between align-items-center">

                        <!-- BUTTON -->
                        <button type="submit" class="button">
                            Ajouter
                        </button>

                        <!-- IMAGE -->
                        <label for="image" style="cursor:pointer;">
                            <i class="fa-solid fa-paperclip"></i> Image
                        </label>

                        <input type="file"
                               id="image"
                               name="image"
                               class="d-none"
                               accept="image/*"
                               required>

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

    const input = document.getElementById('image');
    const preview = document.getElementById('preview');

    if (input && preview) {
        input.addEventListener('change', function () {

            preview.innerHTML = '';

            const file = input.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;

                    img.style.width = "120px";
                    img.style.height = "120px";
                    img.style.objectFit = "cover";
                    img.style.borderRadius = "10px";
                    img.style.boxShadow = "0 2px 8px rgba(0,0,0,0.1)";

                    preview.appendChild(img);
                };

                reader.readAsDataURL(file);
            }

        });
    }

});
</script>
@endpush