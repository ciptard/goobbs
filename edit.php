<?php

$template = 'main';
require 'header.php';

if(isGET('topic') && (isWorker() || isAuthor($_GET['topic'])) && isValidEntry('topic', $_GET['topic']))
{
	$topicEntry = readEntry('topic', $_GET['topic']);
	$out['subtitle'] = $lang['edit'].$lang['topic']. ' : ' .$topicEntry['title'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';
	if(checkBot() && check('title') && check('content', 1, 2000))
	{
		$topicEntry['title'] = clean($_POST['title']);
		$topicEntry['content'] = clean($_POST['content']);
		if(isWorker() &&
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
		foreach(listEntry('forum') as $forum)
		{
			$forumEntry = readEntry('forum', $forum);
			$forumOptions[$forum] = $forumEntry['name'];
		}
		$forumEntry = readEntry('forum', $topicEntry['forum']);
		$out['content'] .= '<form action="edit.php?topic=' .$_GET['topic']. '" method="post">
		<p>' .text('title', $topicEntry['title']). '</p>
		<p>' .textarea('content', $topicEntry['content']). '</p>'.
		(isWorker()? '<p>' .select('locked', array('yes' => $lang['yes'], 'no' => $lang['no']), $topicEntry['locked']? 'yes' : 'no'). ' ' .select('pinned', array('yes' => $lang['yes'], 'no' => $lang['no']), isset($forumEntry['pinnedTopic'][$_GET['topic']])? 'yes' : 'no'). ' ' .select('forum', $forumOptions, $topicEntry['forum']). '</p>' : '').
		'<p>' .submit(). '</p>
		</form>'.
		(isPOST('content')? '<p class="box">' .content(clean($_POST['content'])). '</p>' : '');
	}
}
else if(isGET('reply') && (isWorker() || isAuthor($_GET['reply'])) && isValidEntry('reply', $_GET['reply']))
{
	$replyEntry = readEntry('reply', $_GET['reply']);
	$out['subtitle'] = $lang['edit'].$lang['reply'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';
	if(checkBot() && check('content', 1, 2000))
	{
		$replyEntry['content'] = clean($_POST['content']);
		saveEntry('reply', $_GET['reply'], $replyEntry);
		$topicEntry = readEntry('topic', $replyEntry['topic']);
		$out['content'] .= '<p><a href="view.php?topic=' .$replyEntry['topic']. '&amp;p='. onPage($_GET['reply'], $topicEntry['reply']). '#' .$_GET['reply']. '">← ' .$lang['redirect']. ' : ' .$topicEntry['title']. '</a></p>';
	}
	else
	{
		require 'include/parser.inc.php';
		$out['content'] .= '<form action="edit.php?reply=' .$_GET['reply']. '" method="post">
		<p>' .textarea('content', $replyEntry['content']). '</p>
		<p>' .submit(). '</p>
		</form>'.
		(isPOST('content')? '<p class="box">' .content(clean($_POST['content'])). '</p>' : '');
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
else
{
	exit;
}

require 'footer.php';

?>
