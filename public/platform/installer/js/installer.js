$(document).ready(function() {

	/*
	|-------------------------------------
	| Common
	|-------------------------------------
	|
	| Common Javascript among multiple steps
	|
	*/

	// Disabled continue button
	$('#continue-btn.disabled').on('click.disabled', function(e) {
		e.preventDefault();
		return false;
	});

	/*
	|-------------------------------------
	| Installer Preparation
	|-------------------------------------
	|
	| The first step of the installer is
	| the preparation step. We need to check
	| the system is compatible and permissions
	| are correct.
	|
	*/

	// Look for permissions Tempo template
	// DOM elements.
	if ($('#permissions-pass').length && $('#permissions-fail').length) {

		// Setup the tepo templates.
		var permissionsPass = Tempo.prepare('permissions-pass', {
			'var_braces' : '\\[\\%\\%\\]',
			'tag_braces' : '\\[\\?\\?\\]'
		});
		var permissionsFail = Tempo.prepare('permissions-fail', {
			'var_braces' : '\\[\\%\\%\\]',
			'tag_braces' : '\\[\\?\\?\\]'
		});

		// Attach a $.data() reference to setInterval()
		$('body').data('passInterval', setInterval(function() {

			// Trigger a check
			$('body').trigger('checkPassInterval');
		}, 1000));

		// Bind the check
		$('body').bind('checkPassInterval', function() {
			$.getJSON(platform.url.base('installer/permissions'), function(data) {
				permissionsPass.render(data.pass);
				permissionsFail.render(data.fail);

				// If there's no more errors
				if (data.fail.length == 0) {
					$('#continue-btn').removeClass('disabled')
					                  .off('click.disabled');
					clearInterval($('body').data('passInterval'));
				}
			});
		});

		// Trigger a check immediately on body load
		$('body').trigger('checkPassInterval');
	}

	/*
	|-------------------------------------
	| FTP Check
	|-------------------------------------
	|
	*/
	$('#ftp-test').on('click', function(e) {
		e.preventDefault();

		$.ajax({
			type     : 'POST',
			url      : platform.url.base('installer/ftp_test'),
			async    : false,
			data     : $('#filesystem-form').serialize(),
			dataType : 'JSON',
			success  : function(data) {
				if (data.connected) {
					$('#ftp-status').text('Connected.');
				}
				else {
					$('#ftp-status').text('Could not connect.');
				}
			}
		});
	});

	/*
	|-------------------------------------
	| Database form
	|-------------------------------------
	|
	| When the user has filled out all
	| inputs in the database form, we do
	| an ajax call to check the database
	| credentials before allowing them to
	| continue with the install process.
	|
	*/

	$('.messages').html('Awaiting Credentials');

	/**
	 * Function to check database credentials.
	 *
	 * @return  bool
	 */
	var checkDBCredentials = function() {

		// By default, we don't succesd
		var dbPass = false;

		length = $('#database-form').find('select, input:not([type=password], [type=checkbox])').filter(function()
		{
			return $(this).val() == '';
		}).length;

		if (length == 0)
		{
			$.ajax({
				type     : 'POST',
				url      : platform.url.base('installer/confirm_db'),
				async    : false,
				data     : $('#database-form').serialize(),
				dataType : 'JSON',
				success  : function(data, textStatus, jqXHR) {

					// Show success message and enable continue button
					$('.messages').html(data.message)
					              .removeClass('alert-error')
					              .addClass('alert-success')
					              .show();

					dbPass = true;
				},
				error    : function(jqXHR, textStatus, errorThrown) {

					// Don't know, this fixes some
					// dumb alert
					if (jqXHR.status != 0) {
						$('.messages').html($.parseJSON(jqXHR.responseText).message)
						              .removeClass('alert-success')
						              .addClass('alert-error');
						console.log(jqXHR);

					}
				}
			});
		}
		else
		{
			$('.messages')
				.removeClass('alert-success')
				.removeClass('alert-error')
				.addClass('alert')
				.html('Awaiting Credentials');
		}

		return dbPass;
	}

	// Check we have a database form present
	if ($('#database-form').length) {

		$('#database-form').find('select, input').on('focus keyup change', function(e) {

			// Clear the timeout on keyup
			// this stops multiple AJAX calls bubbling up
			if (typeof(checkDBTimer) != 'undefined') {
				clearTimeout(checkDBTimer);
			}

			// Check keycode - enter
			// shouldn't trigger it
			if (e.keyCode === 13) {
				return;
			}

			// Set a new timer
			checkDBTimer = setTimeout(function() {
				$('#database-form button:submit')[(checkDBCredentials()) ? 'removeAttr' : 'attr']('disabled', 'disabled');
			}, 500);

		});
	}

});
