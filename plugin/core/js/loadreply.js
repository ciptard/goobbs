$(function(){
	$('a.label-info[href^="view.php"]').each(function () {
		var target = this;
		$.getJSON('service.php/reply/' + $(target).attr('href').split('#')[1], function(json) {
			$(target).popover({
				'title': json['trip'],
				'content': json['content']
			});
		});
	});
});
