<?php

function bbcode($text, $summary = true)
{
	//the pattern to be matched
	//the replacement
	global $pattern, $replace;

	$pattern[] = '%\[block\]([^\a]+?)\[/block\]%e';
	$replace[] = $summary? '\'[...]\'' : '\'<pre class="pre-scrollable">\'.str_replace(\'<br />\', \'\', \'$1\').\'</pre>\'';

	$pattern[] = '%\[b\]([^\a]+?)\[/b\]%';
	$replace[] = '<b>$1</b>';

	$pattern[] = '%\[i\]([^\a]+?)\[/i\]%';
	$replace[] = '<i>$1</i>';

	$pattern[] = '%\[u\]([^\a]+?)\[/u\]%';
	$replace[] = '<ins>$1</ins>';

	$pattern[] = '%\[s\]([^\a]+?)\[/s\]%';
	$replace[] = '<del>$1</del>';

	$pattern[] = '%\[img\]([^\r\n\[]+?)\[/img\]%';
	$replace[] = '<img class="thumbnail" src="$1" alt=""/>';

	$pattern[] = '%\[url=([^\r\n\[]+?)\]([^\a]+?)\[/url\]%';
	$replace[] = '<a target="_blank" href="$1">$2</a>';

	$pattern[] = '%\[youtube\]([-\w]{11})\[/youtube\]%';
	$replace[] = $summary? '[...]' : '<iframe width="320" height="240" src="http://www.youtube.com/embed/$1?rel=0" frameborder="0"></iframe>';
	
	$pattern[] = '%\[quote\](\d{4}-\d{2}-\d{8}[a-z\d]{5})\[/quote\]%e';
	$replace[] = 'quote(\'$1\')';

	return preg_replace($pattern, $replace, $text);
}

function summary($text)
{
	$parts = explode("\n", bbcode($text));
	$out = array();
	$sum = 0;
	foreach ($parts as $part)
	{
		$len = strlen($part);
		if ($len === 0)
			continue;
		if ($sum > 200)
			return nl2br(implode("\n", $out));
		$out[]= $part;
		$sum += $len;
	}
	return nl2br(implode("\n", $out));
}

function content($text)
{
	return bbcode(nl2br($text), false);
}

?>
