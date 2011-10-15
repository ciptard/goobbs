<?php

$template = 'main';
require 'header.php';
require 'include/manage.inc.php';

if(isGET('topic') && isValidEntry('topic', $_GET['topic']))
{
	require 'include/parser.inc.php';
	$topicEntry = readEntry('topic', $_GET['topic']);
	$forumEntry = readEntry('forum', $topicEntry['forum']);

	//topic view++
	$topicEntry['view'] ++;
	saveEntry('topic', $_GET['topic'], $topicEntry);

	//forum view++
	$forumEntry['view'] ++;
	saveEntry('forum', $topicEntry['forum'], $forumEntry);

	$user = md5($topicEntry['author']);
	$out['subtitle'] = $topicEntry['title'];
	$out['content'] .= '<table>
	<tr class="entryHeader"><td><h1><a href="view.php?forum=' .$topicEntry['forum']. '">' .$forumEntry['name']. '</a> » ' .$out['subtitle']. '</h1></td></tr>
	<tr class="entryHeader"><td>' .manageUser($user). '<a href="view.php?user=' .$user. '">' .$topicEntry['author']. '</a>@' .manageTopic($_GET['topic'], $topicEntry['author']).entryDate($_GET['topic']). '</td></tr>
	<tr><td><p>' .content($topicEntry['content']). '</p>'.
	(!$topicEntry['locked'] && isUser()? '<p><a class="important" href="add.php?reply=' .$_GET['topic']. '">' .$lang['add'].$lang['reply']. '</a></p>' : '').
	hook('afterTopic', $_GET['topic']).'</td></tr>';
	if($topicEntry['reply'])
	{
		$out['content'] .= '<tr id="' .$reply. '" class="entryHeader"><td>' .manageUser($user). '<a href="view.php?user=' .$user. '">' .$replyEntry['author']. '</a>@' .manageReply($reply, $replyEntry['author']).entryDate($reply). '</td></tr>';
		foreach($topicEntry['reply'] as $reply)
		{
			$replyEntry = readEntry('reply', $reply);
			$user = md5($replyEntry['author']);
			$out['content'] .= '<tr><td><p>' .content($replyEntry['content']). '</p>'.
			hook('afterReply', $reply). '</td></tr>';
		}	
	}
	$out['content'] .= '</table>';
	
	$topics = listEntry('topic');
	shuffle($topics);
	$topics = array_chunk($topics, 4);
	$out['content'] .= '<table>
	<tr class="entryHeader"><td>' .$lang['more'].$lang['topic']. '</td>
	<td class="w1">' .$lang['view']. ' / ' .$lang['reply']. '</td>
	<td class="w2">' .$lang['forum']. '</td></tr>';
	foreach($topics[0] as $topic)
	{
		$topicEntry = readEntry('topic', $topic);
		$forumEntry = readEntry('forum', $topicEntry['forum']);
		$out['content'] .= '<tr><td>' .manageTopic($topic, $topicEntry['author']). '<a href="view.php?user=' .md5($topicEntry['author']). '">' .$topicEntry['author']. '</a>@<a href="view.php?topic=' .$topic. '">' .$topicEntry['title']. '</a></td>
		<td>' .$topicEntry['view']. ' / ' .count($topicEntry['reply']). '</td>
		<td><a href="view.php?forum=' .$topicEntry['forum']. '">' .$forumEntry['name']. '</a></td></tr>';
	}
	$out['content'] .= '</table>';
}
else if(isGET('forum') && isValidEntry('forum', $_GET['forum']))
{
	$forumEntry = readEntry('forum', $_GET['forum']);
	$out['subtitle'] = $forumEntry['name'];
	$out['content'] .= '<table>
	<tr class="entryHeader"><td><h1>' .manageForum($_GET['forum']).$out['subtitle']. '</h1></td></tr>
	<tr><td><p>' .$forumEntry['info']. '</p>'.
	(isUser()? '<p><a class="important" href="add.php?topic=' .$_GET['forum']. '">' .$lang['add'].$lang['topic']. '</a></p>' : '').
	hook('afterForum', $_GET['forum']).
	'</td></tr>
	</table>';
	$page = array_chunk(array_merge($forumEntry['pinnedTopic'], array_reverse(array_diff($forumEntry['topic'], $forumEntry['pinnedTopic']))), 8);
	if(!isGET('p') || !isset($page[$_GET['p']-1]))
	{
		$_GET['p'] = 1;
	}
	$i = $_GET['p'] - 1;
	if($page)
	{
		$out['content'] .= '<table>
		<tr class="entryHeader"><td>' .$lang['topic']. '</td>
		<td class="w1">' .$lang['view']. ' / ' .$lang['reply']. '</td>
		<td class="w2">' .$lang['date']. '</td></tr>';
		foreach($page[$i] as $topic)
		{
			$topicEntry = readEntry('topic', $topic);
			$out['content'] .= '<tr><td>' .manageTopic($topic, $topicEntry['author']).(isset($forumEntry['pinnedTopic'][$topic])? '[' .$lang['pinned']. ']':'').($topicEntry['locked']? '[' .$lang['locked']. ']':''). '<a href="view.php?user=' .md5($topicEntry['author']). '">' .$topicEntry['author']. '</a>@<a href="view.php?topic=' .$topic. '">' .$topicEntry['title']. '</a></td>
			<td>' .$topicEntry['view']. ' / ' .count($topicEntry['reply']). '</td>
			<td>' .entryDate($topic). '</td></tr>';
		}
		$out['content'] .= '</table>';
	}
	$out['content'] .= '<div id="page"><ul>' .
	(isset($page[$i-1])? '<li><a href="view.php?forum=' .$_GET['forum']. '&p=' .($_GET['p']-1). '">← ' .$lang['prev']. '</a></li>' : '').
	'<li>' .$lang['page']. ' : ' .$_GET['p']. ' / ' .count($page). '</li>' .
	(isset($page[$i+1])? '<li><a href="view.php?forum=' .$_GET['forum']. '&p=' .($_GET['p']+1). '">' .$lang['next']. ' →</a></li>' : '').
	'</ul></div>';
}
else if(isGET('user') && isValidEntry('user', $_GET['user']))
{
	$userEntry = readEntry('user', $_GET['user']);
	$out['subtitle'] = $userEntry['name'];
	$out['content'] .= '<table>
	<tr class="entryHeader"><td><h1>' .manageUser($_GET['user']).$out['subtitle']. '</h1></td></tr>
	<tr><td>' .$lang['role']. ' : ' .$lang[$userEntry['role']]. '</td></tr>
	<tr><td>' .$lang['count']. ' : ' .(count($userEntry['topic']) + count($userEntry['reply'])). '</td></tr>
	</table>';
	$topics = array_chunk(array_reverse($userEntry['topic']), 4);
	if($topics)
	{
		$out['content'] .= '<table>
		<tr class="entryHeader"><td>' .$lang['new'].$lang['topic']. '</td>
		<td class="w1">' .$lang['view']. ' / ' .$lang['reply']. '</td>
		<td class="w2">' .$lang['date']. '</td></tr>';
		foreach($topics[0] as $topic)
		{
			$topicEntry = readEntry('topic', $topic);
			$out['content'] .= '<tr><td>' .manageTopic($topic, $topicEntry['author']). '<a href="view.php?topic=' .$topic. '">' .$topicEntry['title']. '</a></td>
			<td>' .$topicEntry['view']. ' / ' .count($topicEntry['reply']). '</td>
			<td>' .entryDate($topic). '</td></tr>';
		}
		$out['content'] .= '</table>';
	}
	$replies = array_chunk(array_reverse($userEntry['reply']), 4);
	if($replies)
	{
		$out['content'] .= '<table>
		<tr class="entryHeader"><td>' .$lang['new'].$lang['reply']. '</td>
		<td class="w1">' .$lang['view']. ' / ' .$lang['reply']. '</td>
		<td class="w2">' .$lang['date']. '</td></tr>';
		foreach($replies[0] as $reply)
		{
			$replyEntry = readEntry('reply', $reply);
			$topicEntry = readEntry('topic', $replyEntry['topic']);
			$out['content'] .= '<tr><td>' .manageReply($reply, $replyEntry['author']). '<a href="view.php?topic=' .$replyEntry['topic']. '#' .$reply. '">' .$topicEntry['title']. '</a></td>
			<td>' .$topicEntry['view']. ' / ' .count($topicEntry['reply']). '</td>
			<td>' .entryDate($reply). '</td></tr>';
		}
		$out['content'] .= '</table>';
	}
}
else
{
	exit;
}

require 'footer.php';

?>
