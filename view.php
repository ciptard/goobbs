<?php

$out['self'] = 'view';
require 'header.php';

if(isGET('topic') && isValidEntry('topic', $_GET['topic']))
{
	$topicEntry = readEntry('topic', $_GET['topic']);
	$forumEntry = readEntry('forum', $topicEntry['forum']);

	//topic view++
	$topicEntry['view']++;
	saveEntry('topic', $_GET['topic'], $topicEntry);

	$out['subtitle'] = $topicEntry['title'];
	$out['content'] .= '<ul class="breadcrumb">
		<li><a href="index.php/forum">' .$lang['forum']. '</a> <span class="divider">/</span></li>
		<li><a href="view.php/forum/' .$topicEntry['forum']. '">' .$forumEntry['name']. '</a> <span class="divider">/</span></li>
		<li>' .$out['subtitle']. ' <span class="divider">/</span></li>
		<li>' .$lang['count']. ' (' .(count($topicEntry['reply']) + 1). ')</li>
	</ul>
	<div class="topic well clearfix">
		<div class="span2">
			<p class="user">' .manageTopic($_GET['topic']).$topicEntry['trip']. '</p>
			<p>' .toDate($_GET['topic']). '</p>'.
			hook('profile', $topicEntry['trip']).
		'</div>
		<div class="span9">
			<div class="content">' .content($topicEntry['content']). '</div>'.
			(!$topicEntry['locked']? '<div class="btn-toolbar"><a class="btn btn-primary btn-large" href="add.php/reply/' .$_GET['topic']. '">' .lang('add reply'). '</a></div>' : '').
			hook('afterTopic', $_GET['topic']).
		'</div>
	</div>';
	$total = totalPage($topicEntry['reply']);
	$p = pid($total);
	if($topicEntry['reply'])
	{
		foreach(viewPage($topicEntry['reply'], $p) as $reply)
		{
			$replyEntry = readEntry('reply', $reply);
			$out['content'] .= '<div id="' .$reply. '" class="reply well clearfix">
				<div class="span2">
					<p class="user">' .manageReply($reply).$replyEntry['trip']. '</p>
					<p>' .toDate($reply). '</p>'.
					hook('profile', $replyEntry['trip']).
				'</div>
				<div class="span9">
					<div class="content">' .content($replyEntry['content']). '</div>'.
					(!$topicEntry['locked']? '<div class="btn-toolbar"><a class="btn" href="add.php/reply/' .$_GET['topic']. '/q/' .$reply. '">' .lang('add reply'). '</a></div>' : '').
					hook('afterReply', $reply).
				'</div>
			</div>';
		}
	}
	$out['content'] .= pageControl($p, $total, 'view.php/topic/' .$_GET['topic']).
	'<table class="table table-striped table-bordered table-condensed">
	<thead>
		<tr>
			<th class="span7">' .lang('more topic'). '</th>
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
		$out['content'] .= '<tr>
			<td>' .manageTopic($topic).$topicEntry['trip']. ' ' .$lang['started']. ' <a href="view.php/topic/' .$topic. '">' .$topicEntry['title']. '</a></td>
			<td>' .shortNum($topicEntry['view']). ' / ' .count($topicEntry['reply']). '</td>
			<td><a href="view.php/forum/' .$topicEntry['forum']. '">' .$forumEntry['name']. '</a></td>
		</tr>';
	}
	$out['content'] .= '</tbody>
	</table>';
}
else if(isGET('forum') && isValidEntry('forum', $_GET['forum']))
{
	$forumEntry = readEntry('forum', $_GET['forum']);
	$out['subtitle'] = $forumEntry['name'];
	$out['sub_prefix'] = manageForum($_GET['forum']);
	$out['content'] .= '<div class="well">
		<div class="content">' .$forumEntry['info']. '</div>
		<div class="btn-toolbar"><a class="btn btn-primary btn-large" href="add.php/topic/' .$_GET['forum']. '">' .lang('add topic'). '</a></div>'.
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
			$out['content'] .= '<tr>
				<td>' .manageTopic($topic).(isset($forumEntry['pinnedTopic'][$topic])? '<span class="label label-success">' .$lang['pinned']. '</span>':'').($topicEntry['locked']? '<span class="label label-warning">' .$lang['locked']. '</span>':'').$topicEntry['trip']. ' ' .$lang['started']. ' <a href="view.php/topic/' .$topic. '">' .$topicEntry['title']. '</a></td>
				<td>' .shortNum($topicEntry['view']). ' / ' .count($topicEntry['reply']). '</td>
				<td>' .($topicEntry['reply']? toDate(end($topicEntry['reply'])) : toDate($topic)). '</td>
			</tr>';
		}
		$out['content'] .= '</tbody>
		</table>';
	}
	$out['content'] .= pageControl($p, $total, 'view.php/forum/' .$_GET['forum']);
}
else if(isGET('plugin') && isValidHook('view', $_GET['plugin']))
{
	$out['subtitle'] = strtolower($_GET['plugin']);
	$out['content'] .= myHook('view', $_GET['plugin']);
}
else
{
	redirect('index.php/404');
}

require 'footer.php';

?>
