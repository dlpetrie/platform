@layout('installer::template')

@section('navigation')
	<h1>{{ Lang::line('installer::general.step_1.title') }}</h1>
	<p class="step">{{ Lang::line('installer::general.step_1.tagline') }}</p>
	<div class="breadcrumbs">
		<ul class="nav">
			<ul class="nav">
			<li class="active"><span>{{ Lang::line('installer::general.step_1.step') }}</span> {{ Lang::line('installer::general.step_1.step_description') }}</li>
			<li><span>{{ Lang::line('installer::general.step_2.step') }}</span> {{ Lang::line('installer::general.step_2.step_description') }}</li>
			<li><span>{{ Lang::line('installer::general.step_3.step') }}</span> {{ Lang::line('installer::general.step_3.step_description') }}</li>
			<li><span>{{ Lang::line('installer::general.step_4.step') }}</span> {{ Lang::line('installer::general.step_4.step_description') }}</li>
		</ul>
	</div>
@endsection

@section('content')
<div class="contain">
	<h2>{{ Lang::line('installer::general.step_1.description') }}</h2>
	<div class="permissions" id="permissions-pass">
		<div data-template>
			<code class="alert alert-success">[[.]]</code>
		</div>
	</div>
	<div class="permissions" id="permissions-fail">
		<div data-template>
			<code class="alert alert-error">[[.]]</code>
		</div>
	</div>

	<div class="actions">
		<a href="{{ URL::to('installer/step_2') }}" class="btn btn-large {{ (count($permissions['fail']) > 0) ? 'disabled' : null }}" id="continue-btn">
			{{ Lang::line('installer::button.next') }}
		</a>
	</div>
</div>
@endsection
