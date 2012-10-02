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
				maxLevels      : 0,
				protectRoot    : false,
				tolerance      : 'pointer',
				rootID         : null,
				rtl            : false,
				tabSize        : 20,

				disableNesting       : 'no-nest',
				forcePlaceholderSize : true,
				handle               : '.child-header',
				helper               :'clone',
				// items                : that.settings.listSelector,
				// maxLevels            : that.settings.maxLevels,
				// opacity              : that.settings.opacity,
				placeholder          : 'placeholder',
				revert               : 250,
				// tabSize              : that.settings.tabSize,
				tolerance            : 'pointer',
				toleranceElement     : '> div',

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
			 *
			 * @var array
			 */
			fields : {},

			// Invalid field callback - must return true for valid
			// field or false for invalid field.
			invalidFieldCallback : function(field, value) {},

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

		return this.setupNestedSortable()
		           .observeAddingItems();
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




			return this;
		}

		// get sortable() {

		// },
		// set sortable(value) {

		// }
	}
	
	// *
	//  * NestySortable object.
	//  *
	//  * @todo Add more validation support for

	var rofl = {
	//  *       new items...
	 
	// var ANestySortable = {

		// // Settings for this instance
		// settings: {

			
		// },

		// // The form object we call $.nestySortable on
		// elem: null,

		// // The sortable list DOM object, cached
		// // for speed
		// _sortable: null,

		// // The selector for an item add button
		// _itemAddButton: null,

		/**
		 * Used to initialise a new instance of
		 * nestySortable
		 */
		// init: function(elem, settings) {
		// 	var that  = this;
		// 	that.elem = elem;

		// 	$.extend(true, that.settings, settings);

		// 	// Check for NestedSortable
		// 	if ( ! $().nestedSortable) {
		// 		$.error('$.nestySortable requires $.nestedSortable');
		// 	}

		// 	// Check for TempoJS
		// 	if ( ! window.Tempo) {
		// 		$.error('$.nestySortable required TempoJS');
		// 	}

		// 	// Initialise NestedSortable
		// 	that.sortable().nestedSortable({
		// 		disableNesting       : 'no-nest',
		// 		forcePlaceholderSize : true,
		// 		handle               : that.settings.itemHandleSelector,
		// 		helper               :'clone',
		// 		items                : that.settings.listSelector,
		// 		maxLevels            : that.settings.maxLevels,
		// 		opacity              : that.settings.opacity,
		// 		placeholder          : 'placeholder',
		// 		revert               : 250,
		// 		tabSize              : that.settings.tabSize,
		// 		tolerance            : 'pointer',
		// 		toleranceElement     : '> div'
		// 	});

		// 	// Observers
		// 	that.observeAddingItems()
		// 			.observeToggling()
		// 			.observeRemovingItems()
		// 			.observeSaving();
		// },

		// /**
		//  * Used to retrieve the sortable DOM object.
		//  */
		// sortable: function() {
		// 	var that = this;

		// 	if ( ! that._sortable) {
		// 		that._sortable = that.elem.find(that.settings.sortableSelector);
		// 	}

		// 	return that._sortable;
		// },

		// *
		//  * Used to retrieve the add button DOM object.
		 
		// itemAddButton: function() {
		// 	var that = this;

		// 	if ( ! that._itemAddButton) {
		// 		that._itemAddButton = that.elem.find(that.settings.itemAddButtonSelector);
		// 	}

		// 	return that._itemAddButton;
		// },

		/**
		 * Observe adding items.
		 */
		observeAddingItems: function() {
			var that = this;

			// When user clicks on the add item button
			that.itemAddButton().on('click', function(e) {
				e.preventDefault();

				// Get the item template
				var itemTemplate = that.settings.itemTemplate;

				// Flag for valid itemTemplate
				var valid = true;

				var itemId               = that.settings.lastItemId + 1;
				that.settings.lastItemId = itemId;

				// Data for templat
				var data = {
					id      : itemId,
					raw     : {},
					control : {}
				};

				// Loop through the defined fields, and replace
				// the template variables with the value of each
				// field's selector.
				for (i in that.settings.fields) {

					// Get some variables
					var field        = that.settings.fields[i],
					    $formElement = $(field.newSelector);

					// Skip non-existent DOM elements
					if ( ! $formElement.length) {
						continue;
					}

					// Validate form element
					if ($formElement.is(':invalid')) {
						result = that.settings.invalidFieldCallback(field, $formElement.val());
 
						if (typeof result !== 'undefined' && valid === true) {
							valid = Boolean(result);

							// Invalid, break the loop now.
							if (valid === false) {
								break;
							}
						}
 
						continue;
					}

					// Checkboxes have a boolean attribute,
					// We add 'checked="checked"' to it.
					if ($formElement.is(':checkbox')) {
						data.control[field.name] = ($formElement.attr('checked')) ? 'checked="checked"' : '';
					}

					// Selects have a 'data-selected' attribute
					// on the select element itthat
					else if ($formElement.is('select')) {
						data.control[field.name] = 'data-value="' + $formElement.val() + '"';
					}
					else {
						data.control[field.name] = $formElement.val();
						rawValue   = $formElement.val();
					}

					data.raw[field.name] = $formElement.val();
				}

				if (valid !== true) {
					return false;
				}

				// Get the template
				var $template = $(that.settings.template.selector).clone();
				$template.appendTo(that.settings.template.containerSelector);
				$template.attr('id', that.settings.itemTemplateSelector+'-'+that.randomString());

				// Parse with TempoJS
				Tempo.prepare($template.attr('id'), {
					'var_braces' : that.settings.template.varBraces,
					'tag_braces' : that.settings.template.tagBraces
				}).render(data);

				// Append
				var $templateContents = $template.children('[data-template]');
				$templateContents.appendTo(that.sortable());

				// Update values if possible
				$templateContents.find('select').each(function() {
					var value;
					if (value = $(this).attr('data-value')) {
						$(this).val(value);
						$(this).removeAttr('data-value');
					}
				});

				// Delete the DOM element
				$template.remove();

				// Wipe fields
				for (i in that.settings.fields) {
					$(that.settings.fields[i].newSelector).val('');
				}
			});

			return this;
		},

		/**
		 * Observe toggling item details
		 */
		observeToggling: function() {
			var that = this;

			// Observe toggling item details
			$('body').on('click', that.elem.selector + ' ' + that.settings.itemToggleDetailsSelector, function(e) {
				e.preventDefault();

				$(this).closest(that.settings.itemSelector)
							 .find(that.settings.itemDetailsSelector)
							 .toggleClass('show');
			});

			// Toggle all item details
			that.elem.find(that.settings.itemToggleAllDetailsSelector).on('click', function(e) {
				e.preventDefault();

				that.elem.find(that.settings.itemDetailsSelector)
								 .toggleClass('show');
			});

			return this;
		},

		/**
		 * Observe removing items
		 */
		observeRemovingItems: function() {
			var that = this;

			// Observe toggling item details
			$('body').on('click', that.elem.selector + ' ' + that.settings.itemRemoveSelector, function(e) {
				e.preventDefault();

				// Find the closest item
				var list = $(this).closest(that.settings.listSelector);

				// Find the next level of list items
				var level = list.children(that.settings.levelSelector);

				// If there is no child level,
				// Just remove this item
				if (level.length == 0) {
					return list.remove();
				}

				// Find children
				var children = level.children(that.settings.listSelector);

				// If there are no children
				if (children.length == 0) {
					return;
				}

				// Now move the children up to be
				// the next sibling of our list
				// item
				children.insertAfter(list);

				// Remove our list item
				return list.remove();
			});

			return this;
		},

		/**
		 * Observes saving the menu.
		 */
		observeSaving: function() {
			var that = this;

			// Catch submit button
			that.elem.find(':submit').on('click', function(e) {

				// Loop through the defined fields and remove
				// validation on them.
				for (i in that.settings.fields) {

					// Get some variables
					var field        = that.settings.fields[i],
					    $formElement = $(field.newSelector);

					$formElement.val('');

					if ($formElement.attr('required')) {
						$formElement.attr('data-required', 'required')
						            .removeAttr('required');
					}
				}
			});

			// Catch form submission.
			that.elem.submit(function(e) {

				// Remove the template from the DOM
				// so it's not submitted, we'll re-insert
				// after submit
				var $template = that.elem.find(that.settings.itemTemplateSelector);
				$templateClone = $template.clone();
				$template.remove();

				// If we have fancy button plugin
				if ($().button) {
					var $submitButton = that.elem.find(':submit');
				}

				// AJAX form submission
				if (that.settings.ajax === true) {
					e.preventDefault();

					var inputName = that.settings.hierarchyInputName
					       params = {};
					params[inputName] = that.sortable().nestedSortable('toHierarchy', {
						attribute: 'data-item'
					});
					var postData = $.extend($(this).find('input').serializeObject(), params);

					// AJAX call to save menu
					$.ajax({
						url        : $(this).attr('action'),
						type       : 'POST',
						data       : postData,
						beforeSend : function(jqXHR, settings) {
							if ($().button) {
								$submitButton.button('loading');
							}
						},
						success    : function(data, textStatus, jqXHR) {
							if ($().button) {
								$submitButton.button('complete');

								setTimeout(function() {
									$submitButton.button('reset');
								}, 300);
							}
						},
						error      : function(jqXHR, textStatus, errorThrown) {
							var response = $.parseJSON(jqXHR.responseText);

							alert(jqXHR.status + ' ' + errorThrown);
						}
					});

					// If we're using AJAX, put the template back in the DOM.
					// Traditional form submission doesn't need it again as
					// we're leaving the page.
					$templateClone.appendTo(that.settings.itemTemplateContainerSelector);

					// Loop through the defined fields and re-add
					// validation back in
					for (i in that.settings.fields) {

						// Get some variables
						var field        = that.settings.fields[i],
						    $formElement = $(field.newSelector);

						$formElement.val('');

						if ($formElement.attr('data-required')) {
							$formElement.attr('required', 'required')
							            .removeAttr('data-required');
						}
					}

					return false;
				}

				if ($().button) {
					$submitButton.button('loading');
				}

				// Traditional form submission
				var postData = that.sortable().nestedSortable('toHierarchy', {
					attribute: 'data-item'
				});

				// Append input to the form. It's values are JSON encoded..
				that.elem.append('<input type="hidden" name="' + that.settings.hierarchyInputName + '" value=\'' + JSON.stringify(postData) + '\'>');

				return true;
			});
		},

		randomString: function() {
			var chars = 'abcdefghiklmnopqrstuvwxyz';
			var string_length = 8;
			var randomstring = '';
			for (var i=0; i<string_length; i++) {
				var rnum = Math.floor(Math.random() * chars.length);
				randomstring += chars.substring(rnum,rnum+1);
			}
			return randomstring
		}
	}

	// The actual jquery plugin
	$.fn.nestySortable = function(settings) {
		return new NestySortable(this, settings);
	}

})(jQuery);