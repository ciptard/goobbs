<?php

function manageTopic($topic)
{
	return (isWorker() || isAuthor($topic)? '<a href="edit.php?topic=' .$topic. '">[!]</a><a href="delete.php?topic=' .$topic. '">[x]</a>' : '').hook('manageTopic', $topic);
}

function manageReply($reply)
{
	return (isWorker() || isAuthor($reply)? '<a href="edit.php?reply=' .$reply. '">[!]</a><a href="delete.php?reply=' .$reply. '">[x]</a>' : '').hook('manageReply', $reply);
}

function manageForum($forum)
{
	return (isAdmin()? '<a href="edit.php?forum=' .$forum. '">[!]</a><a href="delete.php?forum=' .$forum. '">[x]</a>' : '').hook('manageForum', $forum);
}

?>
