<?php

function bbcode($text)
{
	//the pattern to be matched
	//the replacement

	$pattern[] = '%\[b\]([^\r\n\[]+)\[/b\]%';
	$replace[] = '<b>$1</b>';

	$pattern[] = '%\[i\]([^\r\n\[]+)\[/i\]%';
	$replace[] = '<i>$1</i>';

	$pattern[] = '%\[u\]([^\r\n\[]+)\[/u\]%';
	$replace[] = '<ins>$1</ins>';

	$pattern[] = '%\[s\]([^\r\n\[]+)\[/s\]%';
	$replace[] = '<del>$1</del>';

	$pattern[] = '%\[img\]([^\r\n\[]+)\[/img\]%';
	$replace[] = '<img src="$1" alt=""/>';

	$pattern[] = '%\[url=([^\r\n\]]+)\]([^\r\n\[]+)\[/url\]%';
	$replace[] = '<a href="$1">$2</a>';

	$pattern[] = '%\[youtube\]([-\w]{11})\[/youtube\]%';
	$replace[] = '<iframe width="320" height="240" src="http://www.youtube.com/embed/$1?rel=0" frameborder="0"></iframe>';

	$pattern[] = '%\[block\](([^\t](?!\[block\]))+)\[/block\]%e';
	$replace[] = '\'<span class="block">\'.str_replace(\'<br />\', \'\', \'$1\').\'</span>\'';
	
	$pattern[] = '%\[cut\]%';
	$replace[] = '';
	
	$pattern[] = '%\[quote=(\d{4}-\d{2}-\d{8}[a-z\d]{5})\]([^\r\n\[]+)\[/quote\]%';
	$replace[] = '<a href="#$1">&gt; $2</a>';

	return preg_replace($pattern, $replace, $text);
}

function summary($text)
{
	$parts = explode('[cut]', $text, 2);
	return content($parts[0]);
}

function content($text)
{
	return bbcode(nl2br($text));
}

?>
