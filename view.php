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
			(!$topicEntry['locked']? '<p><a class="important" href="add.php?reply=' .$_GET['topic']. '&q=' .$reply. '">' .$lang['add'].$lang['reply']. '</a></p>' : '').
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
		$out['content'] .= '<tr><td>' .manageTopic($topic).$topicEntry['trip']. ' ' .$lang['started']. ' <a href="view.php?topic=' .$topic. '">' .$topicEntry['title']. '</a></td>
		<td>' .$topicEntry['view']. ' / ' .count($topicEntry['reply']). '</td>
		<td><a href="view.php?forum=' .$topicEntry['forum']. '">' .$forumEntry['name']. '</a></td></tr>';
	}
	$out['content'] .= '</table>';
}
else if(isGET('forum') && isValidEntry('forum', $_GET['forum']))
{
	require 'include/page.inc.php';
	$forumEntry = readEntry('forum', $_GET['forum']);
	$out['subtitle'] = $forumEntry['name'];
	$out['content'] .= '<table>
	<tr class="entryHeader"><td><h1>' .manageForum($_GET['forum']).$out['subtitle']. '</h1></td></tr>
	<tr><td><p>' .$forumEntry['info']. '</p>
	<p><a class="important" href="add.php?topic=' .$_GET['forum']. '">' .$lang['add'].$lang['topic']. '</a></p>'.
	hook('afterForum', $_GET['forum']).
	'</td></tr>
	</table>';
	$pages = array_chunk(array_merge($forumEntry['pinnedTopic'], array_reverse(array_diff($forumEntry['topic'], $forumEntry['pinnedTopic']))), 8);
	$total = count($pages);
	$p = pageNum($total);
	if($total > 0)
	{
		$out['content'] .= '<table>
		<tr class="entryHeader"><td>' .$lang['topic']. '</td>
		<td class="w1">' .$lang['view']. ' / ' .$lang['reply']. '</td>
		<td class="w2">' .$lang['date']. '</td></tr>';
		foreach($pages[$p-1] as $topic)
		{
			$topicEntry = readEntry('topic', $topic);
			$out['content'] .= '<tr><td>' .manageTopic($topic).(isset($forumEntry['pinnedTopic'][$topic])? '<span class="pinned">' .$lang['pinned']. '</span>':'').($topicEntry['locked']? '<span class="locked">' .$lang['locked']. '</span>':'').$topicEntry['trip']. ' ' .$lang['started']. ' <a href="view.php?topic=' .$topic. '">' .$topicEntry['title']. '</a></td>
			<td>' .$topicEntry['view']. ' / ' .count($topicEntry['reply']). '</td>
			<td>' .entryDate($topic). '</td></tr>';
		}
		$out['content'] .= '</table>';
	}
	$out['content'] .= pageControl($p, $total, '?forum=' .$_GET['forum']);
}
else if(isGET('plugin') && function_exists($_GET['plugin']. '_view'))
{
	$misc = $_GET['plugin']. '_view';
	$out['subtitle'] = strtolower($_GET['plugin']);
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>'.
	$misc();
}
else
{
	exit;
}

require 'footer.php';

?>
