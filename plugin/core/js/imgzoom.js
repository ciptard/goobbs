$(document).ready(function(){
	$('.img').hover(
		function() {
			$('img').attr('src', $(this).attr('src'))
			.css({
				'position': 'absolute',
				'top': '50%',
				'left': '50%'
			}).appendTo('body').hide().fadeIn().
			.click(function() {
				$(this).remove();
			});
		},
		function() {}
	);
});
