var lastHash='';

$(document).ready(function(){
	highlight();
	$(window).bind('hashchange', function() {
		highlight();
	});
});

function highlight() {
	$(lastHash).css('background-color', '#FFFFFF');
	$(location.hash).css('background-color', '#FFFF99');
	lastHash=location.hash;
}
