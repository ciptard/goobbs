<?php

function manageTopic($topic, $author)
{
	return (isModerator() || isAuthor($author)? '<a href="edit.php?topic=' .$topic. '">[!]</a>' : '').(isModerator()? '<a href="delete.php?topic=' .$topic. '">[x]</a>' : '').hook('manageTopic', $topic);
}

function manageReply($reply, $author)
{
	return (isModerator() || isAuthor($author)? '<a href="edit.php?reply=' .$reply. '">[!]</a>' : '').(isModerator()? '<a href="delete.php?reply=' .$reply. '">[x]</a>' : '').hook('manageReply', $reply);
}

function manageForum($forum)
{
	return (isAdmin()? '<a href="edit.php?forum=' .$forum. '">[!]</a><a href="delete.php?forum=' .$forum. '">[x]</a>' : '').hook('manageForum', $forum);
}

function manageUser($user)
{
	return (isAdmin() || $user === md5($_SESSION['name'])? '<a href="edit.php?user=' .$user. '">[!]</a>' : '').(isAdmin() && $user !== md5('admin')? '<a href="delete.php?user=' .$user. '">[x]</a>' : '').hook('manageUser', $user);
}

?>
