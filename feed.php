<?php

$template = 'feed';
require 'header.php';
require 'include/parser.inc.php';

$dir = dirname($_SERVER['SCRIPT_NAME']);
$out['url'] = 'http://' .$_SERVER['SERVER_NAME'].$dir.($dir === '/'? '' : '/');

if(isGET('topic'))
{
	$out['subtitle'] = $lang['topic'];
	$out['type'] = 'topic';
	$topics = listEntry('topic');
	rsort($topics);
	$topics = array_slice($topics, 0, 4);
	if($topics)
	{
		foreach($topics as $topic)
		{
			$topicEntry = readEntry('topic', $topic);
			$url = $out['url']. 'view.php?topic=' .$topic;
			$out['content'] .= '<entry>
			<id>' .$url. '</id>
			<title>' .$topicEntry['trip']. ' ' .$lang['started']. ' ' .$topicEntry['title']. '</title>
			<updated>' .entryDate($topic, 'c'). '</updated>
			<link href="' .$url. '"/>
			<summary type="html">' .htmlspecialchars(summary($topicEntry['contentHTML']), ENT_QUOTES). '</summary>
			</entry>';
		}
	}
}
else if(isGET('reply'))
{
	$out['subtitle'] = $lang['reply'];
	$out['type'] = 'reply';
	$replies = listEntry('reply');
	rsort($replies);
	$replies = array_slice($replies, 0, 4);
	if($replies)
	{
		foreach($replies as $reply)
		{
			$replyEntry = readEntry('reply', $reply);
			$topicEntry = readEntry('topic', $replyEntry['topic']);
			$url = $out['url']. 'view.php?topic=' .$replyEntry['topic']. '&amp;p='. onPage($reply, $topicEntry['reply']). '#' .$reply;
			$out['content'] .= '<entry>
			<id>' .$url. '</id>
			<title>' .$replyEntry['trip']. ' ' .$lang['replied']. ' ' .$topicEntry['title']. '</title>
			<updated>' .entryDate($reply, 'c'). '</updated>
			<link href="' .$url. '"/>
			<summary type="html">' .htmlspecialchars(summary($replyEntry['contentHTML']), ENT_QUOTES). '</summary>
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
