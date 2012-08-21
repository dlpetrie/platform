@layout('templates.default')

<!-- Page Title -->
@section('title')
	{{ Lang::line('menus::general.title') }}
@endsection

<!-- Queue Styles -->
{{ Theme::queue_asset('menus', 'menus::css/menus.less', 'style') }}
{{ Theme::queue_asset('toggle', 'css/bootstrap/less/toggle.less', 'style') }}


<!-- Styles -->
@section ('styles')
@endsection

<!-- Queue Scripts -->
{{ Theme::queue_asset('jquery-helpers', 'js/jquery/helpers.js', 'jquery') }}
{{ Theme::queue_asset('bootstrap-tab', 'js/bootstrap/tab.js', 'jquery') }}
{{ Theme::queue_asset('bootstrap-toggle', 'js/bootstrap/toggle.js', 'jquery') }}
{{ Theme::queue_asset('jquery-ui', 'js/jquery/ui-1.8.18.min.js', 'jquery') }}
{{ Theme::queue_asset('jquery-nestedsortable', 'js/jquery/nestedsortable-1.3.4.js', 'jquery') }}
{{ Theme::queue_asset('jquery-nestysortable', 'js/jquery/nestysortable-1.0.js', 'jquery') }}
{{ Theme::queue_asset('menussortable', 'menus::js/menussortable-1.0.js', 'jquery')}}
{{ Theme::queue_asset('validate', 'js/validate.js', 'jquery')}}

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

		// Toggle Checkboxes.
		$('.basic').toggle({
			style: {
				enabled: 'success',
          		disabled: 'danger'
			}
		});

		//validate forms
		//Validate.setup($("#platform-menu"));

	});
	</script>
@endsection

@section('content')
<section id="menus-edit">

	<header class="row">
		<div class="span4">
			<h1>{{ Lang::line('menus::general.update.title') }}</h1>
			<p>{{ Lang::line('menus::general.update.description') }}</p>
		</div>
		<nav class="actions span8 pull-right"></nav>
	</header>

	<hr>

	<form action="{{ URL::to_secure(ADMIN.'/menus/edit/'.$menu_slug ?: null) }}" id="platform-menu" class="form-horizontal" method="POST" accept-char="UTF-8" autocomplete="off">
		<input type="hidden" name="{{ Session::csrf_token }}" value="{{ Session::token() }}">

		<div class="tabbable">
			<ul class="nav nav-tabs">
				<li class="{{ ($menu_slug) ? 'active' : null }}"><a href="#menus-edit-items" data-toggle="tab">{{ Lang::line('menus::general.tabs.items') }}</a></li>
				<li class="{{ ( ! $menu_slug) ? 'active' : null }}"><a href="#menus-edit-menu-options" data-toggle="tab">{{ Lang::line('menus::general.tabs.options') }}</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane {{ ($menu_slug) ? 'active' : null }}" id="menus-edit-items">

					<div class="clearfix">
						<a class="pull-right btn items-toggle-all">{{ Lang::line('menus::button.toggle_items_details') }} <i class="icon-edit"></i></a>
					</div>

					<div class="clearfix">

						<div class="platform-new-item">

							<div class="well">

								<fieldset>
									<legend>{{ Lang::line('menus::form.item.legend') }}</legend>
									<hr>

									<!-- Item Name -->
									<div>
										<input type="text" name="new_item_name" id="new-item-name" value="" placeholder="{{ Lang::line('menus::form.item.name') }}" required>
									</div>

									<!-- Slug -->
									<div>
										<input type="text" name="new_item_slug" id="new-item-slug" value="" placeholder="{{ Lang::line('menus::form.item.slug') }}" required>
									</div>

									<!-- URI -->
									<div>
										<input type="text" name="new_item_slug" id="new-item-uri" value="" placeholder="{{ Lang::line('menus::form.item.uri') }}">
									</div>

									<!-- Secure HTTPS -->
									<div>
										<p class="title">{{ Lang::line('menus::form.item.secure') }}</p>
										<div class="toggle basic primary" data-enabled="ENABLED" data-disabled="DISABLED" data-toggle="toggle">
											<input type="checkbox" value="1" name="new_item_secure" id="new-item-secure" class="checkbox" />
											<label class="check" for="new_item_secure"></label>
										</div>
									</div>

									<!-- Target -->
									<div>
										<p class="title">{{ Lang::line('menus::form.item.target') }}</p>
										<div class="toggle basic primary" data-enabled="ENABLED" data-disabled="DISABLED" data-toggle="toggle">
											<input type="checkbox" value="1" name="new_item_target" id="new-item-target" class="checkbox" />
											<label class="check" for="new_item_target"></label>
										</div>
									</div>

									<!-- Visability-->
									<div>
										{{ Form::label('new-item-type', Lang::line('menus::form.item.type')) }}
										{{ Form::select(null, array('0' => Lang::line('menus::form.item.show_always'), '1' => Lang::line('menus::form.item.logged_in'), '2' => Lang::line('menus::form.item.logged_out'), '3' => Lang::line('menus::form.item.admin')), '0', array('id' => 'new-item-type')) }}
									</div>

								</fieldset>
								<hr>
								<div class="actions">
									<button type="button" class="btn btn-large btn-primary items-add-new">{{ Lang::line('menus::button.add_item') }}</button>
								</div>

							</div>

						</div> <!-- /end - platform-new-item -->

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
							{{ Form::label('menu-name', Lang::line('menus::form.item.name')) }}
							{{ Form::text('name', isset($menu['name']) ? $menu['name'] : null, array('id' => 'menu-name', 'placeholder' => Lang::line('menus::form.item.name'), (isset($menu['user_editable']) and ! $menu['user_editable']) ? 'disabled' : 'required')) }}
						</div>

						<div>
							{{ Form::label('menu-slug', Lang::line('menus::form.item.slug')) }}
							{{ Form::text('slug', isset($menu['slug']) ? $menu['slug'] : null, array('id' => 'menu-slug', 'placeholder' => Lang::line('menus::form.item.slug'), (isset($menu['user_editable']) and ! $menu['user_editable']) ? 'disabled' : 'required')) }}
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
