@layout('templates.default')

<!-- Page Title -->
@section('title')
    {{ Lang::line('themes::general.title')->get() }}
@endsection

<!-- Queue Styles -->
{{ Theme::queue_asset('themes','themes::css/themes.less', 'style') }}

<!-- Styles -->
@section ('styles')
@endsection

<!-- Queue Scripts -->
{{ Theme::queue_asset('themes','themes::js/themes.js', 'jquery') }}

<!-- Scripts -->
@section('scripts')
@endsection

<!-- Page Content -->
@section('content')
<section id="themes">
    <header class="row-fluid">
        <div class="span6">
            <h1>{{ Lang::line('themes::general.title')->get() }}</h1>
            <p>{{ Lang::line('themes::general.description')->get() }}</p>
        </div>
        <nav class="actions span6 pull-right"></nav>
    </header>

    <hr />

    <div class="selections row-fluid">
        @foreach ($themes as $theme)
        <div class="active span3">
            <div class="thumbnail">
                <img src="{{ $theme['thumbnail'] }}" title="{{ $theme['name'] }}">
                <div class="caption">
                    <h5>{{ $theme['name'] }}</h5>
                    <p class="version">{{ Lang::line('themes::general.version')->get() }} {{ $theme['version'] }}</p>
                    <p class="author">{{ Lang::line('themes::general.author')->get() }}  {{ $theme['author'] }}</p>
                    <p>{{ $theme['description'] }}</p>
                    @if ($theme['active'])
                    <a href="{{ URL::to_admin('themes/edit/' . $type . '/' . $theme['theme']) }}" class="btn" data-theme="{ $active['theme'] }}" data-type="backend">{{ Lang::line('button.edit')->get() }}</a>
                    @else
                    <a href="{{ URL::to_admin('themes/activate/' . $type . '/' . $theme['theme']) }}" class="btn activate" data-token="{{ Session::token() }}" data-theme="{{ $theme['theme'] }}" data-type="{{ $type }}">{{ Lang::line('button.enable')->get() }}</a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endsection