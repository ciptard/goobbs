<?php

if(!isset($out))
{
	exit;
}

if($out['self'] === 'feed')
{
	require 'theme/' .$config['theme']. '/feed.tpl.php';
}
else
{
	$out['content'] .= '<div>
	<a class="label label-warning" href="#">' .$lang['count']. ' : ' .count(array_merge(listEntry('topic'), listEntry('reply'))). '</a>';
	foreach(listEntry('forum') as $forum)
	{
		$forumEntry = readEntry('forum', $forum);
		$out['content'] .= '<a class="label label-warning" href="view.php/forum/' .$forum. '">' .$forumEntry['name']. '</a>';
	}
	$out['content'] .= '</div>';
	
	require 'theme/' .$config['theme']. '/main.tpl.php';
}

?>
