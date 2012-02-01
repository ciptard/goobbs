$(function(){
	$('#menu ul li a, input[type="submit"]').hover(
		function() {
			$(this).fadeTo('slow', 0.5);
		},
		function() {
			$(this).fadeTo('slow', 1);
		}
	);
});
