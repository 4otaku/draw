$(".logout").live('click', function() { 
	$.cookie('draw_settings', null, {path: '/', domain: window.location.hostname});
	$.cookie('draw_settings', null, {path: '/', domain: '.'+window.location.hostname});
	document.location.reload();
});

$(document).ready(function(){	
		
	$('form.ajax-form').die("submit").live("submit", function() {
		
		var query = '/?'+$(this).serialize();
		
		$.get(query, function(data) {
			
			switch (data) {
				case 'profile_incorrect_password':
					$(".ajax-reply").html('Неправильный пароль.');
					break;
				case 'profile_no_such_user':
					$(".ajax-reply").html('Пользователя с таким именем не существует.');
					break;
				case 'profile_passwords_dont_match':
					$(".ajax-reply").html('Введенные пароли не совпадают.');
					break;
				case 'profile_password_too_short':
					$(".ajax-reply").html('Слишком короткий пароль. минимальная длинна 6 символов.');
					break;
				case 'profile_login_too_short':
					$(".ajax-reply").html('Слишком короткий логин. минимальная длинна 6 символов.');
					break;
				case 'profile_user_already_exists':
					$(".ajax-reply").html('Пользователь с таким именем уже существует.');
					break;
				case 'profile_register_success':
				case 'profile_login_success':
					document.location.reload();
					break;
				default:
					$(".ajax-reply").html('Неизвестная ошибка, напишите администратору.');
					break;
			}
		});	
		
		return false;
	});
});
