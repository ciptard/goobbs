<?php

function isAdmin()
{
	return $_SESSION['role'] === 'admin';
}

function isAuthor($entry)
{
	return isset($_SESSION[$entry]);
}

function isWorker()
{
	return $_SESSION['role'] === 'worker' || $_SESSION['role'] === 'admin';
}

function login($password)
{
	global $config;
	$password = hide($password);
	if($password === $config['admin'])
	{
		$_SESSION['role'] = 'admin';
		return true;
	}
	if(isset($config['worker'][$password]))
	{
		$_SESSION['role'] = 'worker';
		return true;
	}
	return false;
}

function trip($name)
{
	$parts = explode('#', $name, 2);
	return $parts[0].(isset($parts[1])? '#' .substr(md5($parts[1]), -5) : '');
}

function quote($reply)
{
	if(isValidEntry('reply', $reply))
	{
		$replyEntry = readEntry('reply', $reply);
		$topicEntry = readEntry('topic', $replyEntry['topic']);
		return '<p><a class="label label-info" href="view.php/topic/' .$replyEntry['topic']. '/p/' .onPage($reply, $topicEntry['reply']). '#' .$reply. '">' .$replyEntry['trip']. '</a></p>';
	}
	else
	{
		return '<p><a class="label label-info">[?]</a></p>';
	}
}

?>
