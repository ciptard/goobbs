<?php

$template = 'main';
require 'header.php';

if(isGET('topic') && isValidEntry('forum', $_GET['topic']))
{
	$forumEntry = readEntry('forum', $_GET['topic']);
	$out['subtitle'] = $lang['add'].$lang['topic']. ' : ' .$forumEntry['name'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';
	if(checkBot() && check('name', 0) && check('title') && check('content', 1, 2000))
	{
		$topicEntry['title'] = clean($_POST['title']);
		$topicEntry['content'] = clean($_POST['content']);
		$topicEntry['view'] = 0;
		$topicEntry['forum'] = $_GET['topic'];
		$topicEntry['reply'] = array();
		$topicEntry['locked'] = false;
		$topic = newEntry();
		$topicEntry['trip'] = $_POST['name'] === ''? substr($topic, -5) : trip(clean($_POST['name']));
		saveEntry('topic', $topic, $topicEntry);

		$forumEntry['topic'][$topic] = $topic;
		saveEntry('forum', $topicEntry['forum'], $forumEntry);
		
		$_SESSION[$topic] = $topic;

		$out['content'] .= '<p><a href="view.php?topic=' .$topic. '">← ' .$lang['redirect']. ' : ' .$topicEntry['title']. '</a></p>';
	}
	else
	{
		require 'include/parser.inc.php';
		$out['content'] .= '<form action="add.php?topic=' .$_GET['topic']. '" method="post">
		<p>' .text('title'). '</p>
		<p>' .text('name'). '</p>
		<p>' .textarea(). '</p>
		<p>' .submit(). '</p>
		</form>'.
		(isPOST('content')? '<p class="box">' .content(clean($_POST['content'])). '</p>' : '');
	}
}
else if(isGET('reply') && isValidEntry('topic', $_GET['reply']))
{
	$topicEntry = readEntry('topic', $_GET['reply']);
	if($topicEntry['locked'])
	{
		exit;
	}
	$out['subtitle'] = $lang['add'].$lang['reply']. ' : ' .$topicEntry['title'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';
	if(checkBot() && check('name', 0) && check('content', 1, 2000))
	{
		$replyEntry['content'] = clean($_POST['content']);
		$replyEntry['topic'] = $_GET['reply'];
		$reply = newEntry();
		$replyEntry['trip'] = $_POST['name'] === ''? substr($reply, -5) : trip(clean($_POST['name']));
		saveEntry('reply', $reply, $replyEntry);

		$topicEntry['reply'][$reply] = $reply;
		saveEntry('topic', $replyEntry['topic'], $topicEntry);
		
		$_SESSION[$reply] = $reply;

		$out['content'] .= '<p><a href="view.php?topic=' .$_GET['reply']. '&amp;p='. onPage($reply, $topicEntry['reply']). '#' .$reply. '">← ' .$lang['redirect']. ' : ' .$topicEntry['title']. '</a></p>';
	}
	else
	{
		if(isGET('q') && isValidEntry('reply', $_GET['q']))
		{
			$replyEntry = readEntry('reply', $_GET['q']);
			$quote = '[quote=' .$_GET['q']. ']' .$replyEntry['trip']. '[/quote]';
		}
		else
		{
			$quote = '';
		}

		require 'include/parser.inc.php';
		$out['content'] .= '<form action="add.php?reply=' .$_GET['reply']. '" method="post">
		<p>' .text('name'). '</p>
		<p>' .textarea($quote). '</p>
		<p>' .submit(). '</p>
		</form>'.
		(isPOST('content')? '<p class="box">' .content(clean($_POST['content'])). '</p>' : '');
	}
}
else if(isGET('forum') && isAdmin())
{
	$out['subtitle'] = $lang['add'].$lang['forum'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';
	if(checkBot() && check('name') && check('info', 1, 80))
	{
		$forumEntry['name'] = clean($_POST['name']);
		$forumEntry['info'] = clean($_POST['info']);
		$forumEntry['topic'] = array();
		$forumEntry['pinnedTopic'] = array();
		saveEntry('forum', newEntry(), $forumEntry);
		$out['content'] .= '<p><a href="index.php?forum">← ' .$lang['redirect']. ' : ' .$lang['forum']. '</a></p>';
	}
	else
	{
		$out['content'] .= '<form action="add.php?forum" method="post">
		<p>' .text('name'). '</p>
		<p>' .text('info'). '</p>
		<p>' .submit(). '</p>
		</form>';
	}
}
else if(isGET('worker') && isAdmin())
{
	$out['subtitle'] = $lang['add'].$lang['worker'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';
	if(checkBot() && check('password'))
	{
		$config['worker'][hide($_POST['password'])] = clean($_POST['password']);
		saveEntry('config', 'config', $config);
		$out['content'] .= '<p><a href="config.php?worker">← ' .$lang['redirect']. ' : ' .$lang['worker']. '</a></p>';
	}
	else
	{
		$out['content'] .= '<form action="add.php?worker" method="post">
		<p>' .text('password'). '</p>
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
