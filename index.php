<?php

$template = 'main';
require 'header.php';
require 'include/manage.inc.php';

if(isGET('new'))
{
	$out['subtitle'] = $lang['new'].$lang['topic']. ' / ' .$lang['reply'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';

	$mixes = array_fill_keys(listEntry('topic'), 'topic') + array_fill_keys(listEntry('reply'), 'reply');
	krsort($mixes);
	$mixes = array_slice($mixes, 0, 8);
	if($mixes)
	{
		$out['content'] .= '<table>
		<tr class="entryHeader"><td>' .$lang['topic']. '</td>
		<td class="w1">' .$lang['view']. ' / ' .$lang['reply']. '</td>
		<td class="w2">' .$lang['date']. '</td></tr>';
		foreach($mixes as $mix => $type)
		{
			if($type === 'topic')
			{
				$topic = $mix;
				$topicEntry = readEntry('topic', $topic);
				$out['content'] .= '<tr><td>' .manageTopic($topic, $topicEntry['author']). '<a href="view.php?user=' .md5($topicEntry['author']). '">' .$topicEntry['author']. '</a>@<a href="view.php?topic=' .$topic. '">' .$topicEntry['title']. '</a></td>
				<td>' .$topicEntry['view']. ' / ' .count($topicEntry['reply']). '</td>
				<td>' .entryDate($topic). '</td></tr>';
			}
			else
			{
				$reply = $mix;
				$replyEntry = readEntry('reply', $reply);
				$topicEntry = readEntry('topic', $replyEntry['topic']);
				$out['content'] .= '<tr><td>' .manageReply($reply, $replyEntry['author']). '<a href="view.php?user=' .md5($replyEntry['author']). '">' .$replyEntry['author']. '</a>@<a href="view.php?topic=' .$replyEntry['topic']. '#' .$reply. '">' .$topicEntry['title']. '</a></td>
				<td>' .$topicEntry['view']. ' / ' .count($topicEntry['reply']). '</td>
				<td>' .entryDate($reply). '</td></tr>';
			}
		}
		$out['content'] .= '</table>';
	}
	else
	{
		$out['content'] .= '<p>' .$lang['none']. '</p>';
	}
}
else if(isGET('forum'))
{
	$out['subtitle'] = $lang['forum'];
	$out['content'] .= '<h1>' .(isAdmin()? '<a class="important" href="add.php?forum">' .$lang['add']. '</a>' : '').$out['subtitle']. '</h1>';
	$forums = listEntry('forum');
	if($forums)
	{
		$out['content'] .= '<table>
		<tr class="entryHeader"><td>' .$lang['forum']. '</td>
		<td class="w1">' .$lang['topic']. '</td>
		<td class="w2">' .$lang['date']. '</td></tr>';
		foreach($forums as $forum)
		{
			$forumEntry = readEntry('forum', $forum);
			$out['content'] .= '<tr><td>' .manageForum($forum). '<a href="view.php?forum=' .$forum. '">' .$forumEntry['name']. '</a> » ' .$forumEntry['info']. '</td>
			<td>' .count($forumEntry['topic']). '</td>
			<td>' .($forumEntry['topic']? entryDate(end($forumEntry['topic'])) : $lang['none']). '</td></tr>';
		}
		$out['content'] .= '</table>';
	}
	else
	{
		$out['content'] .= '<p>' .$lang['none']. '</p>';
	}
}
else if(isGET('user') && isModerator())
{
	$out['subtitle'] = $lang['user'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>
	<div id="user"><ul>';
	$users = listEntry('user');
	foreach($users as $user)
	{
		$userEntry = readEntry('user', $user);
		$out['content'] .= '<li><a href="view.php?user=' .$user. '">' .$userEntry['name']. '</a></li>';
	}
	$out['content'] .= '</ul></div>';
}
else
{
	header('Location: index.php?new');
	exit;
}

require 'footer.php';

?>
