(function() {

	/**
	 * MenuSortable object.
	 *
	 * @todo Add more validation support for
	 *       new children...
	 */
	var MenuSortable = {

		// Settings for this instance
		settings: {

			// New child selectors
			newChildContainerSelector: '#menu-new-child',

			// Control group for children
			childControlGroupSelector: '.control-group',

			// Slug input selector
			slugInputSelector:   '.child-slug',
			uriInputSelector:    '.child-uri',
			secureInputSelector: '.child-secure',
			visibilityInputSelector:   '.child-visibility',
			targetInputSelector: '.child-target',

			// Name
			rootNameSelector: '#menu-name',

			// Root menu child slug
			rootSlug: null,

			// What should be appended
			// to the root slug when namespacing
			// child children? Should match default
			// slug separator for your application
			rootSlugAppend: '-',

			// Root selector
			rootSlugSelector: '#menu-slug',

			// New child selectors
			newChildNameSelector:       '#new-child-name',
			newChildSlugSelector:       '#new-child-slug',
			newChildUriSelector:        '#new-child-uri',
			newChildSecureSelector:     '#new-child-secure',
			newChildVisibilitySelector: '#new-child-visibility',
			newChildTargetSelector:     '#new-child-target',

			// Child selectors
			childSelector: '.child',

			// An array of slugs persisted to the
			// database already. We use to make sure
			// our slugs are unique and the user doesn't
			// get an error when saving.
			persistedSlugs: [],

			// Nesty sortable settings
			nestySortable: {

				// The selector for the sortable list,
				// used to cache the sortable list property
				sortableSelector : '.menu-children',

				// Is the form to AJAX submit or tranditional
				// submit?
				ajax             : false,

				// Selectors for DOM elements for each active
				// item.
				itemSelector              : '.child',
				itemHandleSelector        : '.child-header',
				itemToggleDetailsSelector : '.child-toggle-details',
				itemDetailsSelector       : '.child-details',
				itemRemoveSelector        : '.child-remove',

				// Invalid field callback - must return true for valid
				// field or false for invalid field.
				invalidFieldCallback : function(field, value) {
					$(field.newSelector).closest('.control-group').addClass('error');
				}
			}
		},

		/**
		 * Used to initialise a new instance of
		 * nestySortable
		 */
		init: function(elem, settings) {
			var self  = this;
			self.elem = elem;

			$.extend(true, self.settings, settings);

			// Check for nesty sortable
			if ( ! $().nestySortable) {
				$.error('$.menuSortable requires $.nestySortable');
			}

			// Check for nesty sortable settings
			if ( ! self.settings.nestySortable) {
				$.error('nestySortable configuration is required.');
			}

			// Setup Nesty sortable
			elem.nestySortable(self.settings.nestySortable);

			self.validateNewChildren()
			    .validateSlugs()
			    .helpNewName()
			    .helpSecureUris();
		},

		validateNewChildren: function() {
			var self = this;

			// Reverse the error on validation
			$(self.settings.newChildContainerSelector).find('input, textarea, select').on('focus keyup change', function(e) {

				if ($(this).is(':valid')) {
					$(this).closest(self.settings.childControlGroupSelector).removeClass('error');
				}
			});

			return this;
		},

		/**
		 * Validates the slugs.
		 *
		 * @todo See if I can make this
		 *       faster...
		 */
		validateSlugs: function() {
			var self = this;

			// Slugify slugs
			$('body').on('blur', self.elem.selector+' '+self.settings.slugInputSelector+', '+self.settings.rootSlugSelector, function() {

				$(this).val($(this).slugify())
				       .trigger('change');
			});

			$(self.settings.rootNameSelector).on('focus keyup change', function() {
				$(self.settings.rootSlugSelector).val($(this).slugify())
				                                 .trigger('change');
			});

			// New slug
			$(self.settings.rootSlugSelector).on('focus keyup change', function() {

				// Get the old slug
				var oldRootSlug = self.settings.rootSlug,
				       oldStart = oldRootSlug+self.settings.rootSlugAppend;

				// If there is a value
				if ($(this).val()) {

					// Change the new root slug
					self.settings.rootSlug = $(this).val();
					var newStart           = $(this).val()+self.settings.rootSlugAppend;

					// Change all existing children
					$.each(self.elem.find(self.settings.slugInputSelector), function() {

						// No slug at all, empty...
						if ( ! $(this).val()) {
							$(this).val(newStart);
						}

						// If the input had the old namespaced slug
						else if ($(this).val().indexOf(oldStart) === 0) {

							$(this).val($(this).val().replace(oldStart, newStart));
						}
					});
				}
			});

			// Namespace existing slugs
			$('body').on('focus keyup change', self.elem.selector+' '+self.settings.slugInputSelector, function(e) {

				if ($(this).val().indexOf(self.settings.rootSlug+self.settings.rootSlugAppend) !== 0) {
					$(this).val(self.settings.rootSlug+self.settings.rootSlugAppend+$(this).val());
				}
			});

			// On blur from a slug field, check against DB slugs
			// console.log(self.settings.persistedSlugs);
			$('body').on('blur', self.elem.selector+' '+self.settings.slugInputSelector, function(e) {

				// Temp array of taken slugs
				var takenSlugs = self.settings.persistedSlugs;

				// Add all taken slugs
				self.elem.find(self.settings.slugInputSelector).not($(this)).each(function() {
					takenSlugs.push($(this).val());
				});

				// Check our value against array
				if ($.inArray($(this).val(), takenSlugs) > -1) {
					$(this).closest(self.settings.childControlGroupSelector).addClass('error');
				}

				$(this).on('focus', function(e) {
					e.stopPropagation();
					$(this).closest(self.settings.childControlGroupSelector).removeClass('error');
				});

				console.log(takenSlugs);
			});

			// Trigger a change on the root child which
			// populates our cached root slug
			$(self.settings.rootSlugSelector).trigger('change');

			return this;
		},

		helpNewName: function() {
			var self = this;

			// Autofill the slug based on the name
			$(self.settings.newChildNameSelector).on('focus keyup change', function() {
				$(self.settings.newChildSlugSelector).val(self.settings.rootSlug+self.settings.rootSlugAppend+$(this).slugify()).trigger('change').trigger('blur');

				// And the URI
				$(self.settings.newChildUriSelector).val($(this).slugify('/')).trigger('change');
			});

			return this;
		},

		helpSecureUris: function() {
			var self = this;

			// New children
			$(self.settings.newChildUriSelector).on('focus keyup change', function(e) {

				// Full URL, disable the chekcbox
				if (self.isFullUrl($(this).val())) {
					$(self.settings.newChildSecureSelector).attr('disabled', 'disabled');
					$(self.settings.newChildSecureSelector)[self.isSecureUrl($(this).val()) ? 'attr' : 'removeAttr']('checked', 'checked');
				}

				// Relative, give option
				else {

					$(self.settings.newChildSecureSelector).removeAttr('disabled');
				}
			});

			// Existing children
			$('body').on('focus keyup change', self.elem.selector+' '+self.settings.uriInputSelector, function() {

				var $secure = $(this).closest(self.settings.childSelector).find(self.settings.secureInputSelector);

				// Full URL
				if (self.isFullUrl($(this).val())) {
					$secure.attr('disabled', 'disabled');
					$secure[self.isSecureUrl($(this).val()) ? 'attr' : 'removeAttr']('checked', 'checked');
				}

				// Relative URI
				else {
					$secure.removeAttr('disabled');
				}
			});

			return this;
		},

		/**
		 * Tests a URL to see if it's a full
		 * url
		 *
		 * @param   string  uri
		 * @return  bool
		 */
		isFullUrl: function(uri) {
			return /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/.test(uri);
		},

		/**
		 * Tests a URL to see if it's secure
		 *
		 * @param   string  uri
		 * @return  bool
		 */
		isSecureUrl: function(uri) {
			return /https:\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/.test(uri);
		}
	}

	// The actual jquery plugin
	$.fn.menuSortable = function(settings) {
		MenuSortable.init(this, settings);
	}

})(jQuery);




// 	/*
// 	|-------------------------------------
// 	| Secure menu children
// 	|-------------------------------------
// 	|
// 	| Allows for easy setup of the secure
// 	| menu children.
// 	*/
// 	$('body').on('focus keyup change', '.menu-child-uri', function(e) {


// 	});
