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

	{{ Form::open(null, 'POST', array('id' => 'database-form', 'class' => 'form-horizontal')) }}
	{{ Form::token() }}
		<fieldset>
			<legend>{{ Lang::line('installer::installer.database.legend') }}</legend>

			<!-- Database Driver Select -->
			<div>
				{{ Form::label('driver', 'Database Driver:') }}
				{{ Form::select('driver', array(null => 'Database Driver') + $drivers, $credentials['driver'], array('required')) }}
				<span class="help">Select a driver.</span>
			</div>

			<!-- Database Username -->
			<div>
				{{ Form::label('host', 'Server:') }}
				{{ Form::text('host', $credentials['host'], array('placeholder' => 'Database Server', 'required')) }}
				<span class="help">Input your database host, e.g. localhost</span>
			</div>

			<!-- Database Username -->
			<div>
				{{ Form::label('username', 'Username:') }}
				{{ Form::text('username', $credentials['username'], array('placeholder' => 'User Name', 'required')) }}
				<span class="help">Input your database user.</span>
			</div>

			<!-- Database Password -->
			<div>
				{{ Form::label('password', 'Password:') }}
				{{ Form::password('password', array('placeholder' => 'Password')) }}
				<span class="help"></span>
			</div>

			<!-- Database Name -->
			<div>
				{{ Form::label('database', 'Database:') }}
				{{ Form::text('database', $credentials['database'], array('placeholder' => 'Database Name', 'required')) }}
				<span class="help">Input the name of your database.</span>
			</div>

			<!-- Drop Table Warning -->
			<div>
				{{ Form::label('disclaimer', 'Warning:') }}
				{{ Form::checkbox('disclaimer', '', false, array('id' => 'disclaimer', 'required')) }}
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
