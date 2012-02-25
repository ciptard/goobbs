$(function(){
	$('.thumbnail').popover({
		'title': 'preview',
		'content': function() {
			return $('<div></div>').append($('<img>').css('max-width', 540).attr('src', $(this).attr('src'))).html();
		}
	});
});
