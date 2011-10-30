$(document).ready(function(){
	$('a.important[href^="add.php"]').click(function(event) {
		event.preventDefault();
		$('#form').remove();
		$('<div id="form"></div>').hide().insertAfter(this).load($(this).attr('href') + ' form', function() {
			bbcode();
			$(this).slideDown('slow');
		});
	});
});
