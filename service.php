<?php

$out['self'] = 'service';
require 'header.php';

if(isGET('reply') && isValidEntry('reply', $_GET['reply']))
{
	$replyEntry = readEntry('reply', $_GET['reply']);
	$json['trip'] = $replyEntry['trip'];
	$json['content'] = summary($replyEntry['content']);
}

echo json_encode($json);

?>