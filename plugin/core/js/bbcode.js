$(function() {
	bbcode();
});

function bbcode() {
	var ta = $('textarea[name="content"]')[0];
	var bar = $('<div id="bbcode" class="btn-group"></div>');
	$.each(['b', 'i', 'u', 's', 'img', 'url', 'youtube', 'block'], function (i, tag) {
		$('<input class="btn" type="button"/>').attr('value', tag).appendTo(bar);
	});
	$('<div><input id="upload-file" type="file" multiple="multiple"/></div>').insertBefore(ta);
	$(bar).insertBefore(ta);

	
	$('#upload-file').change(function() {
	  $.each($('#upload-file')[0].files, function(i, file) {
		var fd = new FormData();
		fd.append('image', file);
		fd.append('key', '6528448c258cff474ca9701c5bab6927');
		
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function () {
		  if(xhr.readyState == 4 && xhr.status == 200) {
			ta.value += '[img]' + JSON.parse(xhr.responseText).upload.links.large_thumbnail + '[/img]';
		  }
		}
		xhr.open('POST', 'http://api.imgur.com/2/upload.json');
		xhr.send(fd);
	  });
	});

	$('#bbcode .btn').click(function() {
		var tag = $(this).attr('value');
		var start = '['+tag+']';
		var end = '[/'+tag+']';
		
		var param;
		switch(tag) {
			case 'img':
				param = prompt('Enter image URL', 'http://');
				if (param)
					start += param;
				else
					return;
		      		break;
			case 'youtube':
				param = prompt('Enter youtube ID', '3f7l-Z4NF70');
				if (param)
					start += param;
				else
					return;
	      			break;
			case 'url':
				param = prompt('Enter URL', 'http://');
				if (param)
					start = '[url=' + param + ']';
				else
					return;
      				break;
		}

		ta.focus();
		if (typeof ta.selectionStart != 'undefined') {
			var startPos = ta.selectionStart;
			var endPos = ta.selectionEnd;
			ta.value = ta.value.substring(0, startPos) + start + ta.value.substring(startPos, endPos) + end + ta.value.substring(endPos, ta.value.length);
		} else {
			ta.value += start + end;
		}
	});
}
