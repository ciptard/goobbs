$(document).ready(function(){
	highlight();
	$(window).bind('hashchange', function() {
		highlight();
	});
});

function highlight() {
	$(location.hash).fadeTo('slow', 0.1).fadeTo('slow', 1);
}
