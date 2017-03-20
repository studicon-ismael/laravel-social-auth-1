@if ($user = Auth::user())
    @foreach($socialProviders as $provider)
        @unless ($user->isAttached($provider->slug))
            <a href="{{route('social.auth', [$provider->slug])}}" class="btn btn-lg waves-effect waves-light  btn-block {{$provider->slug}}">{{$provider->label}}</a>
        @endunless
    @endforeach
@endif
