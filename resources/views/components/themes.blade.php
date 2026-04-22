<div class="card mb-3">

    <div class="card-body">
        <h5 class="titre mt-1 text-uppercase text-center">
            Thèmes
        </h5>
    </div>

    <div class="list-group list-group-flush">

        @foreach($themes as $theme)

            <a href="{{ route('topics.index2', ['theme_id' => $theme->id]) }}"
               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">

                <div class="route-a d-flex align-items-center">

                    @if ($theme->image_url)
                        <img src="{{ $theme->image_url }}"
                             width="30"
                             class="me-2 rounded">
                    @endif

                    <span>
                        {{ ucfirst($theme->nom) }}
                    </span>

                </div>

                <span class="badge bg-light text-dark" >
                    {{ $theme->topics_count }}
                </span>

            </a>

        @endforeach

    </div>

</div>