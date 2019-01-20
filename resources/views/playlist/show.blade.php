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
                        <h2><a href="/list/1">The Marvel Universe</a></h2>

                        <ul class="list_menu_bar">
                            <li class="account">
                                <a href="/u/travisbell">
                                    <img class="avatar" src="https://secure.gravatar.com/avatar/c9e9fc152ee756a900db85757c29815d.jpg?s=50" srcset="https://secure.gravatar.com/avatar/c9e9fc152ee756a900db85757c29815d.jpg?s=50 1x, https://secure.gravatar.com/avatar/c9e9fc152ee756a900db85757c29815d.jpg?s=100 2x, https://secure.gravatar.com/avatar/c9e9fc152ee756a900db85757c29815d.jpg?s=150 3x" alt="Travis Bell">
                                </a>
                                <p>A list by<br><a href="/u/travisbell">Travis Bell</a></p>
                            </li>
                        </ul>

                        <h3>About this list</h3>
                        <div class="description">
                            <p>The idea behind this list is to collect the live action comic book movies from within the Marvel franchise. Last updated on Dec 18, 2013.</p>

                            <a class="rounded white button no_click" href="#"><span class="glyphicons glyphicons-sort-by-attributes"></span> Sort By</a>

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection