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
	$page = array_chunk($topics, 4);
	if($page)
	{
		foreach($page[0] as $topic)
		{
			$topicEntry = readEntry('topic', $topic);
			$out['content'] .= '<entry>
			<id>' .$out['url']. 'view.php?topic=' .$topic. '</id>
			<title>' .$topicEntry['author']. '@' .$topicEntry['title']. '</title>
			<updated>' .entryDate($topic, 'c'). '</updated>
			<link href="' .$out['url']. 'view.php?topic=' .$topic. '"/>
			<summary type="html">' .clean(summary($topicEntry['content'])). '</summary>
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
	$page = array_chunk($replies, 4);
	if($page)
	{
		foreach($page[0] as $reply)
		{
			$replyEntry = readEntry('reply', $reply);
			$topicEntry = readEntry('topic', $replyEntry['topic']);
			$out['content'] .= '<entry>
			<id>' .$out['url']. 'view.php?topic=' .$replyEntry['topic']. '#' .$reply. '</id>
			<title>' .$replyEntry['author']. '@' .$topicEntry['title']. '</title>
			<updated>' .entryDate($reply, 'c'). '</updated>
			<link href="' .$out['url']. 'view.php?topic=' .$replyEntry['topic']. '#' .$reply. '"/>
			<summary type="html">' .clean(summary($replyEntry['content'])). '</summary>
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
