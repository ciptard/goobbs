<?php

function manageTopic($topic, $author)
{
	global $lang;
	return (isModerator() || isAuthor($author)? '<a href = "edit.php?topic=' .$topic. '">[' .$lang['edit']. ']</a><a href = "delete.php?topic=' .$topic. '">[' .$lang['delete']. ']</a>' : '').hook('manageTopic', $topic);
}

function manageReply($reply, $author)
{
	global $lang;
	return (isModerator() || isAuthor($author)? '<a href = "edit.php?reply=' .$reply. '">[' .$lang['edit']. ']</a><a href = "delete.php?reply=' .$reply. '">[' .$lang['delete']. ']</a>' : '').hook('manageReply', $reply);
}

function manageForum($forum)
{
	global $lang;
	return (isAdmin()? '<a href = "edit.php?forum=' .$forum. '">[' .$lang['edit']. ']</a><a href = "delete.php?forum=' .$forum. '">[' .$lang['delete']. ']</a>' : '').hook('manageForum', $forum);
}

function manageUser($user)
{
	global $lang;
	return (isAdmin() || $user === md5($_SESSION['name'])? '<a href = "edit.php?user=' .$user. '">[' .$lang['edit']. ']</a>' : '').(isAdmin() && $user !== md5('admin')? '<a href = "delete.php?user=' .$user. '">[' .$lang['delete']. ']</a>' : '').hook('manageUser', $user);
}

?>
