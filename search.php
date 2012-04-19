<?php

$out['self'] = 'search';
require 'header.php';

$out['subtitle'] = $lang['search'];

if(checkBot() && check('topic'))
{	
	$_POST['topic'] = clean($_POST['topic']);
	$foundTopics = array();
	foreach(listEntry('topic') as $topic)
	{
		$topicEntry = readEntry('topic', $topic);
		if(stripos($topicEntry['title'], $_POST['topic']) !== false || stripos($topicEntry['content'], $_POST['topic']) !== false)
		{
			$foundTopics[$topic] = $topicEntry['title'];
		}
	}
	$out['content'] .= '<ul>';
	if($foundTopics)
	{		
		foreach($foundTopics as $topic => $title)
		{
			$out['content'] .= '<li>' .manageTopic($topic). '<a href="view.php/topic/' .$topic. '">' .$title. '</a></li>';
		}
	}
	else
	{
		$out['content'] .= '<li>' .$lang['none']. '</li>';
	}
	$out['content'] .= '</ul>';
}

$out['content'] .= form('search.php',
	text('topic').
	submit());

require 'footer.php';

?>
