<?php

$template = 'feed';
require 'header.php';
require 'include/parser.inc.php';

if(isGET('topic'))
{
	$out['subtitle'] = $lang['topic'];
	$out['type'] = 'topic';
	$topics = _max(listEntry('topic'),4);
	if($topics)
	{
		foreach($topics as $topic)
		{
			$topicEntry = readEntry('topic', $topic);
			$url = 'view.php/topic/' .$topic;
			$out['content'] .= '<entry>
			<id>' .$url. '</id>
			<title>' .$topicEntry['trip']. ' ' .$lang['started']. ' ' .$topicEntry['title']. '</title>
			<updated>' .toDate($topic, 'c'). '</updated>
			<link href="' .$url. '"/>
			<summary type="html">' .htmlspecialchars(summary($topicEntry['content']), ENT_QUOTES). '</summary>
			</entry>';
		}
	}
}
else if(isGET('reply'))
{
	$out['subtitle'] = $lang['reply'];
	$out['type'] = 'reply';
	$replies = _max(listEntry('reply'), 4);
	if($replies)
	{
		foreach($replies as $reply)
		{
			$replyEntry = readEntry('reply', $reply);
			$topicEntry = readEntry('topic', $replyEntry['topic']);
			$url = 'view.php/topic/' .$replyEntry['topic']. '/p/'. onPage($reply, $topicEntry['reply']). '#' .$reply;
			$out['content'] .= '<entry>
			<id>' .$url. '</id>
			<title>' .$replyEntry['trip']. ' ' .$lang['replied']. ' ' .$topicEntry['title']. '</title>
			<updated>' .toDate($reply, 'c'). '</updated>
			<link href="' .$url. '"/>
			<summary type="html">' .htmlspecialchars(summary($replyEntry['content']), ENT_QUOTES). '</summary>
			</entry>';
		}
	}
}
else
{
	exit;
}

require 'footer.php';

?>
