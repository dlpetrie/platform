@layout('installer::template')

@section('scripts')
<script>
	$(document).ready(function() {

		Validate.setup($("#database-form"));

	});
</script>
@endsection

@section('navigation')
	<h1>Database</h1>
	<p class="step">Let's take some database credentials</p>
	<div class="breadcrumbs">
		<ul class="nav">
			<li><span>{{ Lang::line('installer::installer.general.step_1') }}</span> {{ Lang::line('installer::installer.general.step_1_title') }}</li>
			<li class="active">
				<span>{{ Lang::line('installer::installer.general.step_2') }}</span> {{ Lang::line('installer::installer.general.step_2_title') }}
			</li>
			<li><span>{{ Lang::line('installer::installer.general.step_3') }}</span> {{ Lang::line('installer::installer.general.step_3_title') }}</li>
			<li><span>{{ Lang::line('installer::installer.general.step_4') }}</span> {{ Lang::line('installer::installer.general.step_4_title') }}</li>
		</ul>
	</div>
@endsection


@section('content')
<div class="grid contain">
	<h2>Now its time to create a database, then give us the details and we'll do the rest.</h2>

	<form id="database-form" class="form-horizontal" method="POST" accept-char="UTF-8">
	<input type="hidden" name="{{ Session::csrf_token }}" value="{{ Session::token() }}">

		<fieldset>
			<legend>{{ Lang::line('installer::installer.database.legend') }}</legend>

			<!-- Database Driver Select -->
			<div>
				<label for="driver">Database Driver:</label>
				<select name="driver" id="driver" required>
					<option value="">Database Driver</option>
					@foreach ($drivers as $value => $name)
						<option value="{{ $value }}">{{ $name }}</option>
					@endforeach
				</select>
				<span class="help">Select a driver.</span>
			</div>

			<!-- Database Username -->
			<div>
				<label for="host">Server:</label>
				<input id="host" name="host" type="text" value="{{ $credentials['host'] }}" placeholder="Database Server" required>
				<span class="help">Input your database host, e.g. localhost</span>
			</div>

			<!-- Database Username -->
			<div>
				<label for="username">Username:</label>
				<input id="username" name="username" type="text" value="{{ $credentials['username'] }}" placeholder="User Name" required>
				<span class="help">Input your database user.</span>
			</div>

			<!-- Database Password -->
			<div>
				<label for="password">Password:</label>
				<input id="password" name="password" type="password" placeholder="Password">
				<span class="help">Your database users password</span>
			</div>

			<!-- Database Name -->
			<div>
				<label for="database">Database:</label>
				<input id="database" name="database" type="text" value="{{ $credentials['database'] }}" placeholder="User Name" required>
				<span class="help">Input the name of your database.</span>
			</div>

			<!-- Drop Table Warning -->
			<div>
				<label for="disclaimer">Warning:</label>
				<input type="checkbox" name="disclaimer" value="" required>
				<span class="help">If the database has existing tables that conflict with Platform, they will be dropped during the Platform Installation process. You may want to back up your existing database.</span>
			</div>

			<div class="messages alert"></div>

		</fieldset>

		<div class="actions">
			<a class="btn btn-large" href="{{URL::to('installer/step_1');}}">Back</a>
			<button type="submit" class="btn btn-large" disabled>Continue to Step 3</button>
		</div>
	{{ Form::close() }}
</div>
@endsection
