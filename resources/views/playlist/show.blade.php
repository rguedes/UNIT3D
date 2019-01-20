@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('playlists.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">Playlists</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="block">
            <section class="inner_content header" style="background-image: url({{ $movie->backdrop ?? 'https://via.placeholder.com/1400x800' }});">
                <div class="bg_filter">
                    <div class="single_column">
                        <h2><a href="/list/1">{{ $playlist->name }}</a></h2>

                        <ul class="list_menu_bar">
                            <li class="account">
                                <a href="#!">
                                    @if ($playlist->user->image != null)
                                        <img src="{{ url('files/img/' . $playlist->user->image) }}" alt="{{ $playlist->user->username }}" style=" width: 50px">
                                    @else
                                        <img src="{{ url('img/profile.png') }}" alt="{{ $playlist->user->username }}" style=" width: 50px">
                                    @endif
                                </a>
                                <p>A list by<br><a href="/u/travisbell">{{ $playlist->user->username }}</a></p>
                            </li>
                        </ul>

                        <h3 class="text-bold">About this list</h3>
                        <div class="description">
                            <p>{{ $playlist->description }}</p>

                            @if(auth()->user()->id == $playlist->user_id)
                            <a class="rounded white button no_click" href="#"><i class="fa fa-search-plus"></i> Add Torrent</a>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="block">
            <div class="row">
                @foreach($torrents as $k => $t)
                    @php $client = new \App\Services\MovieScrapper(config('api-keys.tmdb'), config('api-keys.tvdb'), config('api-keys.omdb')); @endphp
                    @if ($t->category_id == 2)
                        @if ($t->tmdb || $t->tmdb != 0)
                            @php $movie = $client->scrape('tv', null, $t->tmdb); @endphp
                        @else
                            @php $movie = $client->scrape('tv', 'tt'. $t->imdb); @endphp
                        @endif
                    @else
                        @if ($t->tmdb || $t->tmdb != 0)
                            @php $movie = $client->scrape('movie', null, $t->tmdb); @endphp
                        @else
                            @php $movie = $client->scrape('movie', 'tt'. $t->imdb); @endphp
                        @endif
                    @endif
                    <div class="col-xs-6 col-sm-4 col-md-2">
                        <div class="image-box text-center mb-20">
                            <div class="overlay-container">
                                <img class='details-poster' src="{{ $movie->poster }}">
                                <div class="overlay-top">
                                    <div class="text">
                                        <h2>
                                            <a data-id="{{ $t->id }}" data-slug="{{ $t->slug }}" href="{{ route('torrent', array('slug' => $t->slug, 'id' => $t->id)) }}">{{ $t->name }}</a>
                                        </h2>
                                    </div>
                                </div>
                                <div class="overlay-bottom">
                                    <div class="links">
                                        <span class='label label-success'>{{ $t->type }}</span>
                                        <div class="separator mt-10"></div>
                                        <ul class="list-unstyled margin-clear">
                                            <li><i class="{{ config('other.font-awesome') }} fa-arrow-up"></i> {{ trans('torrent.seeders') }}: {{ $t->seeders }}</li>
                                            <li><i class="{{ config('other.font-awesome') }} fa-arrow-down"></i> {{ trans('torrent.leechers') }}: {{ $t->leechers }}</li>
                                            <li><i class="{{ config('other.font-awesome') }} fa-check"></i> {{ trans('torrent.completed') }}: {{ $t->times_completed }}</li>
                                            <li><i class="{{ config('other.font-awesome') }} fa-server"></i> {{ trans('torrent.size') }}: {{ $t->getSize() }}</li>
                                            <li>
                                                <a rel="nofollow" href="https://www.imdb.com/title/tt{{ $t->imdb }}" title="IMDB" target="_blank"><span class='label label-success'>{{ trans('common.view') }} IMDB</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    </div>
@endsection