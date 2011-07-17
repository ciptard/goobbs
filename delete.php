<?php

$template = 'main';
require 'header.php';

if(isGET('topic') && isValidEntry('topic', $_GET['topic']))
{
	$topicEntry = readEntry('topic', $_GET['topic']);
	if(!isModerator() && !isAuthor($topicEntry['author']))
	{
		exit;
	}
	$out['subtitle'] = $lang['delete'].$lang['topic']. ' : ' .$topicEntry['title'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';
	if(checkBot('token'))
	{
		deleteEntry('topic', $_GET['topic']);

		$forumEntry = readEntry('forum', $topicEntry['forum']);
		unset($forumEntry['topic'][$_GET['topic']]);
		unset($forumEntry['pinnedTopic'][$_GET['topic']]);
		saveEntry('forum', $topicEntry['forum'], $forumEntry);

		$user = md5($topicEntry['author']);
		$userEntry = readEntry('user', $user);
		unset($userEntry['topic'][$_GET['topic']]);
		saveEntry('user', $user, $userEntry);

		foreach($topicEntry['reply'] as $reply)
		{
			$replyEntry = readEntry('reply', $reply);
			deleteEntry('reply', $reply);

			$user = md5($replyEntry['author']);
			$userEntry = readEntry('user', $user);
			unset($userEntry['reply'][$reply]);
			saveEntry('user', $user, $userEntry);
		}
		$out['content'] .= '<p><a href = "view.php?forum=' .$topicEntry['forum']. '">← ' .$lang['redirect']. ' : ' .$forumEntry['name']. '</a></p>';
	}
	else
	{
		$out['content'] .= '<form action = "delete.php?topic=' .$_GET['topic']. '" method = "post">
		<p>' .submit(). '</p>
		</form>';
	}
}
else if(isGET('reply') && isValidEntry('reply', $_GET['reply']))
{
	$replyEntry = readEntry('reply', $_GET['reply']);
	if(!isModerator() && !isAuthor($replyEntry['author']))
	{
		exit;
	}
	$out['subtitle'] = $lang['delete'].$lang['reply'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';
	if(checkBot('token'))
	{
		deleteEntry('reply', $_GET['reply']);

		$topicEntry = readEntry('topic', $replyEntry['topic']);
		unset($topicEntry['reply'][$_GET['reply']]);
		saveEntry('topic', $replyEntry['topic'], $topicEntry);

		$user = md5($replyEntry['author']);
		$userEntry = readEntry('user', $user);
		unset($userEntry['reply'][$_GET['reply']]);
		saveEntry('user', $user, $userEntry);

		$out['content'] .= '<p><a href = "view.php?topic=' .$replyEntry['topic']. '">← ' .$lang['redirect']. ' : ' .$topicEntry['title']. '</a></p>';
	}
	else
	{
		$out['content'] .= '<form action = "delete.php?reply=' .$_GET['reply']. '" method = "post">
		<p>' .submit(). '</p>
		</form>';
	}
}
else if(isGET('forum') && isAdmin() && isValidEntry('forum', $_GET['forum']))
{
	$forumEntry = readEntry('forum', $_GET['forum']);
	$out['subtitle'] = $lang['delete'].$lang['forum']. ' : ' .$forumEntry['name'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';
	if(checkBot('token'))
	{
		deleteEntry('forum', $_GET['forum']);
		foreach($forumEntry['topic'] as $topic)
		{
			$topicEntry = readEntry('topic', $topic);
			deleteEntry('topic', $topic);

			$user = md5($topicEntry['author']);
			$userEntry = readEntry('user', $user);
			unset($userEntry['topic'][$topic]);
			saveEntry('user', $user, $userEntry);

			foreach($topicEntry['reply'] as $reply)
			{
				$replyEntry = readEntry('reply', $reply);
				deleteEntry('reply', $reply);

				$user = md5($replyEntry['author']);
				$userEntry = readEntry('user', $user);
				unset($userEntry['reply'][$reply]);
				saveEntry('user', $user, $userEntry);
			}
		}
		$out['content'] .= '<p><a href = "index.php?forum">← ' .$lang['redirect']. ' : ' .$lang['forum']. '</a></p>';
	}
	else
	{
		$out['content'] .= '<form action = "delete.php?forum=' .$_GET['forum']. '" method = "post">
		<p>' .submit(). '</p>
		</form>';
	}
}
else if(isGET('user') && isAdmin() && $_GET['user'] !== md5('admin') && isValidEntry('user', $_GET['user']))
{
	$userEntry = readEntry('user', $_GET['user']);
	$out['subtitle'] = $lang['delete'].$lang['user']. ' : ' .$userEntry['name'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';
	if(checkBot('token'))
	{
		deleteEntry('user', $_GET['user']);

		foreach($userEntry['reply'] as $reply)
		{
			$replyEntry = readEntry('reply', $reply);
			deleteEntry('reply', $reply);

			$topicEntry = readEntry('topic', $replyEntry['topic']);
			unset($topicEntry['reply'][$reply]);
			saveEntry('topic', $replyEntry['topic'], $topicEntry);
		}

		foreach($userEntry['topic'] as $topic)
		{
			$topicEntry = readEntry('topic', $topic);
			deleteEntry('topic', $topic);

			$forumEntry = readEntry('forum', $topicEntry['forum']);
			unset($forumEntry['topic'][$topic]);
			unset($forumEntry['pinnedTopic'][$topic]);
			saveEntry('forum', $topicEntry['forum'], $forumEntry);
			foreach($topicEntry['reply'] as $reply)
			{
				$replyEntry = readEntry('reply', $reply);
				deleteEntry('reply', $reply);

				$user = md5($replyEntry['author']);
				$userEntry = readEntry('user', $user);
				unset($userEntry['reply'][$reply]);
				saveEntry('user', $user, $userEntry);
			}
		}
		$out['content'] .= '<p><a href = "index.php?forum">← ' .$lang['redirect']. ' : ' .$lang['forum']. '</a></p>';
	}
	else
	{
		$out['content'] .= '<form action = "delete.php?user=' .$_GET['user']. '" method = "post">
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
