(function() {

	var NestySortable = function(element, options) {
		if (this === window) {
			return new NestySortable(element, options);
		}

		// Check dependencies
		if ( ! $().nestedSortable) {
			$.error('$.nestySortable requires $.nestedSortable');
		}
		if (typeof Tempo === 'undefined') {
			$.error('TempoJS is a dependency for $.portfolio');
		}

		// Default options
		this.options = {

			// Namespace for the plugin for registering
			// dom events
			namespace: 'nestysortable',

			// The selector for the sortable list,
			// used to cache the sortable list property
			sortableSelector    : '.items',
			itemSelector        : '.item',
			itemDetailsSelector : '.item-details',
			itemRemoveSelector  : '.item-remove',
			itemAddSelector     : '.item-add',

			// Options to be passed to $.NestedSortable()
			sortable : {
				disableNesting : 'nestysortable-no-nesting',
				errorClass     : 'nestysortable-error',
				doNotClear     : false,
				listType       : 'ol',
				items          : 'li',
				maxLevels      : 0,
				protectRoot    : false,
				tolerance      : 'pointer',
				rootID         : null,
				rtl            : false,
				tabSize        : 20,

				isAllowed: function(item, parent) { return true; }
			},

			/**
			 * An object containing all the fields for the
			 * Nesty Sortable. Each key in the object represents
			 * the field's slug. Each field has a unique slug.
			 * Each value in the object is an object containing
			 * specifications for the field:
			 *
			 *   - newSelector: This is the selector string (or jQuery object)
			 *                  representing the DOM object for a each new sortable
			 *                  object.
			 *
			 * <code>
			 *		{
			 *			'my_field_slug' : {
			 *				newSelector : '.new-item-my-field'
			 *			}
			 *		}
			 * </code>
			 */
			fields : {},

			// Invalid field callback - must return true for valid
			// field or false for invalid field.
			invalidFieldCallback : function(slug, field, value) {},

			// The ID of the last item added. Used so we fill
			// new templates with an ID that won't clash with existing
			// items.
			lastItemId: 0,

			// This is the selector for the new item's template.
			// This container should be hidden at all times as we
			// clone the HTML inside of this, apply the template and
			// then attach that to the end of the list.
			template : {
				containerSelector : '.items-template-container',
				selector          : '.items-template',
				varBraces         : '\\[\\%\\%\\]',
				tagBraces         : '\\[\\?\\?\\]'
			},

			// Is the form to AJAX submit or tranditional
			// submit?
			ajax: false,

			// The input name for the items
			// hierarchy that's posted to
			// the server.
			hierarchyInputName: 'items_hierarchy'
		}

		// Override options
		$.extend(true, this.options, options);
		this.$element  = element;
		this.$sortable = this.$element.find(this.options.sortableSelector);
		this.$itemAdd  = this.$element.find(this.options.itemAddSelector);

		if ($.isEmptyObject(this.options.fields)) {
			$.error('No fields provided.');
		}

		if (this.$sortable.length === 0) {
			$.error('No sortable list found for selector ['+this.$sortable.selector+']');
		}

		if (this.$itemAdd.length === 0) {
			$.error('No add button found for selector ['+this.$itemAdd.selector+']');
		}

		return this.setupNestedSortable()
		           .observeAddingItems()
		           .observeRemovingItems()
		           .observeSaving();
	}

	NestySortable.prototype = {

		setupNestedSortable: function() {
			var that = this,
			      ns = this.options.namespace;

			that.$sortable.nestedSortable(that.options.sortable);

			return this;
		},

		observeAddingItems: function() {
			var that = this,
			      ns = this.options.namespace;

			that.$itemAdd.on('click.'+ns, function(e) {
				e.preventDefault();

				// Get the item template
				var template = that.options.template.selector,
				    valid    = true,
				    itemId   = ++that.options.lastItemId;
				    data     = {
						id      : itemId,
						raw     : {},
						control : {}
					};

				$.each(that.options.fields, function(slug, field) {
					var $formElement = $(field.newSelector);

					// Skip non-existent DOM elements
					if ( ! $formElement.length) {
						return;
					}

					// Validate form element
					if ($formElement.is(':invalid')) {
						result = that.options.invalidFieldCallback(slug, field, $formElement.val());
 
						if (typeof result !== 'undefined' && valid === true) {
							valid = Boolean(result);

							// Invalid, break the loop now.
							if (valid === false) {
								return false; // Break
							}
						}

						return;
					}

					// Checkboxes have a boolean attribute,
					// We add 'checked="checked"' to it.
					if ($formElement.is(':checkbox')) {
						data.control[slug] = ($formElement.attr('checked')) ? 'checked="checked"' : '';
					}

					// Selects have a 'data-selected' attribute
					// on the select element itthat
					else if ($formElement.is('select')) {
						data.control[slug] = 'data-value="' + $formElement.val() + '"';
					}
					else {
						data.control[slug] = $formElement.val();
						rawValue   = $formElement.val();
					}

					data.raw[slug] = $formElement.val();
				});

				if (valid !== true) {
					return false;
				}

				console.log(data);

				// Get the template
				var $template = $(that.options.template.selector).clone();
				$template.appendTo(that.options.template.containerSelector)
				         .attr('id', that.options.template.selector+'-'+that._randomString());

				// Parse with TempoJS
				Tempo.prepare($template.attr('id'), {
					'var_braces' : that.options.template.varBraces,
					'tag_braces' : that.options.template.tagBraces
				}).render(data);

				// Append
				var $templateContents = $template.children('[data-template]');
				$templateContents.appendTo(that.$sortable)
				                 .find('select').each(function() {
					var value;
					if (value = $(this).attr('data-value')) {
						$(this).val(value)
						       .removeAttr('data-value');
					}
				});

				// Delete the DOM element
				$template.remove();

				// Loop through fields
				$.each(that.options.fields, function(slug, field) {
					$(field.newSelector).val('');
				});
			});

			return this;
		},

		observeRemovingItems: function() {
			var that = this,
			      ns = this.options.namespace;

			$('body').on('click', that.options.itemRemoveSelector, function(e) {
				e.preventDefault();

				if ($(this).hasClass('disabled')) {
					// return false;
				}

				// Find closest item
				var $item = $(this).closest(that.options.sortable.items),
				 $childrenList = $item.children(that.options.sortable.listType);
			});

			return this;
		},

		observeSaving: function() {
			var that = this,
			      ns = this.options.namespace;

			// Catch submit button. Remove validation
			// from new items.
			that.$element.find(':submit').on('click.'+ns, function(e) {

				// Loop through the defined fields and remove
				// validation on them.
				$.each(that.options.fields, function(slug, field) {
					var $formElement = $(field.newSelector);

					$formElement.val('');

					if ($formElement.attr('required')) {
						$formElement.attr('data-required', 'required')
						            .removeAttr('required');
					}
				});
			});

			// Catch form submission.
			that.$element.submit(function(e) {

				// Remove the template from the DOM
				// so it's not submitted, we'll re-insert
				// after submit if using AJAX
				var $template = that.$element.find(that.options.template.selector);
				$templateClone = $template.clone();
				$template.remove();

				// If we have Bootstrap button plugin
				if ($().button) {
					var $submitButton = that.$element.find(':submit');
				}

				// AJAX form submission
				if (that.options.ajax === true) {
					e.preventDefault();

					// Get some parameters
					var inputName = that.options.hierarchyInputName
					       params = {};
					params[inputName] = that.$sortable.nestedSortable('toHierarchy', {
						attribute: 'data-item'
					});
					var postData = $.extend(that.$element.find('input').serializeObject(), params);

					// AJAX call to save menu
					$.ajax({
						url        : $(this).attr('action'),
						type       : 'POST',
						data       : postData,
						beforeSend : function(jqXHR, settings) {

							// If we have Bootstrap button plugin
							if ($().button) {
								$submitButton.button('loading');
							}
						},
						success    : function(data, textStatus, jqXHR) {

							// If we have Bootstrap button plugin
							if ($().button) {
								$submitButton.button('complete');

								setTimeout(function() {
									$submitButton.button('reset');
								}, 300);
							}
						},
						error      : function(jqXHR, textStatus, errorThrown) {
							var response = $.parseJSON(jqXHR.responseText);
							$.error(jqXHR.status + ' ' + errorThrown);
						}
					});

					// If we're using AJAX, put the template back in the DOM.
					// Traditional form submission doesn't need it again as
					// we're leaving the page.
					$templateClone.appendTo(that.options.itemTemplateContainerSelector);

					// Loop through the defined fields and re-add
					// validation back in
					$.each(that.options.fields, function(slug, field) {
						var $formElement = $(field.newSelector);

						$formElement.val('');

						if ($formElement.attr('data-required')) {
							$formElement.attr('required', 'required')
							            .removeAttr('data-required');
						}
					});

					return false;
				}

				// If we have Bootstrap button plugin
				if ($().button) {
					$submitButton.button('loading');
				}

				// Traditional form submission
				var postData = that.$sortable.nestedSortable('toHierarchy', {
					attribute: 'data-item'
				});

				// Append input to the form. It's values are JSON encoded..
				that.$element.append('<input type="hidden" name="' + that.options.hierarchyInputName + '" value=\'' + JSON.stringify(postData) + '\'>');

				return true;
			});
			
			return this;
		},

		_randomString: function() {
			var chars         = 'abcdefghiklmnopqrstuvwxyz',
			    string_length = 8,
			    randomstring  = '';

			for (var i = 0; i < string_length; i++) {
				var rnum = Math.floor(Math.random() * chars.length);
				randomstring += chars.substring(rnum, rnum + 1);
			}

			return randomstring;
		}
	}

	// The actual jquery plugin
	$.fn.nestySortable = function(settings) {
		return new NestySortable(this, settings);
	}

})(jQuery);