$(document).ready(function(){
	function highlight() {
		$(location.hash).fadeTo('slow', 0.1).fadeTo('slow', 1);
	}
	highlight();
	$(window).bind('hashchange', function() {
		highlight();
	});
});
