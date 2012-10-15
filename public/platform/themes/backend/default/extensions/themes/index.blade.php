@layout('templates.default')

<!-- Page Title -->
@section('title')
	{{ Lang::line('themes::general.title') }}
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
			<h1>{{ Lang::line('themes::general.title') }}</h1>
			<p>{{ Lang::line('themes::general.description') }}</p>
		</div>
		<nav class="actions span6 pull-right"></nav>
	</header>

	<hr>

	<div class="selections row-fluid">
		@if($active)
			<div class="active span3">
				<div class="thumbnail">
					<img src="{{ Theme\Asset::url(str_finish(Theme::directory(), DS).$type.DS.$active['theme'].DS.'assets'.DS.'img'.DS.'theme-thumbnail.png') }}" title="{{ $active['theme'] }}">
					<div class="caption">
						<h5>{{ $active['name'] }}</h5>
						<p class="version">{{ Lang::line('themes::general.version') }} {{ $active['version'] }}</p>
						<p class="author">{{ Lang::line('themes::general.author') }}  {{ $active['author'] }}</p>
						<p>{{ $active['description'] }}</p>
						<a href="{{ URL::to_secure(ADMIN.'/themes/edit/'.$type.'/'.$active['theme']) }}" class="btn" data-theme="{{ $active['theme'] }}" data-type="backend">{{ Lang::line('button.edit') }}</a>
					</div>
				</div>
			</div>
		@else
			<div class="active span3">
				<div class="thumbnail">
					<div class="caption">
						<h5>Select a Theme and activate</h5>
					</div>
				</div>
			</div>
		@endif

		@foreach ($inactive as $theme)
			<div class="span3">
				<div class="thumbnail inactive">
					<img src="{{ Theme\Asset::url(str_finish(Theme::directory(), DS).$type.DS.$theme['theme'].DS.'assets'.DS.'img'.DS.'theme-thumbnail.png') }}" title="{{ $theme['theme'] }}">
					<div class="caption">
						<h5>{{ $theme['name'] }}</h5>
						<p>{{ $theme['description'] }}</p>
						<p>{{ Lang::line('themes::general.version') }} {{ $theme['version'] }}</p>
						<p>{{ Lang::line('themes::general.author') }}  {{ $theme['author'] }}</p>
						<a href="{{ URL::to_secure(ADMIN.'/themes/activate/'.$type.'/'.$theme['theme']) }}" class="btn activate" data-token="{{ Session::token() }}" data-theme="{{ $theme['theme'] }}" data-type="{{ URI::segment(3) }}">{{ Lang::line('button.enable') }}</a>
					</div>
				</div>
			</div>
		@endforeach
	</div>
</section>
@endsection
