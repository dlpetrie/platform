@layout('templates.default')

<!-- Page Title -->
@section('title')
	{{ Lang::line('settings::general.title') }}
@endsection

<!-- Queue Styles | e.g Theme::queue_asset('name', 'path_to_css', 'dependency')-->

<!-- Styles -->
@section ('styles')
@endsection

<!-- Queue Scripts -->
{{ Theme::queue_asset('bootstrap-tab', 'js/bootstrap/tab.js', 'jquery') }}

<!-- Scripts -->
@section('scripts')
@endsection

<!-- Page Content -->
@section('content')
<section id="settings">

	<header class="row-fluid">
			<div class="span4">
				<h1>{{ Lang::line('settings::general.title') }}</h1>
				<p>{{ Lang::line('settings::general.description') }}</p>
			</div>
			<nav class="actions span8 pull-right">
			</nav>
	</header>

	<hr />

	<div class="tabbable">
		<ul class="nav nav-tabs">
			@foreach ( $settings as $extension => $data )
			<li{{ ( $extension === 'settings' ? ' class="active"' : '' ) }}><a href="#x{{ $extension }}" data-toggle="tab">{{ $extension }}</a></li>
			@endforeach
		</ul>
		<div class="tab-content">
			@foreach ( $settings as $extension => $data )
		    <div class="tab-pane{{ ( $extension === 'settings' ? ' active' : '' ) }}" id="x{{ $extension }}">
		    	@widget('platform.' . $extension . '::settings.index', $data)
		    </div>
		    @endforeach
	  	</div>
	</div>
</section>

@endsection