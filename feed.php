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
			$out['content'] .= '<entry>
			<id>' .$out['url']. 'view.php?topic=' .$topic. '</id>
			<title>' .$topicEntry['trip']. ' - ' .$topicEntry['title']. '</title>
			<updated>' .entryDate($topic, 'c'). '</updated>
			<link href="' .$out['url']. 'view.php?topic=' .$topic. '"/>
			<summary type="html">' .htmlspecialchars(summary($topicEntry['content']), ENT_QUOTES). '</summary>
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
			$out['content'] .= '<entry>
			<id>' .$out['url']. 'view.php?topic=' .$replyEntry['topic']. '#' .$reply. '</id>
			<title>' .$replyEntry['trip']. ' - ' .$topicEntry['title']. '</title>
			<updated>' .entryDate($reply, 'c'). '</updated>
			<link href="' .$out['url']. 'view.php?topic=' .$replyEntry['topic']. '#' .$reply. '"/>
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
