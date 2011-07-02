$(document).ready(function(){  	

	$(".bbholder img.bb").easyTooltip();
	
	$('.bbholder img.bb').click(function() {
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
		if ($(this).attr('rel') != 'img') insert(start, end, 'textfield');
		else $("#textfield").val($("#textfield").val() + start + getimage + end);
		return false;
	});	

});
