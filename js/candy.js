$(document).ready(function(){
	$('a, img, input[type="submit"]').hover(
		function() {
			$(this).fadeTo('slow', 0.5);
		},
		function() {
			$(this).fadeTo('slow', 1);
		}
	);
});
