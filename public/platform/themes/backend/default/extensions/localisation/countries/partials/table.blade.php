@foreach ($rows as $row)
	<tr>
		<td><a href="{{ URL::to_admin('localisation/countries/view/' . $row['slug']) }}">{{ $row['name'] }}</a></td>
		<td>{{ $row['iso_code_2'] }}</td>
		<td>
			<div class="btn-group">
			<a class="btn btn-mini" href="{{ URL::to_admin('localisation/countries/view/' . $row['slug']) }}">{{ Lang::line('button.view') }}</a>
			<a class="btn btn-mini btn-danger" href="{{ URL::to_admin('localisation/countries/delete/' . $row['slug']) }}">{{ Lang::line('button.delete') }}</a>
			</div>
		</td>
	</tr>
@endforeach
