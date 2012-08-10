{{ Form::open() }}

	{{ Form::token() }}

	<fieldset>
		<div>
			<label for="name">{{ Lang::line('users::groups.general.name') }}</label>
			<input type="text" id="name" name="name" value="{{ Input::old('name'); }}">

		</div>
		<div>
			<button class="btn btn-large btn-primary" type="submit">{{ Lang::line('buttons.create') }}</button>
			<a class="btn btn-large" href="{{ URL::to_secure(ADMIN.'/users/groups') }}">{{ Lang::line('buttons.cancel') }}</a>
		</div>
	</fieldset>
{{ Form::close() }}
