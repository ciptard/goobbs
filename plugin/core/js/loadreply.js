$(document).ready(function(){
	$('a[href^="#"]').hover(
		function(e) {
			var target = $($(this).attr('href') + ' td:eq(1) p:eq(0)');
			$('<div id="reply" class="box"></div>').html($(target).length > 0? $(target).html() : 'nothing.')
			.css({
				'width': '600px',
				'background-color': '#FFF',
				'position': 'absolute',
				'top': e.pageY+20,
				'left': e.pageX+20
			})
			.hide().appendTo('body').fadeIn();
		},
		function() {
			$('#reply').remove();
		}
	).
	mousemove(function(e) {
		$('#reply').css({
			'top': e.pageY+20,
			'left': e.pageX+20
		})
	});
});
