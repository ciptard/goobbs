$(function(){
	$('.thumbnail').click(function(e) {
		if($('#zoom').length == 0) {
			$('<img id="zoom"/>').attr('src', $(this).attr('src'))
			.css({
				'position': 'absolute',
				'max-width': '800px',
				'top': e.pageY,
				'left': e.pageX
			}).appendTo('body').hide().fadeIn()
			.click(function() {
				$('#zoom').remove();
			});
		}
	});
});
