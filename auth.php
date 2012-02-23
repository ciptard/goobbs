<?php

$out['self'] = 'auth';
require 'header.php';

if(isGET('login'))
{
	$out['subtitle'] = $lang['login'];
	if(checkBot() && checkPass('password') && login($_POST['password']))
	{
		session_regenerate_id(true);
		$out['content'] .= '<p><a href="index.php/forum">← ' .$lang['redirect']. ' : ' .$lang['forum']. '</a></p>';
	}
	else
	{
		$out['content'] .= form('auth.php/login',
			password('password').
			submit());
	}
}
else if(isGET('logout') && isWorker())
{
	$_SESSION['role'] = '';
	$out['subtitle'] = $lang['logout'];
	$out['content'] .= '<p><a href="index.php/forum">← ' .$lang['redirect']. ' : ' .$lang['forum']. '</a></p>';
}
else
{
	exit;
}

require 'footer.php';

?>
