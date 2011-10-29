$(document).ready(function(){
	$('a.important[href^="add.php"]').click(function(event) {
		event.preventDefault();
		$('#form').remove();
		$('<div id="form"></div>').load($(this).attr('href') + ' form', function() {
			bbcode();
		}).insertAfter(this);
	});
});
