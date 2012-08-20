{{ Form::open(ADMIN.'/users/create', 'POST', array('class' => 'form-horizontal')) }}

	{{ Form::token() }}

	<div class="well">
		<fieldset>
			<label for="first_name">{{ Lang::line('users::form.users.create.first_name') }}</label>
			<input type="text" id="first_name" name="first_name" value="{{ Input::old('first_name'); }}">

			<label for="last_name">{{ Lang::line('users::form.users.create.last_name') }}</label>
			<input type="text" id="last_name" name="last_name" value="{{ Input::old('last_name'); }}">

			<label for="email">{{ Lang::line('users::form.users.create.email') }}</label>
			<input type="text" id="email" name="email" value="{{ Input::old('email'); }}">

			<label for="password">{{ Lang::line('users::form.users.create.password') }}</label>
			<input type="password" id="password" name="password">

			<label for="password_confirmation">{{ Lang::line('users::form.users.create.password_confirm') }}</label>
			<input type="password" id="password_confirmation" name="password_confirmation">

			<label for="groups">{{ Lang::line('users::form.users.create.groups') }}</label>
			{{ Form::select('groups[]', $groups, null, array('multiple' => 'multiple')) }}
		</fieldset>
	</div>
	<button class="btn btn-large btn-primary" type="submit">{{ Lang::line('button.create') }}</button>
	<a class="btn btn-large" href="{{ URL::to_secure(ADMIN.'/users') }}">{{ Lang::line('button.cancel') }}</a>

{{ Form::close() }}
