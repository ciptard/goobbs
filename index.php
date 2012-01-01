<?php

$out['self'] = 'index';
require 'header.php';
require 'include/manage.inc.php';

if(isGET('new'))
{
	$out['subtitle'] = $lang['new'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';

	$mixes = _max(array_merge(listEntry('topic'), listEntry('reply')), 8);
	if($mixes)
	{
		$out['content'] .= '<table>
		<tr class="th"><td>' .$lang['topic']. '</td>
		<td class="w1">' .$lang['view']. ' / ' .$lang['reply']. '</td>
		<td class="w2">' .$lang['date']. '</td></tr>';
		foreach($mixes as $mix)
		{
			if(isValidEntry('topic', $mix))
			{
				$topic = $mix;
				$topicEntry = readEntry('topic', $topic);
				$out['content'] .= '<tr><td>' .manageTopic($topic).$topicEntry['trip']. ' ' .$lang['started']. ' <a href="view.php/topic/' .$topic. '">' .$topicEntry['title']. '</a></td>
				<td>' .shortNum($topicEntry['view']). ' / ' .count($topicEntry['reply']). '</td>
				<td>' .toDate($topic). '</td></tr>';
			}
			else
			{
				$reply = $mix;
				$replyEntry = readEntry('reply', $reply);
				$topicEntry = readEntry('topic', $replyEntry['topic']);
				$out['content'] .= '<tr><td>' .manageReply($reply).$replyEntry['trip']. ' ' .$lang['replied']. ' <a href="view.php/topic/' .$replyEntry['topic']. '/p/'. onPage($reply, $topicEntry['reply']). '#' .$reply. '">' .$topicEntry['title']. '</a></td>
				<td>' .shortNum($topicEntry['view']). ' / ' .count($topicEntry['reply']). '</td>
				<td>' .toDate($reply). '</td></tr>';
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
	$out['content'] .= '<h1>' .(isAdmin()? '<a href="add.php/forum">[+]</a>' : '').$out['subtitle']. '</h1>';
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
		
		$out['content'] .= (isAdmin()? '<form action="index.php/forum" method="post">' : ''). '<table>
		<tr class="th"><td>' .$lang['forum']. '</td>
		<td class="w1">' .$lang['topic']. '</td>
		<td class="w2">' .$lang['date']. '</td></tr>';
		foreach(array_values($forums) as $key => $forum)
		{
			$forumEntry = readEntry('forum', $forum);
			$lang[$forum] = '';
			$out['content'] .= '<tr><td>' .(isAdmin()? select($forum, $options, $key+1) : '').manageForum($forum). '<a href="view.php/forum/' .$forum. '">' .$forumEntry['name']. '</a> » ' .$forumEntry['info']. '</td>
			<td>' .count($forumEntry['topic']). '</td>
			<td>' .($forumEntry['topic']? toDate(end($forumEntry['topic'])) : $lang['none']). '</td></tr>';
		}
		$out['content'] .= '</table>'.
		(isAdmin()? '<p>' .submit(). '</p>
		</form>' : '');
	}
	else
	{
		$out['content'] .= '<p>' .$lang['none']. '</p>';
	}
}
else if(isGET('404'))
{
	$out['subtitle'] = 'HTTP 404';
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>
	<p>' .$lang['notFound']. '</p>';
}
else
{
	redirect('index.php/new');
}

require 'footer.php';

?>
