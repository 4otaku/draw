$(".logout").live('click', function() { 
	$.cookie('draw_settings', null, {path: '/', domain: window.location.hostname});
	$.cookie('draw_settings', null, {path: '/', domain: '.'+window.location.hostname});
	document.location.reload();
});

$(document).ready(function(){		
	$("a.thickbox").each(function(){
		
		var link_href = $(this).attr('href');		
		var parts = link_href.split('=');
		var height = parseInt(parts[parts.length-1]);

		if (height + 100 > $(window).height()) {
			$(this).attr('href',link_href.replace('height='+height,'height='+($(window).height()-100)));
		}
	});

});

