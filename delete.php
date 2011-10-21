<?php

$template = 'main';
require 'header.php';

if(isGET('topic') && isValidEntry('topic', $_GET['topic']))
{
	$topicEntry = readEntry('topic', $_GET['topic']);
	if(!isWorker() && !isAuthor($_GET['topic']))
	{
		exit;
	}
	$out['subtitle'] = $lang['delete'].$lang['topic']. ' : ' .$topicEntry['title'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';
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
		$out['content'] .= '<p><a href="view.php?forum=' .$topicEntry['forum']. '">← ' .$lang['redirect']. ' : ' .$forumEntry['name']. '</a></p>';
	}
	else
	{
		$out['content'] .= '<form action="delete.php?topic=' .$_GET['topic']. '" method="post">
		<p>' .submit(). '</p>
		</form>';
	}
}
else if(isGET('reply') && isValidEntry('reply', $_GET['reply']))
{
	$replyEntry = readEntry('reply', $_GET['reply']);
	if(!isWorker() && !isAuthor($_GET['reply']))
	{
		exit;
	}
	$out['subtitle'] = $lang['delete'].$lang['reply'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';
	if(checkBot())
	{
		deleteEntry('reply', $_GET['reply']);

		$topicEntry = readEntry('topic', $replyEntry['topic']);
		unset($topicEntry['reply'][$_GET['reply']]);
		saveEntry('topic', $replyEntry['topic'], $topicEntry);

		$out['content'] .= '<p><a href="view.php?topic=' .$replyEntry['topic']. '">← ' .$lang['redirect']. ' : ' .$topicEntry['title']. '</a></p>';
	}
	else
	{
		$out['content'] .= '<form action="delete.php?reply=' .$_GET['reply']. '" method="post">
		<p>' .submit(). '</p>
		</form>';
	}
}
else if(isGET('forum') && isAdmin() && isValidEntry('forum', $_GET['forum']))
{
	$forumEntry = readEntry('forum', $_GET['forum']);
	$out['subtitle'] = $lang['delete'].$lang['forum']. ' : ' .$forumEntry['name'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';
	if(checkBot())
	{
		deleteEntry('forum', $_GET['forum']);
		foreach($forumEntry['topic'] as $topic)
		{
			$topicEntry = readEntry('topic', $topic);
			deleteEntry('topic', $topic);

			foreach($topicEntry['reply'] as $reply)
			{
				deleteEntry('reply', $reply);
			}
		}
		$out['content'] .= '<p><a href="index.php?forum">← ' .$lang['redirect']. ' : ' .$lang['forum']. '</a></p>';
	}
	else
	{
		$out['content'] .= '<form action="delete.php?forum=' .$_GET['forum']. '" method="post">
		<p>' .submit(). '</p>
		</form>';
	}
}
else if(isGET('worker') && isAdmin() && isset($config['worker'][$_GET['worker']]))
{
	$out['subtitle'] = $lang['delete'].$lang['worker'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';
	if(checkBot())
	{
		unset($config['worker'][$_GET['worker']]);
		saveEntry('config', 'config', $config);
		$out['content'] .= '<p><a href="index.php?forum">← ' .$lang['redirect']. ' : ' .$lang['forum']. '</a></p>';
	}
	else
	{
		$out['content'] .= '<form action="delete.php?worker=' .$_GET['worker']. '" method="post">
		<p>' .submit(). '</p>
		</form>';
	}
}
else
{
	exit;
}

require 'footer.php';

?>
