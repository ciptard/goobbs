<?php

$template = 'main';
require 'header.php';

if(isGET('plugin') && function_exists($_GET['plugin']. '_page'))
{
	$misc = $_GET['plugin']. '_page';
	$out['subtitle'] = $_GET['plugin'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>'.
	$misc();
}
else
{
	exit;
}

require 'footer.php';

?>
