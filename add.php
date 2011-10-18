<?php

$template = 'main';
require 'header.php';

if(isGET('topic') && isUser() && isValidEntry('forum', $_GET['topic']))
{
	$forumEntry = readEntry('forum', $_GET['topic']);
	$out['subtitle'] = $lang['add'].$lang['topic']. ' : ' .$forumEntry['name'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';
	if(checkBot() && check('title') && check('content', 1, 2000))
	{
		$topicEntry['title'] = clean($_POST['title']);
		$topicEntry['content'] = clean($_POST['content']);
		$topicEntry['author'] = $_SESSION['name'];
		$topicEntry['view'] = 0;
		$topicEntry['forum'] = $_GET['topic'];
		$topicEntry['reply'] = array();
		$topicEntry['locked'] = false;
		$topic = newEntry();
		saveEntry('topic', $topic, $topicEntry);

		$forumEntry['topic'][$topic] = $topic;
		saveEntry('forum', $topicEntry['forum'], $forumEntry);

		$user = md5($topicEntry['author']);
		$userEntry = readEntry('user', $user);
		$userEntry['topic'][$topic] = $topic;
		saveEntry('user', $user, $userEntry);

		$out['content'] .= '<p><a href="view.php?topic=' .$topic. '">← ' .$lang['redirect']. ' : ' .$topicEntry['title']. '</a></p>';
	}
	else
	{
		require 'include/parser.inc.php';
		$out['content'] .= '<form action="add.php?topic=' .$_GET['topic']. '" method="post">
		<p>' .text('title'). '</p>
		<p>' .textarea(). '</p>
		<p>' .submit(). '</p>
		</form>'.
		(check('content', 1, 2000)? '<div class="block">' .content(clean($_POST['content'])). '</div>' : '');
	}
}
else if(isGET('reply') && isUser() && isValidEntry('topic', $_GET['reply']))
{
	$topicEntry = readEntry('topic', $_GET['reply']);
	if($topicEntry['locked'])
	{
		exit;
	}
	$out['subtitle'] = $lang['add'].$lang['reply']. ' : ' .$topicEntry['title'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';
	if(checkBot() && check('content', 1, 2000))
	{
		$replyEntry['author'] = $_SESSION['name'];
		$replyEntry['content'] = clean($_POST['content']);
		$replyEntry['topic'] = $_GET['reply'];
		$reply = newEntry();
		saveEntry('reply', $reply, $replyEntry);

		$topicEntry['reply'][$reply] = $reply;
		saveEntry('topic', $replyEntry['topic'], $topicEntry);

		$user = md5($replyEntry['author']);
		$userEntry = readEntry('user', $user);
		$userEntry['reply'][$reply] = $reply;
		saveEntry('user', $user, $userEntry);

		$out['content'] .= '<p><a href="view.php?topic=' .$_GET['reply']. '#' .$reply. '">← ' .$lang['redirect']. ' : ' .$topicEntry['title']. '</a></p>';
	}
	else
	{
		require 'include/parser.inc.php';
		$out['content'] .= '<form action="add.php?reply=' .$_GET['reply']. '" method="post">
		<p>' .textarea(). '</p>
		<p>' .submit(). '</p>
		</form>'.
		(check('content', 1, 2000)? '<div class="block">' .content(clean($_POST['content'])). '</div>' : '');
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
else if(isGET('user'))
{
	$out['subtitle'] = $lang['add'].$lang['user'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';
	if(checkBot() && check('name') && check('password') && !isValidEntry('user', md5(clean($_POST['name']))))
	{
		$userEntry['name'] = clean($_POST['name']);
		$userEntry['role'] = 'user';
		$userEntry['password'] = hide($_POST['password']);
		$userEntry['topic'] = array();
		$userEntry['reply'] = array();
		saveEntry('user', md5($userEntry['name']), $userEntry);
		$_SESSION['name'] = $userEntry['name'];
		$_SESSION['role'] = $userEntry['role'];
		$out['content'] .= '<p><a href="index.php?forum">← ' .$lang['redirect']. ' : ' .$lang['forum']. '</a></p>';
	}
	else
	{
		$out['content'] .= '<form action="add.php?user" method="post">
		<p>' .text('name'). '</p>
		<p>' .password(). '</p>
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
