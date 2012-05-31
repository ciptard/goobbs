<?php

$out['self'] = 'service';
require 'header.php';

$json = null;

if(isGET('reply') && isValidEntry('reply', $_GET['reply']))
{
	$replyEntry = readEntry('reply', $_GET['reply']);
	$json['trip'] = $replyEntry['trip'];
	$json['content'] = summary($replyEntry['content']);
}
else if(isGET('plugin') && isValidHook('service', $_GET['plugin']))
{
	$json = myHook('service', $_GET['plugin']);
}

echo json_encode($json);

?>
