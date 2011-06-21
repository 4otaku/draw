$(".logout").live('click', function() { 
	$.cookie('beta_settings', null, {path: '/', domain: window.location.hostname});
	$.cookie('beta_settings', null, {path: '/', domain: '.'+window.location.hostname});
	document.location.reload();
});

$(document).ready(function(){	
	var forms = {
		message: null,
		init: function (type) {
			$("."+type).click(function() { 

				$.get("/templates/ajax/"+type+".html", function(data){
					$(data).modal({
						closeHTML: "<a href='#' title='Закрыть' class='modal-close'>x</a>",
						position: ["15%",],
						overlayId: 'form-overlay',
						containerId: 'form-container',
						onOpen: this.open,
						onShow: this.show,
						onClose: this.close
					});
				});
			});
		},
		open: function (dialog) {
			var title = $('#form-container .form-title').html();
			$('#form-container .form-title').html('Loading...');
			dialog.overlay.fadeIn(200, function () {
				dialog.container.fadeIn(200, function () {
					dialog.data.fadeIn(200, function () {
						$('#form-container .form-content').animate({
							height: h
						}, function () {
							$('#form-container .form-title').html(title);
							$('#form-container form').fadeIn(200, function () {
								$('#form-container #form-name').focus();

								$('#form-container .form-cc').click(function () {
									var cc = $('#form-container #form-cc');
									cc.is(':checked') ? cc.attr('checked', '') : cc.attr('checked', 'checked');
								});
							});
						});
					});
				});
			});
		},
		show: function (dialog) {
			$('#form-container .form-send').click(function (e) {
				e.preventDefault();
				if (this.validate()) {
					var msg = $('#form-container .form-message');
					msg.fadeOut(function () {
						msg.removeClass('contact-error').empty();
					});
					$('#form-container .form-title').html('Sending...');
					$('#form-container form').fadeOut(200);
					$('#form-container .form-content').animate({
						height: '80px'
					}, function () {
						$('#form-container .form-loading').fadeIn(200, function () {
							$.ajax({
								url: 'data/contact.php',
								data: $('#form-container form').serialize() + '&action=send',
								type: 'post',
								cache: false,
								dataType: 'html',
								success: function (data) {
									$('#form-container .form-loading').fadeOut(200, function () {
										$('#form-container .form-title').html('Thank you!');
										msg.html(data).fadeIn(200);
									});
								},
								error: this.error
							});
						});
					});
				}
				else {
					if ($('#form-container .form-message:visible').length > 0) {
						var msg = $('#form-container .form-message div');
						msg.fadeOut(200, function () {
							msg.empty();
							this.showError();
							msg.fadeIn(200);
						});
					}
					else {
						$('#form-container .form-message').animate({
							height: '30px'
						}, this.showError);
					}
					
				}
			});
		},
		close: function (dialog) {
			$('#form-container .form-message').fadeOut();
			$('#form-container .form-title').html('Goodbye...');
			$('#form-container form').fadeOut(200);
			$('#form-container .form-content').animate({
				height: 40
			}, function () {
				dialog.data.fadeOut(200, function () {
					dialog.container.fadeOut(200, function () {
						dialog.overlay.fadeOut(200, function () {
							$.modal.close();
						});
					});
				});
			});
		},
		error: function (xhr) {
			alert(xhr.statusText);
		},
		validate: function () {
			this.message = '';
			if (!$('#form-container #form-name').val()) {
				this.message += 'Name is required. ';
			}

			var email = $('#form-container #form-email').val();
			if (!email) {
				this.message += 'Email is required. ';
			}
			else {
				if (!this.validateEmail(email)) {
					this.message += 'Email is invalid. ';
				}
			}

			if (!$('#form-container #form-message').val()) {
				this.message += 'Message is required.';
			}

			if (this.message.length > 0) {
				return false;
			}
			else {
				return true;
			}
		},
		validateEmail: function (email) {
			var at = email.lastIndexOf("@");

			if (at < 1 || (at + 1) === email.length)
				return false;

			if (/(\.{2,})/.test(email))
				return false;

			var local = email.substring(0, at);
			var domain = email.substring(at + 1);

			if (local.length < 1 || local.length > 64 || domain.length < 4 || domain.length > 255)
				return false;

			if (/(^\.|\.$)/.test(local) || /(^\.|\.$)/.test(domain))
				return false;

			if (!/^"(.+)"$/.test(local)) {
				if (!/^[-a-zA-Z0-9!#$%*\/?|^{}`~&'+=_\.]*$/.test(local))
					return false;
			}

			if (!/^[-a-zA-Z0-9\.]*$/.test(domain) || domain.indexOf(".") === -1)
				return false;	

			return true;
		},
		showError: function () {
			$('#form-container .form-message')
				.html($('<div class="contact-error"></div>').append(this.message))
				.fadeIn(200);
		}
	};
	
	forms.init('login');
	forms.init('register');

});

