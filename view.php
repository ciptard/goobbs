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
	$topicEntry['view']++;
	saveEntry('topic', $_GET['topic'], $topicEntry);

	$out['subtitle'] = $topicEntry['title'];
	$out['content'] .= '<table>
	<tr class="entryHeader"><td colspan="2"><h1><a href="view.php?forum=' .$topicEntry['forum']. '">' .$forumEntry['name']. '</a> » ' .$out['subtitle']. '</h1></td></tr>
	<tr><td class="w2"><p>' .manageTopic($_GET['topic']).$topicEntry['trip']. '</p>
	<p>' .entryDate($_GET['topic']). '</p></td>
	<td><p>' .content($topicEntry['content']). '</p>'.
	(!$topicEntry['locked']? '<p><a class="important" href="add.php?reply=' .$_GET['topic']. '">' .$lang['add'].$lang['reply']. '</a></p>' : '').
	hook('afterTopic', $_GET['topic']).'</td></tr>';
	if($topicEntry['reply'])
	{
		foreach($topicEntry['reply'] as $reply)
		{
			$replyEntry = readEntry('reply', $reply);
			$out['content'] .= '<tr id="' .$reply. '"><td class="w2"><p>' .manageReply($reply).$replyEntry['trip']. '</p>
			<p>' .entryDate($reply). '</p></td>
			<td><p>' .content($replyEntry['content']). '</p>'.
			(!$topicEntry['locked']? '<p><a class="important" href="add.php?reply=' .$_GET['topic']. '&quote=' .$reply. '">' .$lang['add'].$lang['reply']. '</a></p>' : '').
			hook('afterReply', $reply). '</td></tr>';
		}
	}
	$out['content'] .= '</table>';
	
	$topics = listEntry('topic');
	shuffle($topics);
	$topics = array_slice($topics, 0, 4);
	$out['content'] .= '<table>
	<tr class="entryHeader"><td>' .$lang['more'].$lang['topic']. '</td>
	<td class="w1">' .$lang['view']. ' / ' .$lang['reply']. '</td>
	<td class="w2">' .$lang['forum']. '</td></tr>';
	foreach($topics as $topic)
	{
		$topicEntry = readEntry('topic', $topic);
		$forumEntry = readEntry('forum', $topicEntry['forum']);
		$out['content'] .= '<tr><td>' .manageTopic($topic).$topicEntry['trip']. ' - <a href="view.php?topic=' .$topic. '">' .$topicEntry['title']. '</a></td>
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
	<tr><td><p>' .$forumEntry['info']. '</p>
	<p><a class="important" href="add.php?topic=' .$_GET['forum']. '">' .$lang['add'].$lang['topic']. '</a></p>'.
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
			$out['content'] .= '<tr><td>' .manageTopic($topic).(isset($forumEntry['pinnedTopic'][$topic])? '[' .$lang['pinned']. ']':'').($topicEntry['locked']? '[' .$lang['locked']. ']':'').$topicEntry['trip']. ' - <a href="view.php?topic=' .$topic. '">' .$topicEntry['title']. '</a></td>
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
else
{
	exit;
}

require 'footer.php';

?>
