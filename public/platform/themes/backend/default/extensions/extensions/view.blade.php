@layout('templates.default')

@section('title')
    {{ Lang::line('extensions::general.title')->get() }}
@endsection

@section('content')
<section id="extensions">
    <header class="row-fluid">
        <div class="span6">
            <h1>{{ Lang::line('extensions::general.title')->get() }}</h1>
            <p>{{ Lang::line('extensions::general.description.view', array('extension' => array_get($extension, 'info.name')))->get() }}</p>
        </div>
        <nav class="actions span6 pull-right"></nav>
    </header>

    <hr />

    <h5>{{ Lang::line('extensions::general.heading.view.information')->get() }}</h5>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td width="15%">{{ Lang::line('extensions::table.name')->get() }}</td>
                <td>{{ array_get($extension, 'info.name') }}</td>
            </tr>
            <tr>
                <td>{{ Lang::line('extensions::table.slug')->get() }}</td>
                <td>{{ array_get($extension, 'info.slug') }}</td>
            </tr>
            <tr>
                <td>{{ Lang::line('extensions::table.version')->get() }}</td>
                <td>{{ array_get($extension, 'info.version') }}</td>
            </tr>
            <tr>
                <td>{{ Lang::line('extensions::table.description')->get() }}</td>
                <td>{{ array_get($extension, 'info.description') }}</td>
            </tr>
        </tbody>
    </table>

    {{ Form::open() }}
        {{ Form::token() }}
       
        @if ( $dependencies = Platform::extensions_manager()->has_dependencies(array_get($extension, 'info.slug')) )
        <h5>{{ Lang::line('extensions::general.heading.view.dependencies')->get() }}</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="16%">{{ Lang::line('extensions::table.name')->get() }}</th>
                    <th width="16%">{{ Lang::line('extensions::table.slug')->get() }}</th>
                    <th width="40%">{{ Lang::line('general.status')->get() }}</th>
                </tr>
            <thead>
            <tbody>
                @foreach ( $dependencies as $dependent )
                <tr>
                    <td><a href="{{ URL::to_admin('extensions/view/' . array_get($extensions, $dependent . '.info.slug')) }}">{{ array_get($extensions, $dependent . '.info.name', $dependent) }}</a></td>
                    <td>{{ $dependent }}</td>
                    <td>
                        @if ( Platform::extensions_manager()->is_installed($dependent) )
                            <span class="label label-success">{{ Lang::line('extensions::table.installed')->get() }}</span>

                            @if ( Platform::extensions_manager()->is_disabled($dependent) )
                                <span class="label label-warning">{{ Lang::line('extensions::table.disabled')->get() }}</span>
                                <span class="pull-right">
                                    <button class="btn btn-small" type="submit" name="enable_required" value="{{ $dependent }}">{{ Lang::line('extensions::button.enable')->get() }}</button>
                                </span>
                            @else
                                <span class="label label-success">{{ Lang::line('extensions::table.enabled')->get() }}</span>
                            @endif
                        @elseif( Platform::extensions_manager()->is_uninstalled($dependent) )
                            @if ( Platform::extensions_manager()->can_install($dependent) )
                                <span class="label label-warning">{{ Lang::line('extensions::table.uninstalled')->get() }}</span>
                                <span class="pull-right">
                                    <button class="btn btn-small" type="submit" name="install_required" value="{{ $dependent }}">{{ Lang::line('extensions::button.install')->get() }}</button>
                                </span>
                            @else
                                @if ( Platform::extensions_manager()->exists($dependent) )
                                <span class="label label-important">{{ Lang::line('extensions::messages.error.dependencies')->get() }}</span>
                                @else
                                <span class="label label-important">{{ Lang::line('extensions::messages.error.not_found', array('extension' => $dependent))->get() }}</span>
                                @endif
                            @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        @if ( $dependents = Platform::extensions_manager()->has_dependents(array_get($extension, 'info.slug')) )
        <h5>{{ Lang::line('extensions::general.heading.view.dependents')->get() }}</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="16%">{{ Lang::line('extensions::table.name')->get() }}</th>
                    <th width="16%">{{ Lang::line('extensions::table.slug')->get() }}</th>
                    <th width="40%">{{ Lang::line('general.status')->get() }}</th>
                </tr>
            <thead>
            <tbody>
                @foreach ( $dependents as $dependent )
                <tr>
                    <td><a href="{{ URL::to_admin('extensions/view/' . array_get($extensions, $dependent . '.info.slug')) }}">{{ array_get($extensions, $dependent . '.info.name') }}</a></td>
                    <td>{{ $dependent }}</td>
                    <td>
                        @if ( Platform::extensions_manager()->is_installed($dependent) )
                            <span class="label label-success">{{ Lang::line('extensions::table.installed')->get() }}</span>

                            @if ( Platform::extensions_manager()->is_disabled($dependent) )
                                <span class="label label-warning">{{ Lang::line('extensions::table.disabled')->get() }}</span>
                                <span class="pull-right">
                                    <button class="btn btn-small" type="submit" name="enable_required" value="{{ $dependent }}">{{ Lang::line('extensions::buttons.enable')->get() }}</button>
                                </span>
                            @else
                                <span class="label label-success">{{ Lang::line('extensions::table.enabled')->get() }}</span>
                            @endif
                        @elseif( Platform::extensions_manager()->is_uninstalled($dependent) )
                            <span class="label label-{{ ( Platform::extensions_manager()->can_install($dependent) ? 'warning' : 'important' ) }}">{{ Lang::line('extensions::table.uninstalled')->get() }}</span>
                            @if( Platform::extensions_manager()->can_install($dependent) )
                                <span class="pull-right">
                                    <button class="btn btn-small" type="submit" name="install_required" value="{{ $dependent }}">{{ Lang::line('extensions::button.install')->get() }}</button>
                                </span>
                            @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <h5>{{ Lang::line('extensions::general.heading.view.actions')->get() }}</h5>
        @if ( Platform::extensions_manager()->is_core(array_get($extension, 'info.slug')) )
            @if ( Platform::extensions_manager()->has_update(array_get($extension, 'info.slug')) )
                <button class="btn" type="submit" name="update" value="{{ array_get($extension, 'info.slug') }}">{{ Lang::line('extensions::button.update')->get() }}</button>
            @else
                <span class="label label-important">{{ Lang::line('extensions.is_core')->get() }}</span>
            @endif
        @else
            @if ( Platform::extensions_manager()->is_installed(array_get($extension, 'info.slug')) )
                @if ( Platform::extensions_manager()->can_uninstall(array_get($extension, 'info.slug')) )
                    <button class="btn btn-danger" type="submit" name="uninstall" value="{{ array_get($extension, 'info.slug') }}">{{ Lang::line('extensions::button.uninstall')->get() }}</button>

                    @if ( Platform::extensions_manager()->is_enabled(array_get($extension, 'info.slug')) )
                        <button class="btn" type="submit" name="disable" value="{{ array_get($extension, 'info.slug') }}">{{ Lang::line('extensions::button.disable')->get() }}</button>
                    @else
                        <button class="btn" type="submit" name="enable" value="{{ array_get($extension, 'info.slug') }}">{{ Lang::line('extensions::button.enable')->get() }}</button>
                    @endif

                    @if ( Platform::extensions_manager()->has_update(array_get($extension, 'info.slug')) )
                        <button class="btn" type="submit" name="update" value="{{ array_get($extension, 'info.slug') }}">{{ Lang::line('extensions::button.update')->get() }}</button>
                    @endif
                @else
                    <span class="label label-info">{{ Lang::line('extensions.required')->get() }}</span>
                @endif
            @else
                @if ( ! Platform::extensions_manager()->can_install(array_get($extension, 'info.slug')) )
                    <span class="label label-warning">{{ Lang::line('extensions.requires')->get() }}</span>
                @else
                    <div class="btn-group">
                        <button class="btn" type="submit" name="install" value="{{ array_get($extension, 'info.slug') }}">{{ Lang::line('extensions::button.install')->get() }}</button>
                    </div>
                @endif
            @endif
        @endif
    {{ Form::close() }}
</section>
@endsection