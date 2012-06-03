$(function(){
	$('.thumbnail').click(function(){
		if($(this).css('max-width') == '100%') {
			$(this).css('max-width', '320px');
		} else {
			$(this).css('max-width', '100%');
		}
	});
});
