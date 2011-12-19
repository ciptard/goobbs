$(document).ready(function(){
	$('.img').click(function() {
		if($('#zoom').length == 0) {
			var offset = $(this).offset();
			$('<img id="zoom"/>').attr('src', $(this).attr('src'))
			.css({
				'position': 'absolute',
				'max-width': '800px',
				'top': offset.top,
				'left': offset.left,
				'box-shadow': '0 0 100px #777'
			}).appendTo('body').hide().fadeIn()
			.click(function() {
				$('#zoom').remove();
			});
		}
	});
});
