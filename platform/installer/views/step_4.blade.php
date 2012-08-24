@layout('installer::template')

@section('navigation')
	<h1>{{ Lang::line('installer::general.step_4.title') }}</h1>
	<p class="step">{{ Lang::line('installer::general.step_4.tagline') }}</p>
	<div class="breadcrumbs">
		<ul class="nav">
			<ul class="nav">
			<li><span>{{ Lang::line('installer::general.step_1.step') }}</span> {{ Lang::line('installer::general.step_1.step_description') }}</li>
			<li><span>{{ Lang::line('installer::general.step_2.step') }}</span> {{ Lang::line('installer::general.step_2.step_description') }}</li>
			<li><span>{{ Lang::line('installer::general.step_3.step') }}</span> {{ Lang::line('installer::general.step_3.step_description') }}</li>
			<li class="active"><span>{{ Lang::line('installer::general.step_4.step') }}</span> {{ Lang::line('installer::general.step_4.step_description') }}</li>
		</ul>
	</div>
@endsection

@section('content')
<div class="contain">

	<h2>{{ Lang::line('installer::general.step_4.description') }}</h2>
	<p class="lead">{{ Lang::line('installer::general.step_4.licence') }}</p>
	<hr>
	<div class="well">
		<pre style="word-break: break-word;">{{ $license }}</pre>
	</div>

	<div class="actions">
		<p>
			<a href="{{ URL::base() }}" class="btn btn-large">I Agree, Continue to the Home Page</a>
		</p>
	</div>

</div>
@endsection
