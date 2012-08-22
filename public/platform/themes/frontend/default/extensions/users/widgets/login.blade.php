<form id="login-form" class="form-horizontal" method="POST" accept-char="UTF-8">
<input type="hidden" name="{{ Session::csrf_token }}" value="{{ Session::token() }}">

	<fieldset>
		<legend>{{ Lang::line('users::form.auth.login.legend') }}</legend>
		<p class="summary">{{ Lang::line('users::form.auth.login.summary') }}</p>
		<hr>

		<!-- Email Address -->
		<div class="control-group">
			<label class="control-label" for="email">{{ lang::line('users::form.auth.login.email') }}:</label>
			<div class="controls">
				<input type="email" name="email" id="email" value="{{ Input::old('email') }}" placeholder="{{ lang::line('users::form.auth.login.email') }}" required>
				<span class="help-block">{{ lang::line('users::form.auth.login.email_help') }}</span>
			</div>
		</div>

		<!-- Password -->
		<div class="control-group">
			<label class="control-label" for="password">{{ lang::line('users::form.auth.login.password') }}:</label>
			<div class="controls">
				<input type="password" name="password" id="password" placeholder="{{ lang::line('users::form.auth.login.password') }}" required>
				<span class="help-block">{{ lang::line('users::form.auth.login.password_help') }}</span>
			</div>
		</div>

	</fieldset>

	<p class="messages" data-wait="{{ Lang::line('users::messages.auth.wait') }}" data-redirecting="{{ Lang::line('users::messages.auth.redirect') }}"></p>

	<hr>

	<div class="form-actions">
		<button class="btn" type="submit">{{ Lang::line('users::form.auth.login.submit') }}</button>
		<p class="sub-actions">
			<a href="{{ URL::to_secure('/reset_password') }}">{{ Lang::line('users::form.auth.login.reset_password') }}</a>
		</p>

	</div>
</form>
