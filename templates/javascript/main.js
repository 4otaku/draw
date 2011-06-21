$.ajaxSetup({
	url: "/",
	global: false,
	type: "POST",
	cache: false,
	data: {
		cookie: $.cookie('draw_settings')
	}
});

$(".disabled").live('click',function(event){
	event.preventDefault();
});

$(".config_option input, .config_option select").live(
	'change', function() {
		var val; var on_complete;

		if ($(this).is(':checkbox')) {
			val = $(this).is(':checked') + 0;
		} else {
			val = $(this).val();
		}

		if ($(this).is('.reload')) {
			on_complete = function() {
				document.location.reload();
			};
		} else {
			on_complete = function() {};
		}

		$.ajax({
			'data': {
				'module' : 'profile',
				'input': true,
				'function': 'set_option',
				'option_name': $(this).attr('name'),
				'option_value': val
			},
			'success': on_complete
		});
	}
);

$(".loginform").ready(function(){
    $(".loginform").load('/profile/');
});

$(document).ready(function(){
	$(".checked").attr('checked', true);
	$(".not_checked").attr('checked', false);
	$("select .selected").attr('selected', 'selected');

	if ($("div#comments-form").length == 1) {
		$("div#comments-form").load("/templates/ajax/comment_form.html", function(){
			if (document.location.hash.indexOf("#reply-") == 0) {
				var id = parseInt(document.location.hash.replace("#reply-",""));
				if ($('#reply-'+id).length == 1) {
					$('#reply-'+id).append($('#comments-field'));
					$("#comment-parent").val(id);
					$("#comment-main").show();
					$(".commentsh2").hide();
				}
			}

			var split_url = window.location.pathname.split('/');
			$("input[name=place]").val(split_url[1]);
			$("input[name=item_id]").val(split_url[2]);
		});
	}
	
	$("a.reply").click(function(event){  
		event.preventDefault();
		$('#reply-'+$(this).attr('rel')).append($('#comments-form'));
		$("#comment-parent").val($(this).attr('rel'));	
		$("#comment-main").show();
		$(".commentsh2").hide();
	});		
});
