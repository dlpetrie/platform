<form action="{{ URL::to_secure(ADMIN.'/users/groups/edit/'.$group['id']) }}" id="edit-form" class="form-horizontal" method="POST" accept-char="UTF-8">
	<input type="hidden" name="{{ Session::csrf_token }}" value="{{ Session::token() }}">

	<div class="well">
		<fieldset>

			<label for="name">{{ Lang::line('users::form.groups.edit.name') }}</label>
			<input type="text" id="name" name="name" value="{{ Input::old('name', $group['name']); }}" required>

		</fieldset>
	</div>

	<button class="btn btn-large btn-primary" type="submit">{{ Lang::line('button.update') }}</button>
	<a class="btn btn-large" href="{{ URL::to_secure(ADMIN.'/users/groups') }}">{{ Lang::line('button.cancel') }}</a>
{{ Form::close() }}
