$(document).ready(function(){
	var ta = $('textarea').get(0);
	//display panel
	var tags = ['b', 'i', 'u', 's', 'img', 'url', 'youtube', 'block'];
	var text = '';
	for(var i in tags)
	{
		text += '<input type = "button" value = "' +tags[i]+ '"/>';
	}
	$(ta).before('<div id = "bbcode">'+text+'</div>');
	
	//eyecandy
	$('#bbcode input').css('margin', 5);
	
	$('#bbcode input').hover(
	function() {
		$(this).animate({
			opacity: 0.5
		}, 'fast');
	},
	function() {
		$(this).animate({
			opacity: 1
		}, 'fast');
	});
	
	//click
	$('#bbcode input').click(function() {
		var tag = $(this).attr('value');
		var start = '['+tag+']';
		var end = '[/'+tag+']';
		
		var param;
		if (tag == 'img')
		{
			param = prompt('Enter image URL', 'http://');
			if (param)
				start += param;
			else
				return;
		}
		if (tag == 'youtube')
		{
			param = prompt('Enter youtube ID', '3f7l-Z4NF70');
			if (param)
				start += param;
			else
				return;
		}
		if (tag == 'url')
		{
			param = prompt('Enter URL', 'http://');
			if (param)
				start = '[url=' + param + ']';
			else
				return;
		}

		if (typeof ta.selectionStart != 'undefined')
		{
			ta.focus();
			var startPos = ta.selectionStart;
			var endPos = ta.selectionEnd;
			ta.value = ta.value.substring(0, startPos) + start + ta.value.substring(startPos, endPos) + end + ta.value.substring(endPos, ta.value.length);
		}
		else
		{
			ta.value += start + end;
		}
	});
});
