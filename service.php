<?php

$out['self'] = 'service';
require 'header.php';

$json = null;

if(isGETValidEntry('reply', 'reply'))
{
	$replyEntry = readEntry('reply', $_GET['reply']);
	$json['trip'] = $replyEntry['trip'];
	$json['content'] = summary($replyEntry['content']);
}
else if(isGETValidHook('service', 'plugin'))
{
	$json = myHook('service', $_GET['plugin']);
}

echo json_encode($json);

?>
