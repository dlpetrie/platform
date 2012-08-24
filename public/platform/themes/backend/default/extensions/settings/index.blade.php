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

	<header class="row">
			<div class="span4">
				<h1>{{ Lang::line('settings::general.title') }}</h1>
				<p>{{ Lang::line('settings::general.description') }}</p>
			</div>
			<nav class="actions span8 pull-right">
			</nav>
	</header>

	<hr>

	<div class="tabbable">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#general" data-toggle="tab">General</a></li>
			<li><a href="#extra" data-toggle="tab">Extra</a></li>
		</ul>
		<div class="tab-content">
		    <div class="tab-pane active" id="general">
		    	@widget('platform.settings::settings.general')
		    </div>
		    <div class="tab-pane" id="extra">
		    	Add extra settings here
		    </div>
	  	</div>
	</div>

</section>

@endsection
