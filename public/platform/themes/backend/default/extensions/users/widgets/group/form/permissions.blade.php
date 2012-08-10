{{ Form::open(ADMIN.'/users/groups/permissions/'.$id) }}

	{{ Form::token() }}

	<div class="well">
		@foreach ($extension_rules as $category)
			<fieldset>
				<legend>{{ $category['title'] }}</legend>
				@foreach($category['permissions'] as $permission)
					<div>
						<label class="checkbox">
							<input type="checkbox" id="{{ $permission['slug'] }}" name="{{ $permission['slug'] }}" {{ ($permission['has']) ? 'checked="checked"' : '' }}>
							{{ $permission['value'] }}
						</label>
					</div>
				@endforeach
			</fieldset>
		@endforeach
	</div>

	<button class="btn btn-large btn-primary" type="submit">{{ Lang::line('button.update') }}</button>
	<a class="btn btn-large" href="{{ URL::to_secure(ADMIN.'/users/groups') }}">{{ Lang::line('button.cancel') }}</a>
{{ Form::close() }}
