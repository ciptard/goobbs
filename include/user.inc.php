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

function gravatar($user)
{
	return '<img src = "http://www.gravatar.com/avatar/' .$user. '?d=identicon&f=y" alt = ""/>';
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
