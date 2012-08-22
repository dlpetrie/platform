<li data-item="child_{{ array_get($child, 'id', '[%id%]') }}">
	<div class="child">
		
		<header class="child-header">
			<span class="child-sort">
				<i class="icon-move"></i>
			</span>
			{{ array_get($child, 'name', '[%name%]') }}
			<span class="child-toggle-details">
				<i class="icon-edit"></i>
			</span>
		</header>

		<section class="child-details form-horizontal">
			<fieldset>
				<legend>{{ Lang::line('menus::form.update.child.legend') }}</legend>

				<!-- Name -->
				<div class="control-group">
					<label class="control-label" for="menu-children-{{ array_get($child, 'id', '[%id%]') }}-name">{{ Lang::line('menus::form.child.name') }}</label>
					<div class="controls">
						<input type="text" name="children[{{ array_get($child, 'id', '[%id%]') }}][name]" id="menu-children-{{ array_get($child, 'id', '[%id%]') }}-name" value="{{ array_get($child, 'name', '[%name%]') }}" {{ (array_get($child, 'user_editable')) ? 'required' : 'disabled' }}>
					</div>
				</div>

				<!-- Slug -->
				<div class="control-group">
					<label class="control-label" for="menu-children-{{ array_get($child, 'id', '[%id%]') }}-slug">{{ Lang::line('menus::form.child.slug') }}</label>
					<div class="controls">
						<input type="text" name="children[{{ array_get($child, 'id', '[%id%]') }}][slug]" id="menu-children-{{ array_get($child, 'id', '[%id%]') }}-slug" value="{{ array_get($child, 'slug', '[%slug%]') }}" {{ (array_get($child, 'user_editable')) ? 'required' : 'disabled' }}>
					</div>
				</div>

				<!-- URI -->
				<div class="control-group">
					<label class="control-label" for="menu-children-{{ array_get($child, 'id', '[%id%]') }}-uri">{{ Lang::line('menus::form.child.uri') }}</label>
					<div class="controls">
						<input type="text" name="children[{{ array_get($child, 'id', '[%id%]') }}][uri]" id="menu-children-{{ array_get($child, 'id', '[%id%]') }}-uri" value="{{ array_get($child, 'uri') }}" {{ ( ! array_get($child, 'user_editable') or URL::valid(array_get($child, 'user_editable'))) ? 'disabled' : null }}>
					</div>
				</div>

				<!-- Secure -->
				<div class="control-group">
					<label class="control-label" for="menu-children-{{ array_get($child, 'id', '[%id%]') }}-secure">{{ Lang::line('menus::form.child.secure') }}</label>
					<div class="controls">
						<?php /*
						Not Working
						<div class="toggle basic success {{ ( ! array_get($child, 'user_editable') or URL::valid(array_get($child, 'user_editable'))) ? 'disabled' : null }}" data-enabled="{{ Lang::line('general.enabled') }}" data-disabled="{{ Lang::line('general.disabled') }}" data-toggle="toggle">
							<input type="checkbox" id="menu-children-{{ array_get($child, 'id', '[%id%]') }}-secure" value="{{ array_get($child, 'secure', '[%secure%]') }}" {{ ( ! array_get($child, 'user_editable') or URL::valid(array_get($child, 'user_editable'))) ? 'disabled' : null }}>
							<label class="check" for="menu-children-{{ array_get($child, 'id', '[%id%]') }}-secure"></label>
						</div>
						*/ ?>
						<label class="checkbox">
							<input type="checkbox" name="children[{{ array_get($child, 'id', '[%id%]') }}][secure]" id="menu-children-{{ array_get($child, 'id', '[%id%]') }}-secure" value="{{ array_get($child, 'secure', '[%secure%]') }}" value="1" {{ ( ! array_get($child, 'user_editable') or URL::valid(array_get($child, 'user_editable'))) ? 'disabled' : null }} {{ array_get($child, 'secure', '[%secure%]') ? 'checked' : null }} >
							{{ Lang::line('menus::form.child.secure') }}
						</label>
					</div>
				</div>

				<!-- Target -->
				<div class="control-group">
					<label class="control-label" for="menu-children-{{ array_get($child, 'id', '[%id%]') }}-target">{{ Lang::line('menus::form.child.target.title') }}</label>
					<div class="controls">
						<select name="children[{{ array_get($child, 'id', '[%id%]') }}][target]" id="menu-children-{{ array_get($child, 'id', '[%id%]') }}-target">
							<option value="{{ Platform\Menus\Menu::TARGET_SELF }}" {{ (array_get($child, 'target') == Platform\Menus\Menu::TARGET_SELF) ? 'selected' : null }}>{{ Lang::line('menus::form.child.target.self') }}</option>
							<option value="{{ Platform\Menus\Menu::TARGET_BLANK }}" {{ (array_get($child, 'target') == Platform\Menus\Menu::TARGET_BLANK) ? 'selected' : null }}>{{ Lang::line('menus::form.child.target.blank') }}</option>
							<option value="{{ Platform\Menus\Menu::TARGET_PARENT }}" {{ (array_get($child, 'target') == Platform\Menus\Menu::TARGET_PARENT) ? 'selected' : null }}>{{ Lang::line('menus::form.child.target.parent') }}</option>
							<option value="{{ Platform\Menus\Menu::TARGET_TOP }}" {{ (array_get($child, 'target') == Platform\Menus\Menu::TARGET_TOP) ? 'selected' : null }}>{{ Lang::line('menus::form.child.target.top') }}</option>
						</select>
					</div>
				</div>

				<!-- Visibility -->
				<div class="control-group">
					<label class="control-label" for="menu-children-{{ array_get($child, 'id', '[%id%]') }}-visibility">{{ Lang::line('menus::form.child.visibility.title') }}</label>
					<div class="controls">
						<select name="children[{{ array_get($child, 'id', '[%id%]') }}][visibility]" id="menu-children-{{ array_get($child, 'id', '[%id%]') }}-visibility">
							<option value="{{ Platform\Menus\Menu::VISIBILITY_ALWAYS }}" {{ (array_get($child, 'visibility') == Platform\Menus\Menu::VISIBILITY_ALWAYS) ? 'selected' : null }}>{{ Lang::line('menus::form.child.visibility.always') }}</option>
							<option value="{{ Platform\Menus\Menu::VISIBILITY_LOGGED_IN }}" {{ (array_get($child, 'visibility') == Platform\Menus\Menu::VISIBILITY_LOGGED_IN) ? 'selected' : null }}>{{ Lang::line('menus::form.child.visibility.logged_in') }}</option>
							<option value="{{ Platform\Menus\Menu::VISIBILITY_LOGGED_OUT }}" {{ (array_get($child, 'visibility') == Platform\Menus\Menu::VISIBILITY_LOGGED_OUT) ? 'selected' : null }}>{{ Lang::line('menus::form.child.visibility.logged_out') }}</option>
							<option value="{{ Platform\Menus\Menu::VISIBILITY_ADMIN }}" {{ (array_get($child, 'visibility') == Platform\Menus\Menu::VISIBILITY_ADMIN) ? 'selected' : null }}>{{ Lang::line('menus::form.child.visibility.admin') }}</option>
						</select>
					</div>
				</div>

				<div class="form-actions">
					<button class="pull-right btn btn-{{ (array_get($child, 'user_editable')) ? 'danger' : 'inverse' }} btn-mini child-remove" {{ ( ! array_get($child, 'user_editable') ? 'disabled' : null) }}>
						{{ Lang::line('menus::button.remove_child'.(( ! array_get($child, 'user_editable')) ? '_disabled' : null)) }}
					</button>
				</div>

			</fieldset>
		</section>

	</div>
	<!-- /end - child -->

	@if (isset($child['children']) and is_array($child['children']))
		<ol>
			@foreach ($child['children'] as $grand_child)
				@render('menus::edit.child', array('child' => $grand_child))
			@endforeach
		</ol>
	@endif

</li>