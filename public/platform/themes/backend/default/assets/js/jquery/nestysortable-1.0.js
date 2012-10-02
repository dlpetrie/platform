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

	// The actual jquery plugin
	$.fn.nestySortable = function(settings) {
		return new NestySortable(this, settings);
	}

})(jQuery);