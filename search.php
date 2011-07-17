<?php

$template = 'main';
require 'header.php';
require 'include/manage.inc.php';

$out['subtitle'] = $lang['search'];
$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';

if(checkBot('token') && check('topic') && checkBot('captcha'))
{
	$_POST['topic'] = clean($_POST['topic']);
	$topics = listEntry('topic');
	foreach($topics as $topic)
	{
		$topicEntry = readEntry('topic', $topic);
		if(strpos($topicEntry['title'], $_POST['topic']) !== false || strpos($topicEntry['content'], $_POST['topic']) !== false)
		{
			$foundTopics[$topic]['title'] = $topicEntry['title'];
			$foundTopics[$topic]['author'] = $topicEntry['author'];
		}
	}
	$out['content'] .= '<ul>';
	if($foundTopics)
	{
		foreach($foundTopics as $topic => $topicEntry)
		{
			$out['content'] .= '<li>' .manageTopic($topic, $topicEntry['author']). '<a href = "view.php?user=' .md5($topicEntry['author']). '">' .$topicEntry['author']. '</a> @ <a href = "view.php?topic=' .$topic. '">' .$topicEntry['title']. '</a></li>';
		}
	}
	else
	{
		$out['content'] .= '<li>' .$lang['none']. '</li>';
	}
	$out['content'] .= '</ul>';
}

$out['content'] .= '<form action = "search.php" method = "post">
<p>' .text('topic'). '</p>
<p>' .captcha(). '</p>
<p>' .submit(). '</p>
</form>';

require 'footer.php';

?>
