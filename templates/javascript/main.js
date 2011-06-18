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
		
		$.ajax({data: {
			module : 'profile',
			input: true,
			function: 'set_option',
			option_name: $(this).attr('name'),
			option_value: val,  
			success: on_complete
		}});
	}
);

$(".loginform").ready(function(){   
    $(".loginform").load('/profile/');
});

$(document).ready(function(){
	$(".checked").attr('checked', true);
	$(".not_checked").attr('checked', false);
	$("select .selected").attr('selected', 'selected');
});
