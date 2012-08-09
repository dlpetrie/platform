@foreach ($installed as $extension)
	<tr>
		<td>{{ array_get($extension, 'info.name') }}</td>
		<td>{{ array_get($extension, 'info.slug') }}</td>
		<td>{{ array_get($extension, 'info.author') }}</td>
		<td>{{ array_get($extension, 'info.description') }}</td>
		<td>{{ array_get($extension, 'info.version') }}</td>
		<td>
			{{ array_get($extension, 'info.is_core') ? Lang::line('extensions::extensions.bool.yes') : Lang::line('extensions::extensions.bool.no') }}
		</td>
		<td>
			{{ array_get($extension, 'info.enabled') ? Lang::line('extensions::extensions.bool.yes') : Lang::line('extensions::extensions.bool.no') }}
		</td>
		<td>
			{{ array_get($extension, 'has_update') ? Lang::line('extensions::extensions.bool.yes') : Lang::line('extensions::extensions.bool.no') }}
		</td>
		<td>
			@if ( ! array_get($extension, 'info.is_core'))

				@if (array_get($extension, 'info.enabled'))
					<a class="btn" href="{{ URL::to_secure(ADMIN.'/extensions/disable/'.array_get($extension, 'info.id')) }}" onclick="return confirm('Are you sure you want to disable the \'{{ e(array_get($extension, 'info.name')) }}\' extension? All of its data will stay safe in your database, however it won\'t be available to use while disabled.');">disable</a>
				@else
					<a class="btn" href="{{ URL::to_secure(ADMIN.'/extensions/enable/'.array_get($extension, 'info.id')) }}" onclick="return confirm('Are you sure you want to enable the \'{{ e(array_get($extension, 'info.name')) }}\' extension?');">enable</a>
				@endif

				@if (array_get($extension, 'info.update'))
					<a class="btn" href="{{ URL::to_secure(ADMIN.'/extensions/update/'.array_get($extension, 'info.id')) }}" onclick="return confirm('Are you sure you want to update the \'{{ e(array_get($extension, 'info.name')) }}\' extension?');">update</a>
				@endif

				| <a class="btn btn-danger" href="{{ URL::to_secure(ADMIN.'/extensions/uninstall/'.array_get($extension, 'info.id')) }}" onclick="return confirm('Are you sure you want to uninstall the \'{{ e(array_get($extension, 'info.name')) }}\' extension? All traces, including database info will be removed permanently. There is no undo action for this.');">uninstall</a>

				

			@else
				Required
			@endif
		</td>
	</tr>
@endforeach
