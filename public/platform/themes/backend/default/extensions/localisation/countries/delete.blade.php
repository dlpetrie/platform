@layout('templates.default')

@section('title')
	{{ Lang::line('localisation::countries/general.title')->get() }}
@endsection

@section('content')
<section id="countries">
	<header class="head row-fluid">
		<div class="span6">
			<h1>{{ Lang::line('localisation::countries/general.title') }}</h1>
			<p>{{ Lang::line('localisation::countries/general.description.delete', array('country' => $country['name'])) }}</p>
		</div>
		<nav class="tertiary-navigation span6">
			@widget('platform.menus::menus.nav', 2, 1, 'nav nav-pills pull-right', ADMIN)
		</nav>
	</header>



</section>
@endsection