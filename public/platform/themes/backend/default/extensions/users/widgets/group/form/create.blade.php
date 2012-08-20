<form id="create-form" class="form-horizontal" method="POST" accept-char="UTF-8">
	<input type="hidden" name="{{ Session::csrf_token }}" value="{{ Session::token() }}">

	<fieldset>

		<!-- Group Name -->
		<div>
			<label for="name">{{ Lang::line('users::form.groups.create.name') }}</label>
			<input type="text" id="name" name="name" value="{{ Input::old('name'); }}" required>
			<span class="help">{{ lang::line('users::form.groups.create.name_help') }}</span>
		</div>

	</fieldset>

	<p class="messages"></p>
	<hr>

	<div class="actions">
		<a class="btn btn-large" href="{{ URL::to_secure(ADMIN.'/users/groups') }}">{{ Lang::line('button.cancel') }}</a>
		<button class="btn btn-large btn-primary" type="submit">{{ Lang::line('button.create') }}</button>
	</div>
</form
