<form action="{{ URL::to_secure(ADMIN.'/settings/general') }}" id="general-form" class="form-horizontal" method="POST" accept-char="UTF-8">
	<input type="hidden" name="{{ Session::csrf_token }}" value="{{ Session::token() }}">

	<fieldset>

		<div>
			<label for="theme-frontend">Frontend Theme</label>
			<input type="text" id="frontend" name="theme:frontend" value="{{ array_get($settings, 'theme.frontend') }}">
			<span class="help"></span>
		</div>

		<div>
			<label for="theme-backend">Backend Theme</label>
			<input type="text" id="backend" name="theme:backend" value="{{ array_get($settings, 'theme.backend') }}">
			<span class="help"></span>
		</div>

	</fieldset>

	<p class="messages"></p>
	<hr>

	<div class="actions">
		<button class="btn btn-large btn-primary" type="submit">{{ Lang::line('button.update') }}</button>
	</div>
	
</form>