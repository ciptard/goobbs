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
	$out['subtitle'] = $lang['edit'].$lang['topic']. ' : ' .$topicEntry['title'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';
	if(checkBot() && check('title') && check('content', 1, 2000))
	{
		$topicEntry['title'] = clean($_POST['title']);
		$topicEntry['content'] = clean($_POST['content']);
		if(isModerator() &&
			isPOST('locked') && ($_POST['locked'] === 'yes' || $_POST['locked'] === 'no') &&
			isPOST('pinned') && ($_POST['pinned'] === 'yes' || $_POST['pinned'] === 'no') &&
			isPOST('forum') && isValidEntry('forum', $_POST['forum']))
		{
			$topicEntry['locked'] = $_POST['locked'] === 'yes';

			if($topicEntry['forum'] !== $_POST['forum'])
			{
				$forumEntry = readEntry('forum', $topicEntry['forum']);
				unset($forumEntry['topic'][$_GET['topic']]);
				unset($forumEntry['pinnedTopic'][$_GET['topic']]);
				saveEntry('forum', $topicEntry['forum'], $forumEntry);

				$topicEntry['forum'] = $_POST['forum'];
				$forumEntry = readEntry('forum', $topicEntry['forum']);
				$forumEntry['topic'][$_GET['topic']] = $_GET['topic'];
				saveEntry('forum', $topicEntry['forum'], $forumEntry);
			}
			
			$forumEntry = readEntry('forum', $topicEntry['forum']);
			if($_POST['pinned'] === 'yes')
			{
				$forumEntry['pinnedTopic'][$_GET['topic']] = $_GET['topic'];
			}
			else
			{
				unset($forumEntry['pinnedTopic'][$_GET['topic']]);
			}
			saveEntry('forum', $topicEntry['forum'], $forumEntry);
		}
		saveEntry('topic', $_GET['topic'], $topicEntry);
		$out['content'] .= '<p><a href="view.php?topic=' .$_GET['topic']. '">← ' .$lang['redirect']. ' : ' .$topicEntry['title']. '</a></p>';
	}
	else
	{
		require 'include/parser.inc.php';
		
		$options['yes'] = $lang['yes'];
		$options['no'] = $lang['no'];

		$forums = listEntry('forum');
		foreach($forums as $forum)
		{
			$forumEntry = readEntry('forum', $forum);
			$forumOptions[$forum] = $forumEntry['name'];
		}
		$forumEntry = readEntry('forum', $topicEntry['forum']);
		$out['content'] .= '<form action="edit.php?topic=' .$_GET['topic']. '" method="post">
		<p>' .text('title', $topicEntry['title']). '</p>
		<p>' .textarea($topicEntry['content']). '</p>'.
		(isModerator()? '<p>' .select('locked', $options, $topicEntry['locked']? 'yes' : 'no'). ' ' .select('pinned', $options, isset($forumEntry['pinnedTopic'][$_GET['topic']])? 'yes' : 'no'). ' ' .select('forum', $forumOptions, $topicEntry['forum']). '</p>' : '').
		'<p>' .submit(). '</p>
		</form>'.
		(check('content', 1, 2000)? '<div class="block">' .content(clean($_POST['content'])). '</div>' : '');
	}
}
else if(isGET('reply') && isValidEntry('reply', $_GET['reply']))
{
	$replyEntry = readEntry('reply', $_GET['reply']);
	if(!isModerator() && !isAuthor($replyEntry['author']))
	{
		exit;
	}
	$out['subtitle'] = $lang['edit'].$lang['reply'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';
	if(checkBot() && check('content', 1, 2000))
	{
		$replyEntry['content'] = clean($_POST['content']);
		saveEntry('reply', $_GET['reply'], $replyEntry);
		$topicEntry = readEntry('topic', $replyEntry['topic']);
		$out['content'] .= '<p><a href="view.php?topic=' .$replyEntry['topic']. '#' .$_GET['reply']. '">← ' .$lang['redirect']. ' : ' .$topicEntry['title']. '</a></p>';
	}
	else
	{
		require 'include/parser.inc.php';
		$out['content'] .= '<form action="edit.php?reply=' .$_GET['reply']. '" method="post">
		<p>' .textarea($replyEntry['content']). '</p>
		<p>' .submit(). '</p>
		</form>'.
		(check('content', 1, 2000)? '<div class="block">' .content(clean($_POST['content'])). '</div>' : '');
	}
}
else if(isGET('forum') && isAdmin() && isValidEntry('forum', $_GET['forum']))
{
	$forumEntry = readEntry('forum', $_GET['forum']);
	$out['subtitle'] = $lang['edit'].$lang['forum']. ' : ' .$forumEntry['name'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';
	if(checkBot() && check('name') && check('info', 1, 80))
	{
		$forumEntry['name'] = clean($_POST['name']);
		$forumEntry['info'] = clean($_POST['info']);
		saveEntry('forum', $_GET['forum'], $forumEntry);
		$out['content'] .= '<p><a href="index.php?forum">← ' .$lang['redirect']. ' : ' .$lang['forum']. '</a></p>';
	}
	else
	{
		$out['content'] .= '<form action="edit.php?forum=' .$_GET['forum']. '" method="post">
		<p>' .text('name', $forumEntry['name']). '</p>
		<p>' .text('info', $forumEntry['info']). '</p>
		<p>' .submit(). '</p>
		</form>';
	}
}
else if(isGET('user') && (isAdmin() || $_GET['user'] === md5($_SESSION['name'])) && isValidEntry('user', $_GET['user']))
{
	$userEntry = readEntry('user', $_GET['user']);
	$out['subtitle'] = $lang['edit'].$lang['user']. ' : ' .$userEntry['name'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';
	if(checkBot() && check('password'))
	{
		$userEntry['password'] = hide($_POST['password']);
		if(isAdmin() && $userEntry['role'] !== 'admin' &&
			isPOST('role') && ($_POST['role'] === 'user' || $_POST['role'] === 'moderator'))
		{
			$userEntry['role'] = $_POST['role'];
		}
		saveEntry('user', $_GET['user'], $userEntry);
		$out['content'] .= '<p><a href="view.php?user=' .$_GET['user']. '">← ' .$lang['redirect']. ' : ' .$userEntry['name']. '</a></p>';
	}
	else
	{
		$roleOptions['user'] = $lang['user'];
		$roleOptions['moderator'] = $lang['moderator'];

		$out['content'] .= '<form action="edit.php?user=' .$_GET['user']. '" method="post">
		<p>' .password(). '</p>'.
		(isAdmin() && $userEntry['role'] !== 'admin'? '<p>' .select('role', $roleOptions, $userEntry['role']). '</p>' : '').
		'<p>' .submit(). '</p>
		</form>';
	}
}
else
{
	exit;
}

require 'footer.php';

?>
