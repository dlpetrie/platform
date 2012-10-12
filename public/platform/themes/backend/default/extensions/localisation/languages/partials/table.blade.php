@foreach ($rows as $row)
    <tr>
        <td>
            <a href="{{ URL::to_admin('localisation/languages/view/' . $row['slug']) }}">{{ $row['name'] }}</a>
            <span class="pull-right">
                @if ($default_language != $row['abbreviation'])
                <a class="btn btn-mini" href="{{ URL::to_admin('localisation/languages/default/' . $row['slug']) }}" title="* Make this the default country."><i class="icon-star"></i></a>
                @endif
            </span>
        </td>
        <td>{{ $row['abbreviation'] }}</td>
        <td>
            <div class="btn-group">
                <a class="btn btn-mini" href="{{ URL::to_admin('localisation/languages/view/' . $row['slug']) }}">{{ Lang::line('button.view')->get() }}</a>
                @if ($default_language != $row['abbreviation'])
                <a class="btn btn-mini btn-danger" href="{{ URL::to_admin('localisation/languages/delete/' . $row['slug']) }}">{{ Lang::line('button.delete')->get() }}</a>
                @endif
            </div>
        </td>
    </tr>
@endforeach
