$(function(){
	$('a.quote[href]').hover(
		function(e) {
			var link = $(this).attr('href').split('#');
			var target = link[0] + ' #' + link[1] + ' .content';
			$('<div id="reply" class="well"></div>').hide().appendTo('body').load(target, function() {
				$(this).css({
					'width': '600px',
					'background-color': '#FFF',
					'position': 'absolute',
					'top': e.pageY+20,
					'left': e.pageX+20
				})
				.fadeIn();
			});
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
