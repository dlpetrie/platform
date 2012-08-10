@foreach ($rows as $row)
	<tr>
		<td class="span1">{{ $row['id'] }}</td>
		<td class="span9">{{ $row['name'] }}</td>
		<td class="span2">
			<a class="btn" href="{{ URL::to_secure(ADMIN.'/users/groups/edit/'.$row['id']) }}">{{ Lang::line('buttons.edit') }}</a>
			<a class="btn btn-danger" href="{{ URL::to_secure(ADMIN.'/users/groups/delete/'.$row['id']) }}">{{ Lang::line('buttons.delete') }}</a>
		</td>
	</tr>
@endforeach
