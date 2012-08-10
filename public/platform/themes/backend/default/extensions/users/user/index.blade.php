@layout('templates.default')

<!-- Page Title -->
@section('title')
	{{ Lang::line('users::users.general.title') }}
@endsection

<!-- Queue Styles -->
{{ Theme::queue_asset('table', 'css/table.css', 'style') }}

<!-- Styles -->
@section ('styles')
@endsection

<!-- Queue Scripts -->
{{ Theme::queue_asset('table', 'js/table.js', 'jquery') }}
{{ Theme::queue_asset('users', 'users::js/users.js', 'jquery') }}

<!-- Scripts -->
@section('scripts')
@endsection

<!-- Page Content -->
@section('content')
<section id="users">

	<header class="head row">
		<div class="span4">
			<h1>{{ Lang::line('users::users.general.title') }}</h1>
			<p>{{ Lang::line('users::users.general.description') }}</p>
		</div>
		<nav class="tertiary-navigation span8">
			@widget('platform.menus::menus.nav', 2, 1, 'nav nav-pills pull-right', ADMIN)
		</nav>
	</header>

	<hr>

	<div id="table">
		<div class="actions clearfix">
			<div id="table-filters" class="form-inline pull-left"></div>
			<div class="pull-right">
				<a class="btn btn-large btn-primary" href="{{ URL::to_secure(ADMIN.'/users/create') }}">{{ Lang::line('buttons.create') }}</a>
			</div>
		</div>

		<div class="row">
			<div class="span12">
				<div class="row">
					<ul id="table-filters-applied" class="nav nav-tabs span10"></ul>
				</div>
				<div class="row">
					<div class="span10">
						<div class="table-wrapper">
							<table id="users-table" class="table table-bordered">
								<thead>
									<tr>
										@foreach ($columns as $key => $col)
										<th data-table-key="{{ $key }}">{{ $col }}</th>
										@endforeach
										<th></th>
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
