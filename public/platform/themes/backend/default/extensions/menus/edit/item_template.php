<li data-item="item_{{id}}">

	<div class="item">

		<header class="item-header">
			<span class="item-sort"><i class="icon-move"></i></span> {{raw.name}} <span class="item-toggle-details"><i class="icon-edit"></i></span>
		</header>

		<section class="clearfix item-details">

			<div class="well">
				<?php echo Form::hidden('item_fields[{{id}}][is_new]', 1); ?>

				<div class="control-group">
					<?php echo Form::label('menu-items-{{id}}-name', Lang::line('menus::form.item.name')); ?>
					<?php echo Form::text('item_fields[{{id}}][name]', null, array('id' => 'menu-items-{{id}}-name', 'placeholder' => Lang::line('menus::form.item.name'), '{{field.name}}', 'required')); ?>
				</div>

				<div class="control-group">
					<?php echo Form::label('menu-items-{{id}}-slug', Lang::line('menus::form.item.slug')); ?>
					<?php echo Form::text('item_fields[{{id}}][slug]', null, array('id' => 'menu-items-{{id}}-slug', 'placeholder' => Lang::line('menus::form.item.slug'), 'class' => 'item-slug', '{{field.slug}}', 'required')); ?>
				</div>

				<div class="control-group">
					<?php echo Form::label('menu-items-{{id}}-uri', Lang::line('menus::form.item.uri')); ?>
					<?php echo Form::text('item_fields[{{id}}][uri]', null, array('id' => 'menu-items-{{id}}-uri', 'placeholder' => Lang::line('menus::form.item.uri'), 'class' => 'item-uri', '{{field.uri}}', 'required')); ?>
				</div>

				<div class="control-group">
					<label class="checkbox">
						<input type="checkbox" name="item_fields[{{id}}][secure]" value="1" id="menu-items-{{id}}-secure" class="item-secure" {{field.secure}} required>
						<?php echo Lang::line('menus::form.item.secure'); ?>
					</label>
				</div>

				<div class="control-group">
					<label class="checkbox">
						<input type="checkbox" name="item_fields[{{id}}][target]" value="1" id="menu-items-{{id}}-target" class="item-target" {{field.target}} required>
						<?php echo Lang::line('menus::form.item.target'); ?>
					</label>
				</div>


				<div class="control-group">
					<?php echo Form::label('menu-items-{{id}}-type', Lang::line('menus::form.item.type')); ?>
					<?php echo Form::select('item_fields[{{id}}][type]', array(0 => Lang::line('menus::form.item.show_always'), 1 => Lang::line('menus::form.item.logged_in'), 2 => Lang::line('menus::form.item.logged_out'), 3 => Lang::line('menus::form.item.admin')), 0, array('id' => 'menu-items-{{id}}-type')); ?>
				</div>

				<div class="control-group">
					<?php echo Form::label('menu-items-{{id}}-status', Lang::line('menus::form.item.status')); ?>
					<?php echo Form::select('item_fields[{{id}}][status]', array(1 => Lang::line('menus::form.item.yes'), 0 => Lang::line('menus::form.item.no')), 1, array('id' => 'menu-items-{{id}}-status')); ?>
				</div>
			</div>

			<button class="pull-right btn btn-danger btn-mini item-remove">
				<?php echo Lang::line('menus::button.remove_item'); ?>
			</button>

		</section>

	</div>

</li>
