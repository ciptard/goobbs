<?php

$out['self'] = 'view';
require 'header.php';
require 'include/manage.inc.php';

if(isGET('topic') && isValidEntry('topic', $_GET['topic']))
{
	require 'include/parser.inc.php';
	require 'include/page.inc.php';
	
	$topicEntry = readEntry('topic', $_GET['topic']);
	$forumEntry = readEntry('forum', $topicEntry['forum']);

	//topic view++
	$topicEntry['view']++;
	saveEntry('topic', $_GET['topic'], $topicEntry);

	$out['subtitle'] = $topicEntry['title'];
	$out['content'] .= '
	<ul class="breadcrumb">
		<li><a href="view.php/forum/' .$topicEntry['forum']. '">' .$forumEntry['name']. '</a> <span class="divider">/</span></li>
		<li>' .$out['subtitle']. ' <span class="divider">/</span></li>
		<li>' .$lang['count']. ' (' .(count($topicEntry['reply']) + 1). ')</li>
	</ul>
	<div class="topic">
	<div class="span3"><p class="user">' .manageTopic($_GET['topic']).$topicEntry['trip']. '</p>
	<p>' .toDate($_GET['topic']). '</p></div>
	<div class="span8"><div class="content">' .content($topicEntry['content']). '</div>'.
	(!$topicEntry['locked']? '<p><a class="btn" href="add.php/reply/' .$_GET['topic']. '">' .$lang['add'].$lang['reply']. '</a></p>' : '').
	hook('afterTopic', $_GET['topic']). '</div>
	</div>';
	$total = totalPage($topicEntry['reply']);
	$p = pid($total);
	if($topicEntry['reply'])
	{
		foreach(viewPage($topicEntry['reply'], $p) as $reply)
		{
			$replyEntry = readEntry('reply', $reply);
			$out['content'] .= '<div id="' .$reply. '" class="reply">
			<div class="span3"><p class="user">' .manageReply($reply).$replyEntry['trip']. '</p>
			<p>' .toDate($reply). '</p></div>
			<div class="span8"><div class="content">' .content($replyEntry['content']). '</div>'.
			(!$topicEntry['locked']? '<p><a class="btn" href="add.php/reply/' .$_GET['topic']. '/q/' .$reply. '">' .$lang['add'].$lang['reply']. '</a></p>' : '').
			hook('afterReply', $reply). '</div>
			</div>';
		}
	}
	$out['content'] .= pageControl($p, $total, 'view.php/topic/' .$_GET['topic']).
	'<table class="table table-striped table-bordered table-condensed">
	<thead>
		<tr>
			<th class="span7">' .$lang['more'].$lang['topic']. '</th>
			<th class="span2">' .$lang['view']. ' / ' .$lang['reply']. '</th>
			<th class="span3">' .$lang['forum']. '</th>
		</tr>
	</thead>
	<tbody>';
	$topics = listEntry('topic');
	shuffle($topics);
	foreach(array_slice($topics, 0, 4) as $topic)
	{
		$topicEntry = readEntry('topic', $topic);
		$forumEntry = readEntry('forum', $topicEntry['forum']);
		$out['content'] .= '<tr><td>' .manageTopic($topic).$topicEntry['trip']. ' ' .$lang['started']. ' <a href="view.php/topic/' .$topic. '">' .$topicEntry['title']. '</a></td>
		<td>' .shortNum($topicEntry['view']). ' / ' .count($topicEntry['reply']). '</td>
		<td><a href="view.php/forum/' .$topicEntry['forum']. '">' .$forumEntry['name']. '</a></td></tr>';
	}
	$out['content'] .= '</tbody>
	</table>';
}
else if(isGET('forum') && isValidEntry('forum', $_GET['forum']))
{
	require 'include/page.inc.php';
	$forumEntry = readEntry('forum', $_GET['forum']);
	$out['subtitle'] = $forumEntry['name'];
	$out['content'] .= '
	<div class="hero-unit">
		<h1>' .manageForum($_GET['forum']).$out['subtitle']. '</h1>
		<p>' .$forumEntry['info']. '</p>
		<p><a class="btn btn-primary btn-large" href="add.php/topic/' .$_GET['forum']. '">' .$lang['add'].$lang['topic']. '</a></p>'.
		hook('afterForum', $_GET['forum']).
	'</div>';
	$topics = array_merge($forumEntry['pinnedTopic'], array_reverse(array_diff($forumEntry['topic'], $forumEntry['pinnedTopic'])));
	$total = totalPage($topics);
	$p = pid($total);
	if($topics)
	{
		$out['content'] .= '<table class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<th class="span7">' .$lang['topic']. '</th>
				<th class="span2">' .$lang['view']. ' / ' .$lang['reply']. '</th>
				<th class="span3">' .$lang['date']. '</th>
			</tr>
		</thead>
		<tbody>';
		foreach(viewPage($topics, $p) as $topic)
		{
			$topicEntry = readEntry('topic', $topic);
			$out['content'] .= '<tr><td>' .manageTopic($topic).(isset($forumEntry['pinnedTopic'][$topic])? '<span class="label label-success">' .$lang['pinned']. '</span>':'').($topicEntry['locked']? '<span class="label label-warning">' .$lang['locked']. '</span>':'').$topicEntry['trip']. ' ' .$lang['started']. ' <a href="view.php/topic/' .$topic. '">' .$topicEntry['title']. '</a></td>
			<td>' .shortNum($topicEntry['view']). ' / ' .count($topicEntry['reply']). '</td>
			<td>' .toDate($topic). '</td></tr>';
		}
		$out['content'] .= '</tbody>
		</table>';
	}
	$out['content'] .= pageControl($p, $total, 'view.php/forum/' .$_GET['forum']);
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
	redirect('index.php/404');
}

require 'footer.php';

?>
