<form action="{{ URL::to_secure(ADMIN.'/users/groups/edit/'.$group['id']) }}" id="edit-form" class="form-horizontal" method="POST" accept-char="UTF-8">
	<input type="hidden" name="{{ Session::csrf_token }}" value="{{ Session::token() }}">

	<fieldset>

		<!-- Group Name -->
		<div class="control-group">
			<label class="control-label" for="name">{{ Lang::line('users::form.groups.edit.name') }}</label>
			<div class="controls">
				<input type="text" id="name" name="name" value="{{ Input::old('name', $group['name']); }}" required>
				<span class="help-block">{{ lang::line('users::form.groups.edit.name_help') }}</span>
			</div>
		</div>

	</fieldset>

	<p class="messages"></p>
	<hr>

	<div class="form-actions">
		<a class="btn btn-large" href="{{ URL::to_secure(ADMIN.'/users/groups') }}">{{ Lang::line('button.cancel') }}</a>
		<button class="btn btn-large btn-primary" type="submit">{{ Lang::line('button.update') }}</button>
	</div>
</form>
