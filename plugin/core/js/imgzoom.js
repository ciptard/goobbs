$(function(){
	$('.img').click(function() {
		if($('#zoom').length == 0) {
			var offset = $(this).offset();
			$('<img id="zoom"/>').attr('src', $(this).attr('src'))
			.css({
				'position': 'absolute',
				'max-width': '800px',
				'top': offset.top,
				'left': offset.left,
				'border': '20px solid #CCC'
			}).appendTo('body').hide().fadeIn()
			.click(function() {
				$('#zoom').remove();
			});
		}
	});
});
