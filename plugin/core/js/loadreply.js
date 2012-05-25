$(function(){
	$('a.label-info[href^="view.php"]').each(function () {
		var target = this;
		var link = $(target).attr('href').split('#');
		$.getJSON('service.php/reply/' + link[1], function(json) {
			$(target).popover({
				'title': json['trip'],
				'content': json['content']
			});
		});
	});
});
