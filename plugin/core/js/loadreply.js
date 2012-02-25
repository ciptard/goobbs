$(function(){
	$('a.label-info[href^="view.php"]').popover({
		'title': 'quote',
		'content': function() {
			var link = $(this).attr('href').split('#');
			var target = link[0] + ' #' + link[1] + ' .content';
			$.ajaxSetup({async:false});
			return $('<div></div>').load(target).children(':first').html();
		}
	});
});
