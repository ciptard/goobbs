var lastHash = '';

$(document).ready(function(){
	highlight();
	$(window).bind('hashchange', function() {
		highlight();
	});
});

function highlight() {
	$(lastHash).css('background-color', '#FFF');
	$(location.hash).css('background-color', '#FFC');
	lastHash = location.hash;
}
