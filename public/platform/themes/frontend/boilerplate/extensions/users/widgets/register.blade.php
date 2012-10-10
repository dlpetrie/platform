
<form action="{{ URL::to_secure('/register') }}" id="register-form" class="form-horizontal" method="POST" accept-char="UTF-8">
<input type="hidden" name="{{ Session::csrf_token }}" value="{{ Session::token() }}">
	<fieldset>
		<legend>{{ Lang::line('users::form.auth.create.legend') }}</legend>
		<p class="summary">{{ Lang::line('users::form.auth.create.summary') }}</p>
		<hr>
		<!-- First Name -->
		<div class="control-group">
			<label class="control-label" for="first_name">{{ lang::line('users::form.auth.create.first_name') }}:</label>
			<div class="controls">
				<div class="input-append">
					<input type="text" name="first_name" id="first_name" value="{{ Input::old('first_name') }}" placeholder="{{ lang::line('users::form.auth.create.first_name') }}" required>
					<span class="add-on"><i class="icon-user"></i></span>
				</div>
				<span class="help-block">{{ lang::line('users::form.auth.create.first_name_help') }}</span>
			</div>
		</div>

		<!-- Last Name -->
		<div class="control-group">
			<label class="control-label" for="last_name">{{ lang::line('users::form.auth.create.last_name') }}:</label>
			<div class="controls">
				<div class="input-append">
					<input type="text" name="last_name" id="last_name" value="{{ Input::old('last_name') }}" placeholder="{{ lang::line('users::form.auth.create.last_name') }}" required>
					<span class="add-on"><i class="icon-user"></i></span>
				</div>
				<span class="help-block">{{ lang::line('users::form.auth.create.last_name_help') }}</span>
			</div>
		</div>

		<!-- Email Address -->
		<div class="control-group">
			<label class="control-label" for="email">{{ lang::line('users::form.auth.create.email') }}:</label>
			<div class="controls">
				<div class="input-append">
					<input type="email" name="email" id="email" value="{{ Input::old('email') }}" placeholder="{{ lang::line('users::form.auth.create.email') }}" required>
					<span class="add-on"><i class="icon-envelope"></i></span>
				</div>
				<span class="help-block">{{ lang::line('users::form.auth.create.email_help') }}</span>
			</div>
		</div>

		<!-- Email Confirm -->
		<div class="control-group">
			<label class="control-label" for="email_confirmation">{{ lang::line('users::form.auth.create.email_confirm') }}:</label>
			<div class="controls">
				<div class="input-append">
					<input type="email" name="email_confirmation" id="email_confirmation" value="" placeholder="{{ lang::line('users::form.auth.create.email_confirm') }}" required>
					<span class="add-on"><i class="icon-envelope"></i></span>
				</div>
				<span class="help-block">{{ lang::line('users::form.auth.create.email_confirm_help') }}</span>
			</div>
		</div>

		<!-- Password -->
		<div class="control-group">
			<label class="control-label" for="password">{{ lang::line('users::form.auth.create.password') }}:</label>
			<div class="controls">
				<div class="input-append">
					<input type="password" name="password" id="password" placeholder="{{ lang::line('users::form.auth.create.password') }}" required>
					<span class="add-on"><i class="icon-key"></i></span>
				</div>
				<span class="help-block">Type your password.</span>
			</div>
		</div>

		<!-- Password Confirm -->
		<div class="control-group">
			<label class="control-label" for="password_confirmation">{{ lang::line('users::form.auth.create.password_confirm') }}:</label>
			<div class="controls">
				<div class="input-append">
					<input type="password" name="password_confirmation" id="" placeholder="{{ lang::line('users::form.auth.create.password_confirm') }}" required>
					<span class="add-on"><i class="icon-key"></i></span>
				</div>
				<span class="help-block">{{ lang::line('users::form.auth.create.password_confirm_help') }}</span>
			</div>
		</div>
	</fieldset>

	<p class="messages"></p>

	<div class="form-actions">
		<a class="btn" href="{{ URL::to_secure('/') }}">{{ Lang::line('users::form.auth.create.cancel') }}</a>
		<button class="btn btn-primary" type="submit">{{ Lang::line('users::form.auth.create.submit') }}</button>
	</div>
</form>
