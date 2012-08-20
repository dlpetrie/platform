@layout('installer::template')

@section('scripts')
<script>
	$(document).ready(function() {

		Validate.setup($("#database-form"));

	});
</script>
@endsection

@section('navigation')
	<h1>{{ Lang::line('installer::general.step_2.title') }}</h1>
	<p class="step">{{ Lang::line('installer::general.step_2.tagline') }}</p>
	<div class="breadcrumbs">
		<ul class="nav">
			<ul class="nav">
			<li><span>{{ Lang::line('installer::general.step_1.step') }}</span> {{ Lang::line('installer::general.step_1.step_description') }}</li>
			<li class="active"><span>{{ Lang::line('installer::general.step_2.step') }}</span> {{ Lang::line('installer::general.step_2.step_description') }}</li>
			<li><span>{{ Lang::line('installer::general.step_3.step') }}</span> {{ Lang::line('installer::general.step_3.step_description') }}</li>
			<li><span>{{ Lang::line('installer::general.step_4.step') }}</span> {{ Lang::line('installer::general.step_4.step_description') }}</li>
		</ul>
	</div>
@endsection


@section('content')
<div class="contain">
	<h2>{{ Lang::line('installer::general.step_2.description') }}</h2>
	<hr>
	<form id="database-form" class="form-horizontal" method="POST" accept-char="UTF-8">
	<input type="hidden" name="{{ Session::csrf_token }}" value="{{ Session::token() }}">
		<fieldset>
			<legend>{{ Lang::line('installer::form.database.legend') }}</legend>
			<!-- Database Driver Select -->
			<div>
				<label for="driver">{{ Lang::line('installer::form.database.driver') }}</label>
				<select name="driver" id="driver" required>
					<option value="">{{ Lang::line('installer::form.database.driver') }}</option>
					@foreach ($drivers as $value => $name)
						<option value="{{ $value }}">{{ $name }}</option>
					@endforeach
				</select>
				<span class="help">{{ Lang::line('installer::form.database.driver_help') }}</span>
			</div>

			<!-- Database Username -->
			<div>
				<label for="host">{{ Lang::line('installer::form.database.server') }}</label>
				<input type="text" name="host" id="host" value="{{ $credentials['host'] }}" placeholder="{{ Lang::line('installer::form.database.server') }}" required>
				<span class="help">{{ Lang::line('installer::form.database.server_help') }}</span>
			</div>

			<!-- Database Username -->
			<div>
				<label for="username">{{ Lang::line('installer::form.database.username') }}</label>
				<input type="text" name="username" id="username" value="{{ $credentials['username'] }}" placeholder="{{ Lang::line('installer::form.database.username') }}" required>
				<span class="help">{{ Lang::line('installer::form.database.username_help') }}</span>
			</div>

			<!-- Database Password -->
			<div>
				<label for="password">{{ Lang::line('installer::form.database.password') }}</label>
				<input type="password" name="password" id="password" placeholder="{{ Lang::line('installer::form.database.password') }}">
				<span class="help">{{ Lang::line('installer::form.database.password_help') }}</span>
			</div>

			<!-- Database Name -->
			<div>
				<label for="database">{{ Lang::line('installer::form.database.database') }}</label>
				<input type="text" name="database" id="database" value="{{ $credentials['database'] }}" placeholder="{{ Lang::line('installer::form.database.database') }}" required>
				<span class="help">{{ Lang::line('installer::form.database.database_help') }}</span>
			</div>

			<!-- Drop Table Warning -->
			<div>
				<label for="disclaimer">{{ Lang::line('installer::form.database.disclaimer') }}</label>
				<input type="checkbox" name="disclaimer" value="" required>
				<span class="help">{{ Lang::line('installer::form.database.disclaimer_help') }}</span>
			</div>

			<p class="messages alert"></p>

		</fieldset>

		<div class="actions">
			<a class="btn btn-large" href="{{URL::to('installer/step_1');}}">{{ Lang::line('installer::button.previous') }}</a>
			<button type="submit" class="btn btn-large" disabled>{{ Lang::line('installer::button.next') }}</button>
		</div>
	</form>
</div>
@endsection
