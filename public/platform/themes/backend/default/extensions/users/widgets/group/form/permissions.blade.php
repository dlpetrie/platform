<form action="{{ URL::to_secure(ADMIN.'/users/groups/permissions/'.$id) }}" id="permissions-form" class="form-horizontal" method="POST" accept-char="UTF-8">
	<input type="hidden" name="{{ Session::csrf_token }}" value="{{ Session::token() }}">


	@foreach ($extension_rules as $category)
		<fieldset>
			<legend>{{ $category['title'] }}</legend>
			@foreach($category['permissions'] as $permission)

				<div>
					<input type="checkbox" id="{{ $permission['slug'] }}" name="{{ $permission['slug'] }}" {{ ($permission['has']) ? 'checked="checked"' : '' }}>
					{{ $permission['value'] }}
				</div>

			@endforeach
		</fieldset>
		<hr>
	@endforeach

	<p class="messages"></p>

	<hr>

	<div class="actions">
		<a class="btn btn-large" href="{{ URL::to_secure(ADMIN.'/users/groups') }}">{{ Lang::line('button.cancel') }}</a>
		<button class="btn btn-large btn-primary" type="submit">{{ Lang::line('button.update') }}</button>
	</div>


</form>
