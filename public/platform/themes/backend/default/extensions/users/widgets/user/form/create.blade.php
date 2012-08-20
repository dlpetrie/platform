<form action="{{ URL::to_secure(ADMIN.'/users/create') }}" id="create-form" class="form-horizontal" method="POST" accept-char="UTF-8">
<input type="hidden" name="{{ Session::csrf_token }}" value="{{ Session::token() }}">
	<fieldset>
		<!-- First Name -->
		<div>
			<label for="first_name">{{ lang::line('users::form.users.create.first_name') }}:</label>
			<input type="text" name="first_name" id="first_name" value="{{ Input::old('first_name') }}" placeholder="{{ lang::line('users::form.users.create.first_name') }}" required>
			<span class="help">{{ lang::line('users::form.users.create.first_name_help') }}</span>
		</div>

		<!-- Last Name -->
		<div>
			<label for="last_name">{{ lang::line('users::form.users.create.last_name') }}:</label>
			<input type="text" name="last_name" id="last_name" value="{{ Input::old('last_name') }}" placeholder="{{ lang::line('users::form.users.create.last_name') }}" required>
			<span class="help">{{ lang::line('users::form.users.create.last_name_help') }}</span>
		</div>

		<!-- Email Address -->
		<div>
			<label for="email">{{ lang::line('users::form.users.create.email') }}:</label>
			<input type="email" name="email" id="email" value="{{ Input::old('email') }}" placeholder="{{ lang::line('users::form.users.create.email') }}" required>
			<span class="help">{{ lang::line('users::form.users.create.email_help') }}</span>
		</div>

		<!-- Password -->
		<div>
			<label for="password">{{ lang::line('users::form.users.create.password') }}:</label>
			<input type="password" name="password" id="password" placeholder="{{ lang::line('users::form.users.create.password') }}" required>
			<span class="help">Type your password.</span>
		</div>

		<!-- Password Confirm -->
		<div>
			<label for="password_confirmation">{{ lang::line('users::form.users.create.password_confirm') }}:</label>
			<input type="password" name="password_confirmation" id="" placeholder="{{ lang::line('users::form.users.create.password_confirm') }}" required>
			<span class="help">{{ lang::line('users::form.users.create.password_confirm_help') }}</span>
		</div>

		<!-- Groups -->
		<div>
			<label for="groups">{{ Lang::line('users::form.users.create.groups') }}</label>
			<select name="driver" id="driver" multiple required>
				@foreach ($groups as $value => $name)
					<option value="{{ $value }}">{{ $name }}</option>
				@endforeach
			</select>
			<span class="help">{{ Lang::line('users::form.users.create.groups_help') }}</span>
		</div>

	</fieldset>

	<p class="messages"></p>
	<hr>

	<div class="actions">
		<a class="btn btn-large" href="{{ URL::to_secure(ADMIN.'/users') }}">{{ Lang::line('button.cancel') }}</a>
		<button class="btn btn-large btn-primary" type="submit">{{ Lang::line('button.create') }}</button>
	</div>
</form>
