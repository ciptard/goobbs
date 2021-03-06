<?php

$out['self'] = 'delete';
require 'header.php';

if(isGETValidEntry('topic', 'topic') && (isWorker() || isAuthor($_GET['topic'])))
{
	$topicEntry = readEntry('topic', $_GET['topic']);
	$out['subtitle'] = lang('delete topic : %s', $topicEntry['title']);
	if(checkBot())
	{
		deleteEntry('topic', $_GET['topic']);

		$forumEntry = readEntry('forum', $topicEntry['forum']);
		unset($forumEntry['topic'][$_GET['topic']]);
		unset($forumEntry['pinnedTopic'][$_GET['topic']]);
		saveEntry('forum', $topicEntry['forum'], $forumEntry);

		foreach($topicEntry['reply'] as $reply)
		{
			deleteEntry('reply', $reply);
		}
		$out['content'] .= '<p><a href="view.php/forum/' .$topicEntry['forum']. '">← ' .$lang['redirect']. ' : ' .$forumEntry['name']. '</a></p>';
	}
	else
	{
		$out['content'] .= form('delete.php/topic/' .$_GET['topic'],
			submit());
	}
}
else if(isGETValidEntry('reply', 'reply') && (isWorker() || isAuthor($_GET['reply'])))
{
	$replyEntry = readEntry('reply', $_GET['reply']);
	$out['subtitle'] = lang('delete reply');
	if(checkBot())
	{
		deleteEntry('reply', $_GET['reply']);

		$topicEntry = readEntry('topic', $replyEntry['topic']);
		unset($topicEntry['reply'][$_GET['reply']]);
		saveEntry('topic', $replyEntry['topic'], $topicEntry);

		$out['content'] .= '<p><a href="view.php/topic/' .$replyEntry['topic']. '">← ' .$lang['redirect']. ' : ' .$topicEntry['title']. '</a></p>';
	}
	else
	{
		$out['content'] .= form('delete.php/reply/' .$_GET['reply'],
			submit());
	}
}
else if(isGETValidEntry('forum', 'forum') && isAdmin())
{
	$forumEntry = readEntry('forum', $_GET['forum']);
	$out['subtitle'] = lang('delete forum : %s', $forumEntry['name']);
	if(checkBot())
	{
		deleteEntry('forum', $_GET['forum']);
		$forums = readEntry('config', 'forumOrder');
		unset($forums[$_GET['forum']]);
		saveEntry('config', 'forumOrder', $forums);

		foreach($forumEntry['topic'] as $topic)
		{
			$topicEntry = readEntry('topic', $topic);
			deleteEntry('topic', $topic);

			foreach($topicEntry['reply'] as $reply)
			{
				deleteEntry('reply', $reply);
			}
		}
		$out['content'] .= '<p><a href="index.php/forum">← ' .$lang['redirect']. ' : ' .$lang['forum']. '</a></p>';
	}
	else
	{
		$out['content'] .= form('delete.php/forum/' .$_GET['forum'],
			submit());
	}
}
else if(isGET('worker') && isAdmin() && isset($config['worker'][$_GET['worker']]))
{
	$out['subtitle'] = lang('delete worker : %s', $config['worker'][$_GET['worker']]);
	if(checkBot())
	{
		unset($config['worker'][$_GET['worker']]);
		saveEntry('config', 'config', $config);
		$out['content'] .= '<p><a href="config.php/worker">← ' .$lang['redirect']. ' : ' .$lang['worker']. '</a></p>';
	}
	else
	{
		$out['content'] .= form('delete.php/worker/' .$_GET['worker'],
			submit());
	}
}
else
{
	exit;
}

require 'footer.php';

?>
