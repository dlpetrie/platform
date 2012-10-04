@layout('templates.default')

@section('title')
* Localisation | Countries
@endsection

@section('content')
	<header class="row-fluid">
		<div class="span6">
			<h1>* Countries</h1>
			<p>* Deleting the country {{ $country['name'] }}</p>
		</div>
		<nav class="actions span4 pull-right"></nav>
	</header>
@endsection