<?php

function bbcode($text)
{
	//the pattern to be matched
	//the replacement

	$pattern[] = '%\[b\]([^\a]+?)\[/b\]%';
	$replace[] = '<b>$1</b>';

	$pattern[] = '%\[i\]([^\a]+?)\[/i\]%';
	$replace[] = '<i>$1</i>';

	$pattern[] = '%\[u\]([^\a]+?)\[/u\]%';
	$replace[] = '<ins>$1</ins>';

	$pattern[] = '%\[s\]([^\a]+?)\[/s\]%';
	$replace[] = '<del>$1</del>';

	$pattern[] = '%\[img\]([^\r\n\[]+?)\[/img\]%';
	$replace[] = '<img src="$1" alt=""/>';

	$pattern[] = '%\[url=([^\r\n\[]+?)\]([^\a]+?)\[/url\]%';
	$replace[] = '<a target="_blank" href="$1">$2</a>';

	$pattern[] = '%\[youtube\]([-\w]{11})\[/youtube\]%';
	$replace[] = '<iframe width="320" height="240" src="http://www.youtube.com/embed/$1?rel=0" frameborder="0"></iframe>';

	$pattern[] = '%\[block\]([^\a]+?)\[/block\]%e';
	$replace[] = '\'<span class="block">\'.str_replace(\'<br />\', \'\', \'$1\').\'</span>\'';
	
	$pattern[] = '%\[cut\]%';
	$replace[] = '';
	
	$pattern[] = '%\[quote\](\d{4}-\d{2}-\d{8}[a-z\d]{5})\[/quote\]%e';
	$replace[] = 'permalink(\'$1\')';

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
