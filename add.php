<?php

$out['self'] = 'add';
require 'header.php';

if(isGET('topic') && isValidEntry('forum', $_GET['topic']))
{
	$forumEntry = readEntry('forum', $_GET['topic']);
	$out['subtitle'] = $lang['add'].$lang['topic']. ' : ' .$forumEntry['name'];
	if(checkBot() && check('name', 0, 20) && check('title') && check('content', 1, 2000))
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

		$out['content'] .= '<p><a href="view.php/topic/' .$topic. '">← ' .$lang['redirect']. ' : ' .$topicEntry['title']. '</a></p>';
	}
	else
	{
		require 'include/parser.inc.php';
		$out['content'] .= form('add.php/topic/' .$_GET['topic'], 
			text('title').
			text('name').
			textarea('content').
			submit()).
		preview('content');
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
	if(checkBot() && check('name', 0, 20) && check('content', 1, 2000))
	{
		$replyEntry['content'] = clean($_POST['content']);
		$replyEntry['topic'] = $_GET['reply'];
		$reply = newEntry();
		$replyEntry['trip'] = $_POST['name'] === ''? substr($reply, -5) : trip(clean($_POST['name']));
		saveEntry('reply', $reply, $replyEntry);

		$topicEntry['reply'][$reply] = $reply;
		saveEntry('topic', $replyEntry['topic'], $topicEntry);
		
		$_SESSION[$reply] = $reply;

		$out['content'] .= '<p><a href="view.php/topic/' .$_GET['reply']. '/p/' .onPage($reply, $topicEntry['reply']). '#' .$reply. '">← ' .$lang['redirect']. ' : ' .$topicEntry['title']. '</a></p>';
	}
	else
	{
		require 'include/parser.inc.php';
		$out['content'] .= form('add.php/reply/' .$_GET['reply'],
			text('name').
			textarea('content', isGET('q') && isValidEntry('reply', $_GET['q'])? '[quote]' .$_GET['q']. '[/quote]' : '').
			submit()).
		preview('content');
	}
}
else if(isGET('forum') && isAdmin())
{
	$out['subtitle'] = $lang['add'].$lang['forum'];
	if(checkBot() && check('name') && check('info', 1, 80))
	{
		$forumEntry['name'] = clean($_POST['name']);
		$forumEntry['info'] = clean($_POST['info']);
		$forumEntry['topic'] = array();
		$forumEntry['pinnedTopic'] = array();
		$forum = newEntry();
		saveEntry('forum', $forum, $forumEntry);
		
		$forums = readEntry('config', 'forumOrder');
		$forums[$forum] = $forum;
		saveEntry('config', 'forumOrder', $forums);
		
		$out['content'] .= '<p><a href="index.php/forum">← ' .$lang['redirect']. ' : ' .$lang['forum']. '</a></p>';
	}
	else
	{
		$out['content'] .= form('add.php/forum',
			text('name').
			text('info').
			submit());
	}
}
else if(isGET('worker') && isAdmin())
{
	$out['subtitle'] = $lang['add'].$lang['worker'];
	if(checkBot() && check('password'))
	{
		$config['worker'][hide($_POST['password'])] = clean($_POST['password']);
		saveEntry('config', 'config', $config);
		$out['content'] .= '<p><a href="config.php/worker">← ' .$lang['redirect']. ' : ' .$lang['worker']. '</a></p>';
	}
	else
	{
		$out['content'] .= form('add.php/worker',
			text('password').
			submit());
	}
}
else
{
	exit;
}

require 'footer.php';

?>
