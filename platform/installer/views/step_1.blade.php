@layout('installer::template')

@section('navigation')
	<h1>{{ Lang::line('installer::general.step_1.title') }}</h1>
	<p class="step">{{ Lang::line('installer::general.step_1.description') }}</p>
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
	<h2>{{ Lang::line('installer::form.prepare.description') }}</h2>
	<form id="prepare-form" class="form-horizontal" method="POST" accept-char="UTF-8">
	<input type="hidden" name="{{ Session::csrf_token }}" value="{{ Session::token() }}">

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
			<a class="btn btn-large step1-refresh">{{ Lang::line('installer::button.refresh') }}</a>
			<button type="submit" class="btn btn-large" id="continue-btn" {{ (count($permissions['fail']) > 0) ? 'disabled' : null }}>
				{{ Lang::line('installer::button.next') }}
			</button>
		</div>
	</form>
</div>
@endsection
