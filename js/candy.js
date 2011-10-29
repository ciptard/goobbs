$(document).ready(function(){
	$("a, img, input[type='submit']").hover(
		function() {
			$(this).animate({opacity: 0.5});
		},
		function() {
			$(this).animate({opacity: 1});
	});
});