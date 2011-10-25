$(document).ready(function() {
	$("a, img, input[type='submit'], select").hover(
		function() {
			$(this).animate({opacity: 0.5}, "fast");
		},
		function() {
			$(this).animate({opacity: 1}, "fast");
		}
	);
});
