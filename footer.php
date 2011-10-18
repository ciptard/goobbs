<?php

if(!isset($template))
{
	exit;
}

if($template === 'main')
{
	$forums = listEntry('forum');
	$out['content'] .= '<div id="jump"><ul>
	<li>' .$lang['user']. ' : ' .count(listEntry('user')). '</li>
	<li>' .$lang['count']. ' : ' .count(listEntry('topic') + listEntry('reply')). '</li>';
	foreach($forums as $forum)
	{
		$forumEntry = readEntry('forum', $forum);
		$out['content'] .= '<li><a href="view.php?forum=' .$forum. '">' .$forumEntry['name']. '</a></li>';
	}
	$out['content'] .= '</ul></div>';
}

require 'template/' .$template. '.tpl.php';

?>
