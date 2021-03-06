<?php

$out['self'] = 'edit';
require 'header.php';

if(isGETValidEntry('topic', 'topic') && (isWorker() || isAuthor($_GET['topic'])))
{
	$topicEntry = readEntry('topic', $_GET['topic']);
	$out['subtitle'] = lang('edit topic : %s', $topicEntry['title']);
	if(checkBot() && check('title') && check('content', 1, 2000))
	{
		$topicEntry['title'] = clean($_POST['title']);
		$topicEntry['content'] = transNL(clean($_POST['content']));
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
		$out['content'] .= '<p><a href="view.php/topic/' .$_GET['topic']. '">← ' .$lang['redirect']. ' : ' .$topicEntry['title']. '</a></p>';
	}
	else
	{
		foreach(listEntry('forum') as $forum)
		{
			$forumEntry = readEntry('forum', $forum);
			$forumOptions[$forum] = $forumEntry['name'];
		}
		$forumEntry = readEntry('forum', $topicEntry['forum']);
		$out['content'] .= form('edit.php/topic/' .$_GET['topic'],
			text('title', $topicEntry['title']).
			textarea('content', $topicEntry['content']).
			(isWorker()?
				select('locked', array('yes' => $lang['yes'], 'no' => $lang['no']), $topicEntry['locked']? 'yes' : 'no').
				select('pinned', array('yes' => $lang['yes'], 'no' => $lang['no']), isset($forumEntry['pinnedTopic'][$_GET['topic']])? 'yes' : 'no').
				select('forum', $forumOptions, $topicEntry['forum']) : '').
			submit()).
		preview('content');
	}
}
else if(isGETValidEntry('reply', 'reply') && (isWorker() || isAuthor($_GET['reply'])))
{
	$replyEntry = readEntry('reply', $_GET['reply']);
	$out['subtitle'] = lang('edit reply');
	if(checkBot() && check('content', 1, 2000))
	{
		$replyEntry['content'] = transNL(clean($_POST['content']));
		saveEntry('reply', $_GET['reply'], $replyEntry);
		$topicEntry = readEntry('topic', $replyEntry['topic']);
		$out['content'] .= '<p><a href="view.php/topic/' .$replyEntry['topic']. '/p/'. onPage($_GET['reply'], $topicEntry['reply']). '#' .$_GET['reply']. '">← ' .$lang['redirect']. ' : ' .$topicEntry['title']. '</a></p>';
	}
	else
	{
		$out['content'] .= form('edit.php/reply/' .$_GET['reply'],
			textarea('content', $replyEntry['content']).
			submit()).
		preview('content');
	}
}
else if(isGETValidEntry('forum', 'forum') && isAdmin())
{
	$forumEntry = readEntry('forum', $_GET['forum']);
	$out['subtitle'] = lang('edit forum : %s', $forumEntry['name']);
	if(checkBot() && check('name') && check('info', 1, 80))
	{
		$forumEntry['name'] = clean($_POST['name']);
		$forumEntry['info'] = clean($_POST['info']);
		saveEntry('forum', $_GET['forum'], $forumEntry);
		$out['content'] .= '<p><a href="index.php/forum">← ' .$lang['redirect']. ' : ' .$lang['forum']. '</a></p>';
	}
	else
	{
		$out['content'] .= form('edit.php/forum/' .$_GET['forum'],
			text('name', $forumEntry['name']).
			text('info', $forumEntry['info']).
			submit());
	}
}
else
{
	exit;
}

require 'footer.php';

?>
