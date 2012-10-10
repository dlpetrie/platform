@foreach ($rows as $row)
    <tr>
        <td>
            <a href="{{ URL::to_admin('localisation/countries/view/' . $row['slug']) }}">{{ $row['name'] }}</a>
            <span class="pull-right">
                @if ($default_country != $row['iso_code_2'])
                <a class="btn btn-mini" href="{{ URL::to_admin('localisation/countries/default/' . $row['slug']) }}" title="* Make this the default country."><i class="icon-star"></i></a>
                @endif
            </span>
        </td>
        <td>{{ $row['iso_code_2'] }}</td>
        <td>
            <div class="btn-group">
                <a class="btn btn-mini" href="{{ URL::to_admin('localisation/countries/view/' . $row['slug']) }}">{{ Lang::line('button.view')->get() }}</a>
                @if ($default_country != $row['iso_code_2'])
                <a class="btn btn-mini btn-danger" href="{{ URL::to_admin('localisation/countries/delete/' . $row['slug']) }}">{{ Lang::line('button.delete')->get() }}</a>
                @endif
            </div>
        </td>
    </tr>
@endforeach
