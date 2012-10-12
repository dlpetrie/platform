@foreach ($rows as $row)
    <tr>
        <td>
            <a href="{{ URL::to_admin('localisation/currencies/view/' . $row['slug']) }}">{{ $row['name'] }}</a>
            <span class="pull-right">
                @if ($default_currency != $row['code'])
                <a class="btn btn-mini" href="{{ URL::to_admin('localisation/currencies/default/' . $row['slug']) }}" title="* Make this the default country."><i class="icon-star"></i></a>
                @endif
            </span>
        </td>
        <td>{{ $row['code'] }}</td>
        <td>
            <div class="btn-group">
                <a class="btn btn-mini" href="{{ URL::to_admin('localisation/currencies/view/' . $row['slug']) }}">{{ Lang::line('button.view')->get() }}</a>
                @if ($default_currency != $row['code'])
                <a class="btn btn-mini btn-danger" href="{{ URL::to_admin('localisation/currencies/delete/' . $row['slug']) }}">{{ Lang::line('button.delete')->get() }}</a>
                @endif
            </div>
        </td>
    </tr>
@endforeach
