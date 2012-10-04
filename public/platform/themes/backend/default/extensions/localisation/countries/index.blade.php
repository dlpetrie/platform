@layout('templates.default')

@section('title')
* Localisation | {{ Lang::line('localisation::countries/general.title')->get() }}
@endsection

@section('content')
	<header class="row-fluid">
		<div class="span6">
			<h1>{{ Lang::line('localisation::countries/general.title')->get() }}</h1>
			<p>{{ Lang::line('localisation::countries/general.description')->get() }}</p>
		</div>
		<nav class="actions span4 pull-right"></nav>
	</header>
@endsection