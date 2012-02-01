$(function(){
	$('a.button[href^="add.php"]').click(function(event) {
		event.preventDefault();
		if($(this).siblings('#form').length > 0) {
			$(this).siblings('#form').slideUp('slow', function(){ $(this).remove(); });
		} else {
			$('#form').remove();
			$('<div id="form"></div>').hide().insertAfter(this).load($(this).attr('href') + ' form', function() {
				if(typeof bbcode == 'function') bbcode();
				$(this).slideDown('slow');
			});
		}
	});
});
