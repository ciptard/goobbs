<?php

$template = 'main';
require 'header.php';

$out['subtitle'] = $lang['search'];
$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';

if(checkBot() && check('topic'))
{	
	$_POST['topic'] = clean($_POST['topic']);
	$topics = listEntry('topic');
	$foundTopics = array();
	foreach($topics as $topic)
	{
		$topicEntry = readEntry('topic', $topic);
		if(strpos($topicEntry['title'], $_POST['topic']) !== false || strpos($topicEntry['content'], $_POST['topic']) !== false)
		{
			$foundTopics[$topic] = $topicEntry['title'];
		}
	}
	$out['content'] .= '<ul>';
	if($foundTopics)
	{
		require 'include/manage.inc.php';
		
		foreach($foundTopics as $topic => $title)
		{
			$out['content'] .= '<li>' .manageTopic($topic). '<a href="view.php?topic=' .$topic. '">' .$title. '</a></li>';
		}
	}
	else
	{
		$out['content'] .= '<li>' .$lang['none']. '</li>';
	}
	$out['content'] .= '</ul>';
}

$out['content'] .= '<form action="search.php" method="post">
<p>' .text('topic'). '</p>
<p>' .submit(). '</p>
</form>';

require 'footer.php';

?>
