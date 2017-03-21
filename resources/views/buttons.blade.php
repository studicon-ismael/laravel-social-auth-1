@foreach($socialProviders as $provider)
    <a
            href="{{ route('social.auth', [$provider->slug]) }}"
            class="btn btn-lg waves-effect waves-light btn-block {{ $provider->slug }}">
        {{ $provider->label }}
    </a>
@endforeach
