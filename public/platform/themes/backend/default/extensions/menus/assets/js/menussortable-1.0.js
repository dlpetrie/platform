(function($) {

	var MenuSortable = function(element, options) {
		if (this === window) {
			return new MenuSortable(element, options);
		}

		if ( ! $().nestySortable) {
			$.error('$.menuSortable requires $.nestySortable');
		}
		if ( ! $().slugify) {
			$.error('$.menuSortable requires $.slugify');
		}

		var that = this;

		that.options = {

			// Namespace for the plugin for registering
			// dom events
			namespace: 'menusortable',

			// Selector for control groups that wrap inputs
			controlGroupSelector : '.control-group',

			// Menu name
			rootNameSelector : '#menu-name',

			// Slug options
			slugs: {

				// An array of slugs persisted to the
				// database already. We use to make sure
				// our slugs are unique and the user doesn't
				// get an error when saving.
				persisted: [],

				rootSelector : '#menu-slug',

				// Root value
				get root() {
					if (this._root === null) {
						var value = $(this.rootSelector).val();
						this._root = value;
					}
					
					return this._root;
				},
				set root(value) {
					this._root = value;
				},
				_root         : null,

				// New selector
				get newSelector() {
					if (this._newSelector === null) {

						var selector = null;

						if (selector = that.options.nestySortable.fields.slug.newSelector) {
							this._newSelector = selector;
						} else {
							this._newSelector = '#new-child-slug';
						}
					}

					return this._newSelector;
				},
				set newSelector(value) {
					this._newSelector = value;
				},
				_newSelector : null,

				// Separator
				separator    : '-'
			},

			// Children
			children: {
				toggleSelector: '.child-toggle-details',
			},

			// Nesty sortable default settings
			nestySortable: {

				// Namespace for the plugin for registering
				// dom events
				namespace: 'menusortablenestysortable',

				// The selector for the sortable list,
				// used to cache the sortable list property
				sortableSelector    : '.menu-children',
				itemSelector        : '.child',
				itemDetailsSelector : '.child-details',
				itemRemoveSelector  : '.child-remove',
				itemAddSelector     : '.children-add-new',

				// Invalid field callback - must return true for valid
				// field or false for invalid field.
				invalidFieldCallback : function(slug, field, value) {
					$(field.newSelector).closest('.control-group').addClass('error');
				},

				// This is the selector for the new item's template.
				// This container should be hidden at all times as we
				// clone the HTML inside of this, apply the template and
				// then attach that to the end of the list.
				template : {
					containerSelector : '.new-child-template-container',
					selector          : '.new-child-template'
				},

				// The input name for the items
				// hierarchy that's posted to
				// the server.
				hierarchyInputName: 'children_hierarchy'
			}
		}

		$.extend(true, that.options, options);

		that.$element = element;

		return this.setupNestySortable()
		           .validateSlugs()
		           .checkUris()
		           .toggleChildren();
	}

	MenuSortable.prototype = {

		setupNestySortable: function() {
			var that = this,
			      ns = this.options.namespace;

			that.$element.nestySortable(that.options.nestySortable);

			return this;
		},

		validateSlugs: function() {
			var that  = this,
			      ns  = this.options.namespace,
			separator = this.options.slugs.separator;

			function slugPrepend(slug) {
				if (typeof slug === 'undefined') {
					slug = that.options.slugs.root;
				}

				if (slug) {
					return slug+separator;
				}

				return null;
			}

			function slugify(str, separator) {
				if (typeof separator === 'undefined') {
					separator = '-';
				}

				str = str.replace(/^\s+|\s+$/g, ''); // trim
				str = str.toLowerCase();
				
				// remove accents, swap ñ for n, etc
				var from = "ĺěščřžýťňďàáäâèéëêìíïîòóöôùůúüûñç·/_,:;";
				var to   = "lescrzytndaaaaeeeeiiiioooouuuuunc------";
				for (var i=0, l=from.length ; i<l ; i++) {
					str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
				}

				return str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
				          .replace(/\s+/g, separator) // collapse whitespace and replace by _
				          .replace(/-+/g, separator) // collapse dashes
				          .replace(new RegExp(separator+'+$'), '') // Trim separator from start
				          .replace(new RegExp('^'+separator+'+'), ''); // Trim separator from end
			}

			// Slug update events
			that.$element.bind('root_slug_update.'+ns, function(event, value) {

				if (typeof value === 'undefined') {
					value = $(that.options.rootNameSelector).val();
				}

				if (value) {
					var slug;
					if (slug = slugify(value, separator)) {
						$(that.options.slugs.rootSelector).val(slug);
						$(that.options.slugs.newSelector).val(slugPrepend(slug));
						that.options.slugs.root = slug;
					}
				}
			});

			that.$element.bind('new_slug_update.'+ns, function(event, value) {

				if (typeof value === 'undefined') {
					value = $(that.options.nestySortable.fields.name.newSelector).val();
				}

				if (value) {
					var slug, prepend;
					if ((slug = slugify(value)) && (prepend = slugPrepend())) {
						var value = prepend+slug,
						     $dom = $(that.options.slugs.newSelector);
						$dom.val(value);

						// Trigger a validation of the new slug
						that.$element.trigger('new_slug_validate.'+ns, [$dom, value]);
					}
				}
			});
			that.$element.bind('new_uri_update.'+ns, function(event, value) {
				
				if (typeof value === 'undefined') {
					value = $(that.options.nestySortable.fields.name.newSelector).val();
				}

				if (value) {
					var slug,
					    prepend,
					    uriSeparator = '/';
					if ((slug = slugify(value, uriSeparator)) && (prepend = slugPrepend())) {
						prepend = prepend.replace(separator, uriSeparator);
						$(that.options.nestySortable.fields.uri.newSelector).val(prepend+slug)
						    .trigger('blur')
					}
				}

			});
			that.$element.bind('new_slug_validate.'+ns, function(event, dom, value) {
				if (typeof dom === 'undefined') {
					dom = $(that.options.slugs.newSelector);
				}
				if (typeof value === 'undefined') {
					value = dom.val();
				}

				// Add / remove validation error
				if (($.inArray(value, that.options.slugs.persisted) > -1)) {
					dom.addClass('error')
					    .closest(that.options.controlGroupSelector).addClass('error');
				} else {
					dom.removeClass('error')
					    .closest(that.options.controlGroupSelector).removeClass('error');
				}

			});

			// When the person changes the menu slug
			$(that.options.rootNameSelector).on('blur', function() {
				that.$element.trigger('root_slug_update.'+ns);
			});

			// Lastly, on load, trigger an update event
			that.$element.trigger('root_slug_update.'+ns);

			// // Loop through fields and build list of selectors
			// var selectors    = [],
			//     allowedTypes = 'search tel url email datetime date month week time datetime-local number range color'.split(' ');

			// // Loop through selectors and observe them. Build a nice slug
			// // accordingly.
			// $.each(that.options.nestySortable.fields, function(fieldSlug, field) {

			// 	// Skip non allowed field slugs
			// 	var nonAllowedSlugs = ['slug'];
			// 	if ($.inArray(fieldSlug, nonAllowedSlugs) > -1) {
			// 		return;
			// 	}

			// 	var $dom = $(field.newSelector);
			// 	if ($dom.is(function() {

			// 		// Check input types
			// 		if ((this.nodeName.toLowerCase() !== 'input') || ($.inArray($(this).attr('type'), allowedTypes) > -1)) {
			// 			return false;
			// 		}

			// 		return true;
			// 	} )) {
			// 		selectors.push(field.newSelector);
			// 	}
			// });
			// $(selectors.join(', ')).on('blur', function() {
			// 	that.$element.trigger('new_slug_update.'+ns)
			// 	             .trigger('new_uri_update.'+ns);
			// });
			
			// New child names
			$(that.options.nestySortable.fields.name.newSelector).on('blur.'+ns, function() {
				var value = $(this).val();
				that.$element.trigger('new_slug_update.'+ns, [value])
				             .trigger('new_uri_update.'+ns, [value]);
			});

			// // Existing names
			// $('body').on('blur.'+ns, that.options.nestySortable.fields.name.itemSelector, function() {
				
			// });

			// When the person blurs on a slug
			$(that.options.slugs.newSelector).on('blur', function() {
				that.$element.trigger('new_slug_validate.'+ns, [$(this), $(this).val()]);
			});

			return this;
		},

		checkUris: function() {
			var that = this,
			      ns = this.options.namespace;

			function isFullUrl(url) {
				return /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/.test(url);
			}
			function isSecureUrl(url) {
				return /https:\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/.test(url);
			}

			// Check new children
			var $secure = $(that.options.nestySortable.fields.secure.newSelector);
			if ($secure.length) {
				$(that.options.nestySortable.fields.uri.newSelector).on('blur.'+ns, function(e) {
					var value = $(this).val();

					if (isFullUrl(value)) {
						$secure.attr('disabled', 'disabled')
						       [(isSecureUrl(value)) ? 'attr' : 'removeAttr']('checked', 'checked');
					} else {
						$secure.removeAttr('disabled')
						       .removeAttr('checked');
					}
				});
			}

			// Existing
			$('body').on('blur.'+ns, that.options.nestySortable.fields.uri.itemSelector, function() {
				var $child = $(this).closest(that.options.nestySortable.itemSelector),
				   $secure = $child.find(that.options.nestySortable.fields.secure.itemSelector),
				     value = $(this).val();

				if ($secure.length) {
					if (isFullUrl(value)) {
						$secure.attr('disabled', 'disabled')
						       [(isSecureUrl(value)) ? 'attr' : 'removeAttr']('checked', 'checked');
					} else {
						$secure.removeAttr('disabled')
						       .removeAttr('checked');
					}
				}
			});

			return this;
		},

		toggleChildren: function() {
			var that = this,
			      ns = this.options.namespace;

			// Live toggle
			$('body').on('click.'+ns, that.options.children.toggleSelector, function(e) {
				$(this).closest(that.options.nestySortable.itemSelector).find(that.options.nestySortable.itemDetailsSelector).toggleClass('show');
			});

			return this;
		}
	}
	

	// The actual jquery plugin
	$.fn.menuSortable = function(options) {
		return new MenuSortable(this, options);
	}

})(jQuery);
