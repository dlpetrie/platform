(function() {
	
	/**
	 * NestySortable object.
	 *
	 * @todo Add more validation support for
	 *       new items...
	 */
	var NestySortable = {

		// Settings for this instance
		settings: {

			// The selector for the sortable list,
			// used to cache the sortable list property
			sortableSelector: null,

			// Is the form to AJAX submit or tranditional
			// submit?
			ajax: false,

			/**
			 * An array of fields for the Nesty sortable.
			 *
			 * <code>
			 *		{ name : 'my_field', newSelector : '.new-item-my-field', required : false }
			 * </code>
			 *
			 * @var array
			 */
			fields : [],

			// Selectors relating to items, but aren't
			// located inside each item in the DOM
			itemAddButtonSelector        : '.items-add-new',
			itemToggleAllDetailsSelector : '.items-toggle-all',

			// JSON encoded string for new item template
			itemTemplate: '',

			// This is the selector for the new item's template.
			// This container should be hidden at all times as we
			// clone the HTML inside of this, apply the template and
			// then attach that to the end of the list.
			itemTemplateContainerSelector : '.items-new-template-container',
			itemTemplateSelector          : '.items-new-template',

			// The ID of the last item added. Used so we fill
			// new templates with an ID that won't clash with existing
			// items.
			lastItemId: 0,

			// Selectors for DOM elements for each active
			// item.
			itemSelector              : '.item',
			itemHandleSelector        : '.item-header',
			itemToggleDetailsSelector : '.item-toggle-details',
			itemDetailsSelector       : '.item-details',
			itemRemoveSelector        : '.item-remove',

			// Invalid field callback - must return true for valid
			// field or false for invalid field.
			invalidFieldCallback : function(field, value) {},

			// The input name for the items
			// hierarchy that's posted to
			// the server.
			hierarchyInputName: 'items_hierarchy',

			// Misc
			maxLevels     : 0,
			tabSize       : 20,
			levelSelector : 'ol',
			listSelector  : 'li',
		},

		// The form object we call $.nestySortable on
		elem: null,

		// The sortable list DOM object, cached
		// for speed
		_sortable: null,

		// The selector for an item add button
		_itemAddButton: null,

		/**
		 * Used to initialise a new instance of
		 * nestySortable
		 */
		init: function(elem, settings) {
			var self  = this;
			self.elem = elem;

			$.extend(true, self.settings, settings);

			// Check for NestedSortable
			if ( ! $().nestedSortable) {
				$.error('$.nestySortable requires $.nestedSortable');
			}

			if ( ! window.Tempo) {
				$.error('$.nestySortable required TempoJS');
			}

			// Initialise NestedSortable
			self.sortable().nestedSortable({
				disableNesting       : 'no-nest',
				forcePlaceholderSize : true,
				handle               : self.settings.itemHandleSelector,
				helper               :'clone',
				items                : self.settings.listSelector,
				maxLevels            : self.settings.maxLevels,
				opacity              : 0.6,
				placeholder          : 'placeholder',
				revert               : 250,
				tabSize              : self.settings.tabSize,
				tolerance            : 'pointer',
				toleranceElement     : '> div'
			});

			// Observers
			self.observeAddingItems()
					.observeToggling()
					.observeRemovingItems()
					.observeSaving();
		},

		/**
		 * Used to retrieve the sortable DOM object.
		 */
		sortable: function() {
			var self = this;

			if ( ! self._sortable) {
				self._sortable = self.elem.find(self.settings.sortableSelector);
			}

			return self._sortable;
		},

		/**
		 * Used to retrieve the add button DOM object.
		 */
		itemAddButton: function() {
			var self = this;

			if ( ! self._itemAddButton) {
				self._itemAddButton = self.elem.find(self.settings.itemAddButtonSelector);
			}

			return self._itemAddButton;
		},

		/**
		 * Observe adding items.
		 */
		observeAddingItems: function() {
			var self = this;

			// When user clicks on the add item button
			self.itemAddButton().on('click', function(e) {
				e.preventDefault();

				// Get the item template
				var itemTemplate = self.settings.itemTemplate;

				// Select Fields
				var selectFields = {};

				// Flag for valid itemTemplate
				var valid = true;

				var itemId               = self.settings.lastItemId + 1;
				self.settings.lastItemId = itemId;

				// Data for templat
				var data = {
					id      : itemId,
					control : {

					}
				};

				// Loop through the defined fields, and replace
				// the template variables with the value of each
				// field's selector.
				for (i in self.settings.fields) {

					// Get some variables
					var field        = self.settings.fields[i],
					    $formElement = $(field.newSelector);

					// Skip non-existent DOM elements
					if ( ! $formElement.length) {
						continue;
					}

					// Validate form element
					if ($formElement.is(':invalid')) {
						result = self.settings.invalidFieldCallback(field, $formElement.val());
 
						if (typeof result !== 'indefined' && valid === true) {
							valid = Boolean(result);
						}
 
						continue;
					}

					// Checkboxes have a boolean attribute,
					// We add 'checked="checked"' to it.
					if ($formElement.is(':checkbox')) {
						data.control[field.name] = ($formElement.attr('checked')) ? 'checked="checked"' : '';
					}

					// Selects have a 'data-selected' attribute
					// on the select element itself
					else if ($formElement.is('select')) {
						data.control[field.name] = 'data-value="' + $formElement.val() + '"';
					}
					else {
						data.control[field.name] = $formElement.val();
						rawValue   = $formElement.val();
					}
				}

				if (valid !== true) {
					return false;
				}

				// Get the template
				var $template = $(self.settings.itemTemplateSelector).clone();
				$template.appendTo(self.settings.itemTemplateContainerSelector);
				$template.attr('id', self.settings.itemTemplateSelector+'-'+self.randomString());

				// Parse with TempoJS
				Tempo.prepare($template.attr('id'), {
					'var_braces' : '\\[\\%\\%\\]',
					'tag_braces' : '\\[\\?\\?\\]'
				}).render(data);

				// Append
				$template.children('[data-template]').appendTo(self.sortable());

				// Delete the DOM element
				$template.remove();

				// Wipe fields
				for (i in self.settings.fields) {
					$(self.settings.fields[i].newSelector).val('');
				}
			});

			return this;
		},

		/**
		 * Observe toggling item details
		 */
		observeToggling: function() {
			var self = this;

			// Observe toggling item details
			$('body').on('click', self.elem.selector + ' ' + self.settings.itemToggleDetailsSelector, function(e) {
				e.preventDefault();

				$(this).closest(self.settings.itemSelector)
							 .find(self.settings.itemDetailsSelector)
							 .toggleClass('show');
			});

			// Toggle all item details
			self.elem.find(self.settings.itemToggleAllDetailsSelector).on('click', function(e) {
				e.preventDefault();

				self.elem.find(self.settings.itemDetailsSelector)
								 .toggleClass('show');
			});

			return this;
		},

		/**
		 * Observe removing items
		 */
		observeRemovingItems: function() {
			var self = this;

			// Observe toggling item details
			$('body').on('click', self.elem.selector + ' ' + self.settings.itemRemoveSelector, function(e) {
				e.preventDefault();

				// Find the closest item
				var list = $(this).closest(self.settings.listSelector);

				// Find the next level of list items
				var level = list.children(self.settings.levelSelector);

				// If there is no child level,
				// Just remove this item
				if (level.length == 0) {
					return list.remove();
				}

				// Find children
				var children = level.children(self.settings.listSelector);

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
			var self = this;

			// Catch form submission.
			self.elem.submit(function(e) {

				// AJAX form submission
				if (self.settings.ajax === true) {
					e.preventDefault();

					var inputName = self.settings.hierarchyInputName,
					     postData = $.extend($(this).find('input').serializeObject(), {
						inputName : self.sortable().nestedSortable('toHierarchy', {
							attribute: 'data-item'
						})
					});

					// AJAX call to save menu
					$.ajax({
						url      : $(this).attr('action'),
						type     : 'POST',
						dataType : 'json',
						data     : postData,
						success  : function(data, textStatus, jqXHR) {
							if (data.status) {
								
							}
						},
						error    : function(jqXHR, textStatus, errorThrown) {
							alert(jqXHR.status + ' ' + errorThrown);
						}
					});

					return false;
				}

				// Traditional form submission
				var postData = self.sortable().nestedSortable('toHierarchy', {
					attribute: 'data-item'
				});

				// Append input to the form. It's values are JSON encoded..
				self.elem.append('<input type="hidden" name="' + self.settings.hierarchyInputName + '" value=\'' + JSON.stringify(postData) + '\'>');

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
		NestySortable.init(this, settings);
	}

})(jQuery);