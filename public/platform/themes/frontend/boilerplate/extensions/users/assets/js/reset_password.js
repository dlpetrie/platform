(function($) {
	var $form          = $('#password-reset-form');
	var $resetFeedback = $('.messages');

	$form.on('submit', function(e) {
		e.preventDefault();

		$resetFeedback.html('Please Wait...');
		$.ajax({
			type:     'POST',
			url:      $form.prop('action'),
			dataType: 'json',
			data:     $form.serialize(),

			success: function(data) {
				$resetFeedback.removeClass('alert-info alert-danger')
				              .addClass('alert-success')
				              .html(data.message);

				// Move on
				if (typeof data.redirect !== 'undefined') {
					window.location.href = data.redirect;
				}
				else {
					window.location.reload();
				}

				// Put the redirect message for slow net
				// connections
				setTimeout(function() {
					$resetFeedback.html($resetFeedback.data('redirecting'));
				}, 1000);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				var response = $.parseJSON(jqXHR.responseText);
				$resetFeedback.removeClass('alert-info alert-success')
				              .addClass('alert-danger')
				              .html(response.message);
			}
		});

		return false;
	});
})(jQuery);
