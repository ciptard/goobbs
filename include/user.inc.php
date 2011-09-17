<?php

function isAdmin()
{
	return $_SESSION['role'] === 'admin';
}

function isAuthor($name)
{
	return $_SESSION['name'] === $name;
}

function isModerator()
{
	return $_SESSION['role'] === 'moderator' || $_SESSION['role'] === 'admin';
}

function isUser()
{
	return $_SESSION['role'] === 'user' || $_SESSION['role'] === 'moderator' || $_SESSION['role'] === 'admin';
}

function avatar($user)
{
	return '<img src="http://robohash.org/' .$user. '.jpg?size=80x80&bgset=bg1" alt=""/>';
}

function login($name, $password)
{
	$user = md5(clean($name));
	if(!isValidEntry('user', $user))
	{
		return false;
	}
	$userEntry = readEntry('user', $user);
	if($userEntry['password'] === hide($password))
	{
		$_SESSION['name'] = $userEntry['name'];
		$_SESSION['role'] = $userEntry['role'];
		return true;
	}
	return false;
}

?>
