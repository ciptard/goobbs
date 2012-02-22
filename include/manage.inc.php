<?php

function manageTopic($topic)
{
	return (isWorker() || isAuthor($topic)? '<a href="edit.php/topic/' .$topic. '"><i class="icon-edit"></i></a><a href="delete.php/topic/' .$topic. '"><i class="icon-remove"></i></a>' : '').hook('manageTopic', $topic);
}

function manageReply($reply)
{
	return (isWorker() || isAuthor($reply)? '<a href="edit.php/reply/' .$reply. '"><i class="icon-edit"></i></a><a href="delete.php/reply/' .$reply. '"><i class="icon-remove"></i></a>' : '').hook('manageReply', $reply);
}

function manageForum($forum)
{
	return (isAdmin()? '<a href="edit.php/forum/' .$forum. '"><i class="icon-edit"></i></a><a href="delete.php/forum/' .$forum. '"><i class="icon-remove"></i></a>' : '').hook('manageForum', $forum);
}

?>
