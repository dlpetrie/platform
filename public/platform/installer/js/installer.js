$(document).ready(function() {

	if ($('#permissions-pass').length && $('#permissions-fail').length) {

		// Setup the tepo templates.
		var permissionsPass = Tempo.prepare('permissions-pass', {
			'var_braces' : '\\[\\[\\]\\]',
			'tag_braces' : '\\[\\?\\?\\]'
		});
		var permissionsFail = Tempo.prepare('permissions-fail', {
			'var_braces' : '\\[\\[\\]\\]',
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
					$('#continue-btn').removeAttr('disabled');
					clearInterval($('body').data('passInterval'));
				}
			});
		});

		// Trigger a check immediately on body load
		$('body').trigger('checkPassInterval');
	}

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
	*/

	$('.messages').html('Awaiting Credentials');

	var checkDisclaimer = function()
	{
		return $('#disclaimer').is(':checked');
	}

	var checkDBCredentials = function() {

		var db_pass = false;

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
					                [data.error ? 'addClass' : 'removeClass']('alert-error')
					                [data.error ? 'removeClass' : 'addClass']('alert-success')
					                .show();

					db_pass = ! data.error;

					// $('#database-form button:submit')[data.error ? 'attr' : 'removeAttr']('disabled', 'disabled');
				},
				error    : function(jqXHR, textStatus, errorThrown) {
					db_pass = false;
					// Don't know
					if (jqXHR.status != 0) {
						alert(jqXHR.status + ' ' + errorThrown);
					}
				}
			});
		}
		else
		{
			db_pass = false;

			$('.messages')
				.removeClass('alert-success')
				.removeClass('alert-error')
				.addClass('alert')
				.html('Awaiting Credentials');

			$('#database-form button:submit').attr('disabled', 'disabled');
		}

		return db_pass;
	}

	if ($('.step1-refresh').length)
	{
		var $files = $('.files code');

		$('.step1-refresh').on('click', function(e) {
			e.preventDefault();

			$.ajax({
				type     : 'POST',
				url      : platform.url.base('installer/confirm_writable'),
				data     : $('#writable-form').serialize(),
				dataType : 'JSON',
				success  : function(data, textStatus, jqXHR) {
					var i = 0;
					var enabled = true;
					$.each(data, function(idx, val) {
						file = $($files[i]);
						if (val) {
							file.removeClass('alert-error').addClass('alert-success');
						}
						else {
							file.removeClass('alert-success').addClass('alert-error');
							enabled = false;
						}
						i++;
					});

					if (enabled) {
						$('#writable-form button:submit').removeAttr('disabled');
					}
					else {
						$('#writable-form button:submit').attr('disabled', 'disabled');
					}
				},
				error    : function(jqXHR, textStatus, errorThrown) {

				}
		});
		})
	}

	if ($('#database-form').length)
	{
		db = checkDBCredentials();

		$('#database-form').find('select, input').on('focus keyup change', function(e) {

			if (typeof(checkDBTimer) != "undefined") {
				clearTimeout(checkDBTimer);
			}

			// Check keycode - enter
			// shouldn't trigger it
			if (e.keyCode === 13) {
				return;
			}

			checkDBTimer = setTimeout(function() {
				if (checkDBCredentials() && checkDisclaimer()) {
					$('#database-form button:submit').removeAttr('disabled', 'disabled');
				}
				else {
					$('#database-form button:submit').attr('disabled', 'disabled');
				}
			}, 1000);

		});
	}

});
