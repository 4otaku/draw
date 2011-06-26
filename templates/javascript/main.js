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
	
	$(".edit_description button.show_form").click(function(){
		$(".edit_field").toggle();
	});
	
	$(".edit_description button.save_form").click(function(){
		var val; var on_complete;

		val = $(".edit_field textarea").val();

		on_complete = function() {
			document.location.reload();
		};

		$.ajax({
			'data': {
				'module' : 'description',
				'input': true,
				'function': 'edit',
				'type': $(".edit_type_information").val(),
				'id': $(".edit_id_information").val(),
				'text': val
			},
			'success': on_complete
		});
	});
	
	$(".description_bbholder img.bb").easyTooltip();
	
	$('.description_bbholder img.bb').click(function() {
		var getimage='';var urltext='';var attribs='';
		if ($(this).attr('rel') == 'url') {								
			attribs = prompt('Адрес ссылки, полностью', 'http://');
			if (attribs == '' || attribs == null) return false;
			else attribs = '='+attribs;
			if (attribs == "=null") return false;			
			element = document.getElementById('textfield');
			if (element.selectionStart == element.selectionEnd) {
				urltext = prompt('Текст ссылки', '');
				if (urltext == null) return false;
			}			
		}
		if ($(this).attr('rel') == 'img') {
			getimage = prompt('Адрес картинки, полностью', 'http://');
			if (getimage == '' || getimage == null) return false;
			attribs = '='+prompt('Уменьшить пропорционально до ширины, в пикселях', '400');
			if (attribs == "=null") return false;			
		}
		if ($(this).attr('rel') == 'spoiler') {
			attribs = '='+prompt('Заголовок для спойлера', '');
			if (attribs == "=null") return false;
		}
		var start = '['+$(this).attr('rel')+attribs+']'+urltext;
		var end = '[/'+$(this).attr('rel')+']';
		if ($(this).attr('rel') != 'img') insert(start, end, 'description_textfield');
		else $("#description_textfield").val($("#description_textfield").val() + start + getimage + end);
		return false;
	});		
});
