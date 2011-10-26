<?php

function bbcode($text)
{
	//the pattern to be matched
	//the replacement

	$pattern[] = '%\[b\]([^[]+)\[/b\]%';
	$replace[] = '<b>$1</b>';

	$pattern[] = '%\[i\]([^[]+)\[/i\]%';
	$replace[] = '<i>$1</i>';

	$pattern[] = '%\[u\]([^[]+)\[/u\]%';
	$replace[] = '<ins>$1</ins>';

	$pattern[] = '%\[s\]([^[]+)\[/s\]%';
	$replace[] = '<del>$1</del>';

	$pattern[] = '%\[img\]([^[]+)\[/img\]%';
	$replace[] = '<img src="$1" alt=""/>';

	$pattern[] = '%\[url=([^[]+)\]([^[]+)\[/url\]%';
	$replace[] = '<a href="$1">$2</a>';

	$pattern[] = '%\[youtube\]([-\w]{11})\[/youtube\]%';
	$replace[] = '<iframe width="320" height="240" src="http://www.youtube.com/embed/$1?rel=0" frameborder="0"></iframe>';

	$pattern[] = '%\[block\]([^[]+)\[/block\]%';
	$replace[] = '<div class="block">$1</div>';
	
	$pattern[] = '%\[quote\](\d{4}-\d{2}-\d{8}[a-z\d]{5})\[/quote\]%';
	$replace[] = '<a href="#$1">&gt; $1</a>';
	//the variable for the replace
	return preg_replace($pattern, $replace, $text);
}

function summary($text)
{
	$parts = explode("\n", $text, 2);
	return bbcode($parts[0]). '...';
}

function content($text)
{
	return nl2br(bbcode($text));
}

?>
