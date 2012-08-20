
<form action="{{ URL::to_secure('/register') }}" id="register-form" class="form-horizontal" method="POST" accept-char="UTF-8">
<input type="hidden" name="{{ Session::csrf_token }}" value="{{ Session::token() }}">
	<fieldset>
		<legend>{{ Lang::line('users::form.auth.create.legend') }}</legend>
		<p class="summary">{{ Lang::line('users::form.auth.create.summary') }}</p>
		<hr>
		<!-- First Name -->
		<div>
			<label for="first_name">{{ lang::line('users::form.auth.create.first_name') }}:</label>
			<input type="text" name="first_name" id="first_name" value="{{ Input::old('first_name') }}" placeholder="{{ lang::line('users::form.auth.create.first_name') }}" required>
			<span class="help">{{ lang::line('users::form.auth.create.first_name_help') }}</span>
		</div>

		<!-- Last Name -->
		<div>
			<label for="last_name">{{ lang::line('users::form.auth.create.last_name') }}:</label>
			<input type="text" name="last_name" id="last_name" value="{{ Input::old('last_name') }}" placeholder="{{ lang::line('users::form.auth.create.last_name') }}" required>
			<span class="help">{{ lang::line('users::form.auth.create.last_name_help') }}</span>
		</div>

		<!-- Email Address -->
		<div>
			<label for="email">{{ lang::line('users::form.auth.create.email') }}:</label>
			<input type="email" name="email" id="email" value="{{ Input::old('email') }}" placeholder="{{ lang::line('users::form.auth.create.email') }}" required>
			<span class="help">{{ lang::line('users::form.auth.create.email_help') }}</span>
		</div>

		<!-- Email Confirm -->
		<div>
			<label for="email_confirmation">{{ lang::line('users::form.auth.create.email_confirm') }}:</label>
			<input type="email" name="email_confirmation" id="email_confirmation" value="" placeholder="{{ lang::line('users::form.auth.create.email_confirm') }}" required>
			<span class="help">{{ lang::line('users::form.auth.create.email_confirm_help') }}</span>
		</div>

		<!-- Password -->
		<div>
			<label for="password">{{ lang::line('users::form.auth.create.password') }}:</label>
			<input type="password" name="password" id="password" placeholder="{{ lang::line('users::form.auth.create.password') }}" required>
			<span class="help">Type your password.</span>
		</div>

		<!-- Password Confirm -->
		<div>
			<label for="password_confirmation">{{ lang::line('users::form.auth.create.password_confirm') }}:</label>
			<input type="password" name="password_confirmation" id="" placeholder="{{ lang::line('users::form.auth.create.password_confirm') }}" required>
			<span class="help">{{ lang::line('users::form.auth.create.password_confirm_help') }}</span>
		</div>
	</fieldset>

	<p class="messages"></p>
	<hr>

	<div class="actions">
		<button class="btn" type="submit">{{ Lang::line('users::form.auth.create.submit') }}</button>
		<p class="sub-actions">
			<a href="{{ URL::to_secure('/') }}">{{ Lang::line('users::form.auth.create.cancel') }}</a>
		</p>
	</div>
{{ Form::close() }}
