@layout('templates.default')

@section('title')
    {{ Lang::line('localisation::currencies/general.title')->get() }}
@endsection

@section ('styles')
{{ Theme::asset('css/table.css') }}
@endsection

@section('scripts')
{{ Theme::asset('js/table.js') }}
{{ Theme::asset('localisation::js/currencies.js') }}
@endsection

@section('content')
<section id="currencies">
    <header class="head row-fluid">
        <div class="span6">
            <h1>{{ Lang::line('localisation::currencies/general.title')->get() }}</h1>
            <p>{{ Lang::line('localisation::currencies/general.description.index')->get() }}</p>
        </div>
    </header>

    <hr />

    <div id="table">
        <div class="actions clearfix">
            <div id="table-filters" class="form-inline pull-left"></div>
            <div class="pull-right">
                <a class="btn btn-large btn-primary" href="{{ URL::to_admin('localisation/currencies/create') }}">{{ Lang::line('button.create')->get() }}</a>
            </div>
        </div>

        <div class="row-fluid">
            <div class="span12">
                <div class="row-fluid">
                    <ul id="table-filters-applied" class="nav nav-tabs span10"></ul>
                </div>
                <div class="row-fluid">
                    <div class="span10">
                        <div class="table-wrapper">
                            <table id="users-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th data-table-key="name" class="span4">{{ Lang::line('localisation::currencies/table.name')->get() }}</th>
                                        <th data-table-key="code"class="span2">{{ Lang::line('localisation::currencies/table.code')->get() }}</th>
                                        <th class="span2"></th>
                                    </tr>
                                <thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tabs-right span2">
                        <div class="processing"></div>
                        <ul id="table-pagination" class="nav nav-tabs"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection