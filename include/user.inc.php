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

?>
