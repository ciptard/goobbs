<?php

$template = 'main';
require 'header.php';
require 'include/manage.inc.php';

if(isGET('new'))
{
	$out['subtitle'] = $lang['new'].$lang['topic']. ' / ' .$lang['reply'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';

	$mixes = array_merge(array_fill_keys(listEntry('topic'), 'topic'), array_fill_keys(listEntry('reply'), 'reply'));
	krsort($mixes);
	$mixes = array_slice($mixes, 0, 8);
	if($mixes)
	{
		$out['content'] .= '<table>
		<tr class="th"><td>' .$lang['topic']. '</td>
		<td class="w1">' .$lang['view']. ' / ' .$lang['reply']. '</td>
		<td class="w2">' .$lang['date']. '</td></tr>';
		foreach($mixes as $mix => $type)
		{
			if($type === 'topic')
			{
				$topic = $mix;
				$topicEntry = readEntry('topic', $topic);
				$out['content'] .= '<tr><td>' .manageTopic($topic).$topicEntry['trip']. ' ' .$lang['started']. ' <a href="view.php?topic=' .$topic. '">' .$topicEntry['title']. '</a></td>
				<td>' .$topicEntry['view']. ' / ' .count($topicEntry['reply']). '</td>
				<td>' .entryDate($topic). '</td></tr>';
			}
			else
			{
				$reply = $mix;
				$replyEntry = readEntry('reply', $reply);
				$topicEntry = readEntry('topic', $replyEntry['topic']);
				$out['content'] .= '<tr><td>' .manageReply($reply).$replyEntry['trip']. ' ' .$lang['replied']. ' <a href="view.php?topic=' .$replyEntry['topic']. '#' .$reply. '">' .$topicEntry['title']. '</a></td>
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
	$out['content'] .= '<h1>' .(isAdmin()? '<a href="add.php?forum">[+]</a>' : '').$out['subtitle']. '</h1>';
	$forums = listEntry('forum');
	if($forums)
	{
		$out['content'] .= '<table>
		<tr class="th"><td>' .$lang['forum']. '</td>
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
else if(isGET('worker') && isAdmin())
{
	$out['subtitle'] = $lang['worker'];
	$out['content'] .= '<h1><a href="add.php?worker">[+]</a>' .$out['subtitle']. '</h1>';
	if($config['worker'])
	{
		$out['content'] .= '<div id="user"><ul>';
		foreach($config['worker'] as $key => $password)
		{
			$out['content'] .= '<li><a href="delete.php?worker=' .$key. '">' .$password. '</a></li>';
		}
		$out['content'] .= '</ul></div>';
	}
	else
	{
		$out['content'] .= '<p>' .$lang['none']. '</p>';
	}
}
else
{
	header('Location: index.php?new');
	exit;
}

require 'footer.php';

?>
