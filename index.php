<?php

$out['self'] = 'index';
require 'header.php';
require 'include/manage.inc.php';

if(isGET('new'))
{
	$out['subtitle'] = $lang['new'];

	$mixes = _max(array_merge(listEntry('topic'), listEntry('reply')), 8);
	if($mixes)
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
		foreach($mixes as $mix)
		{
			if(isValidEntry('topic', $mix))
			{
				$topic = $mix;
				$topicEntry = readEntry('topic', $topic);
				$out['content'] .= '<tr>
					<td>' .manageTopic($topic).$topicEntry['trip']. ' ' .$lang['started']. ' <a href="view.php/topic/' .$topic. '">' .$topicEntry['title']. '</a></td>
					<td>' .shortNum($topicEntry['view']). ' / ' .count($topicEntry['reply']). '</td>
					<td>' .toDate($topic). '</td>
				</tr>';
			}
			else
			{
				$reply = $mix;
				$replyEntry = readEntry('reply', $reply);
				$topicEntry = readEntry('topic', $replyEntry['topic']);
				$out['content'] .= '<tr>
					<td>' .manageReply($reply).$replyEntry['trip']. ' ' .$lang['replied']. ' <a href="view.php/topic/' .$replyEntry['topic']. '/p/'. onPage($reply, $topicEntry['reply']). '#' .$reply. '">' .$topicEntry['title']. '</a></td>
					<td>' .shortNum($topicEntry['view']). ' / ' .count($topicEntry['reply']). '</td>
					<td>' .toDate($reply). '</td>
				</tr>';
			}
		}
		$out['content'] .= '</tbody>
		</table>';
	}
	else
	{
		$out['content'] .= '<p>' .$lang['none']. '</p>';
	}
}
else if(isGET('forum'))
{
	$out['subtitle'] = $lang['forum'];
	$out['sub_prefix'] = isAdmin()? '<a href="add.php/forum"><i class="icon-plus"></i></a>' : '';
	$forums = readEntry('config', 'forumOrder');
	if($forums)
	{
		if(isAdmin() && checkBot())
		{
			foreach($forums as $forum)
			{
				$order[$forum] = isPOST($forum)? $_POST[$forum] : '0';	
			}
			asort($order);
			$order = array_keys($order);
			$forums = array_combine($order, $order);
			saveEntry('config', 'forumOrder', $forums);
		}
		
		$num = range(1, count($forums));
		$options = array_combine($num, $num);
		
		$controlStr = '';
		$out['content'] .= '<table class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<th class="span7">' .$lang['forum']. '</th>
				<th class="span2">' .$lang['topic']. '</th>
				<th class="span3">' .$lang['date']. '</th>
			</tr>
		</thead>
		<tbody>';
		foreach(array_values($forums) as $key => $forum)
		{
			$forumEntry = readEntry('forum', $forum);
			$lang[$forum] = $forumEntry['name'];
			$controlStr .= select($forum, $options, $key+1);
			$out['content'] .= '<tr>
				<td>' .manageForum($forum). '<a href="view.php/forum/' .$forum. '">' .$forumEntry['name']. '</a> » ' .$forumEntry['info']. '</td>
				<td>' .count($forumEntry['topic']). '</td>
				<td>' .($forumEntry['topic']? toDate(end($forumEntry['topic'])) : $lang['none']). '</td>
			</tr>';
		}
		$out['content'] .= '</tbody>
		</table>'.
		(isAdmin()? form('index.php/forum', 
			$controlStr.
			submit()) : '');
	}
	else
	{
		$out['content'] .= '<p>' .$lang['none']. '</p>';
	}
}
else if(isGET('404'))
{
	$out['subtitle'] = 'HTTP 404';
	$out['content'] .= '<p>' .$lang['notFound']. '</p>';
}
else
{
	redirect('index.php/new');
}

require 'footer.php';

?>
