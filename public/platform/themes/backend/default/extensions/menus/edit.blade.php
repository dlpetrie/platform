@layout('templates.default')

<!-- Page Title -->
@section('title')
	{{ Lang::line('menus::menus.title') }}
@endsection

<!-- Queue Styles -->
{{ Theme::queue_asset('menus', 'menus::css/menus.less', 'style') }}

<!-- Styles -->
@section ('styles')
@endsection

<!-- Queue Scripts -->
{{ Theme::queue_asset('jquery-helpers', 'js/jquery/helpers.js', 'jquery') }}
{{ Theme::queue_asset('bootstrap-tab', 'js/bootstrap/tab.js', 'jquery') }}
{{ Theme::queue_asset('jquery-ui', 'js/jquery/ui-1.8.18.min.js', 'jquery') }}
{{ Theme::queue_asset('jquery-nestedsortable', 'js/jquery/nestedsortable-1.3.4.js', 'jquery') }}
{{ Theme::queue_asset('jquery-nestysortable', 'js/jquery/nestysortable-1.0.js', 'jquery') }}
{{ Theme::queue_asset('menussortable', 'menus::js/menussortable-1.0.js', 'jquery')}}

<!-- Scripts -->
@section('scripts')
<script>
	$(document).ready(function() {

		$('#platform-menu').menuSortable({

			// Array of ALL existing
			// slugs. Just so we don't
			// have any clashes
			persistedSlugs : {{ $persisted_slugs }},

			// Define Nesty Sortable dependency for the menu sortable.
			nestySortable: {
				fields           : [
					{
						name        : 'name',
						newSelector : '#new-item-name'
					},
					{
						name        : 'slug',
						newSelector : '#new-item-slug'
					},
					{
						name        : 'uri',
						newSelector : '#new-item-uri'
					},
					{
						name        : 'secure',
						newSelector : '#new-item-secure'
					},
					{
						name        : 'type',
						newSelector : '#new-item-type'
					},
					{
						name        : 'target',
						newSelector : '#new-item-target'
					},
				],
				itemTemplate         : {{ $item_template }},
				lastItemId           : {{ $last_item_id }}
			}
		});
	});
	</script>
@endsection

@section('content')
<section id="menus-edit">

	<header class="row">
		<div class="span4">
			<h1>{{ Lang::line('menus::menus.update.title') }}</h1>
			<p>{{ Lang::line('menus::menus.update.description') }}</p>
		</div>
		<nav class="actions span8 pull-right"></nav>
	</header>

	<hr>

	<form action="{{ URL::to_secure(ADMIN.'/menus/edit/'.$menu_slug ?: null) }}" id="platform-menu" class="form-horizontal" method="POST" accept-char="UTF-8" autocomplete="off">
		<input type="hidden" name="{{ Session::csrf_token }}" value="{{ Session::token() }}">

		<div class="tabbable">
			<ul class="nav nav-tabs">
				<li class="{{ ($menu_slug) ? 'active' : null }}"><a href="#menus-edit-items" data-toggle="tab">{{ Lang::line('menus::menus.tabs.items') }}</a></li>
				<li class="{{ ( ! $menu_slug) ? 'active' : null }}"><a href="#menus-edit-menu-options" data-toggle="tab">{{ Lang::line('menus::menus.tabs.options') }}</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane {{ ($menu_slug) ? 'active' : null }}" id="menus-edit-items">

					<div class="clearfix">
						<a class="pull-right btn items-toggle-all">{{ Lang::line('menus::menus.button.toggle_items_details') }} <i class="icon-edit"></i></a>
					</div>

					<div class="clearfix">

						<div class="platform-new-item">

							<div class="well">

								<legend>{{ Lang::line('menus::menus.general.new_item') }}</legend>
								<hr>

								<fieldset>

									<div>
										{{ Form::text(null, null, array('class' => 'input-block-level', 'id' => 'new-item-name', 'placeholder' => Lang::line('menus::menus.general.name'), 'required')) }}
									</div>

									<div>
										{{ Form::text(null, null, array('class' => 'input-block-level item-slug', 'id' => 'new-item-slug', 'placeholder' => Lang::line('menus::menus.general.slug'), 'required')) }}
									</div>

									<div>
										{{ Form::label('new-item-uri', Lang::line('menus::menus.general.uri')) }}
										{{ Form::text(null, null, array('class' => 'input-block-level', 'id' => 'new-item-uri', 'placeholder' => Lang::line('menus::menus.general.uri'), 'required')) }}
									</div>

									<div>
										<label class="checkbox">
											{{ Form::checkbox(null, 1, false, array('id' => 'new-item-secure')) }}
											{{ Lang::line('menus::menus.general.secure') }}
										</label>
									</div>

									<div>
										<label class="checkbox">
											{{ Form::checkbox(null, 1, false, array('id' => 'new-item-target')) }}
											{{ Lang::line('menus::menus.general.target') }}
										</label>
									</div>

									<div>
										{{ Form::label('new-item-type', Lang::line('menus::menus.general.type')) }}
										{{ Form::select(null, array('0' => Lang::line('menus::menus.general.show_always'), '1' => Lang::line('menus::menus.general.logged_in'), '2' => Lang::line('menus::menus.general.logged_out'), '3' => Lang::line('menus::menus.general.admin')), '0', array('id' => 'new-item-type')) }}
									</div>
								</fieldset>
								<hr>

								<button type="button" class="btn btn-small btn-primary items-add-new">{{ Lang::line('menus::menus.button.add_item') }}</button>

							</div>

						</div>

						<ol class="platform-menu">
							@if (isset($menu['children']) and (is_array($menu['children'])))
								@foreach ($menu['children'] as $child)
									@render('menus::edit.item', array('item' => $child))
								@endforeach
							@endif
						</ol>

					</div>

				</div>
				<div class="tab-pane {{ ( ! $menu_slug) ? 'active' : null }}" id="menus-edit-menu-options">
					<fieldset>
						<div>
							{{ Form::label('menu-name', Lang::line('menus::menus.general.name')) }}
							{{ Form::text('name', isset($menu['name']) ? $menu['name'] : null, array('id' => 'menu-name', 'placeholder' => Lang::line('menus::menus.general.name'), (isset($menu['user_editable']) and ! $menu['user_editable']) ? 'disabled' : 'required')) }}
						</div>

						<div>
							{{ Form::label('menu-slug', Lang::line('menus::menus.general.slug')) }}
							{{ Form::text('slug', isset($menu['slug']) ? $menu['slug'] : null, array('id' => 'menu-slug', 'placeholder' => Lang::line('menus::menus.general.slug'), (isset($menu['user_editable']) and ! $menu['user_editable']) ? 'disabled' : 'required')) }}
						</div>
					</fieldset>

				</div>
			</div>
		</div>

		<div class="form-actions">

			<button type="submit" class="btn btn-primary btn-save-menu">
				{{ Lang::line('button.'.(($menu_slug) ? 'update' : 'create')) }}
			</button>

			{{ HTML::link_to_secure(ADMIN.'/menus', Lang::line('button.cancel'), array('class' => 'btn')) }}

		</div>

	</form>

</section>
@endsection
