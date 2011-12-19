$(document).ready(function(){
	$('.img').hover(
		function() {
			if($('#zoom').length == 0) {
				$('<img id="zoom">').attr('src', $(this).attr('src'))
				.css({
					'position': 'absolute',
					'max-width': '800px',
					'max-height': '600px',
					'box-shadow': '0 0 100px #ccc'
				}).appendTo('body').hide().fadeIn()
				.click(function() {
					$('#zoom').remove();
				});
				$('#zoom').css({
					'top': ($(window).height() - $('#zoom').height())/2 + 'px',
					'left': ($(window).width() - $('#zoom').width())/2 + 'px'
				});
			}
		},
		function() {}
	);
});
