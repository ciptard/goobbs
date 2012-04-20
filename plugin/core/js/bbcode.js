$(function() {
	bbcode();
});

function bbcode() {
	var ta = $('textarea[name="content"]')[0];

	var tags = ['b', 'i', 'u', 's', 'img', 'url', 'youtube', 'block'];
	var e = $('<div id="bbcode" class="btn-group"></div>');
	for(var i in tags) {
		$('<input class="btn" type="button"/>').attr('value', tags[i]).appendTo(e);
	}
	$('<input id="upload-file" type="file"/>').insertBefore(ta);
	$(e).insertBefore(ta);

        $('#upload-file').change(function() {
           var file = document.getElementById("upload-file").files[0];

           if (!file || !file.type.match(/image.*/)) return;
            
           var fd = new FormData();
           fd.append("image", file); // Append the file
           fd.append("key", "6528448c258cff474ca9701c5bab6927");
           var xhr = new XMLHttpRequest();
           xhr.onreadystatechange = function () {
              if(xhr.readyState == 4 && xhr.status == 200) {
                ta.value += '[img]' + JSON.parse(xhr.responseText).upload.links.large_thumbnail + '[/img]';
              }
            }
           xhr.open("POST", "http://api.imgur.com/2/upload.json"); // Boooom!
           xhr.send(fd);
           
        });

	$('#bbcode input').click(function() {
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
