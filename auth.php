<?php

$template = 'main';
require 'header.php';

if(isGET('login'))
{
	$out['subtitle'] = $lang['login'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';
	if(checkBot('token') && check('name') && check('password') && checkBot('captcha') &&
		login($_POST['name'], $_POST['password']))
	{
		session_regenerate_id(true);
		$out['content'] .= '<p><a href = "index.php?forum">← ' .$lang['redirect']. ' : ' .$lang['forum']. '</a></p>';
	}
	else
	{
		$out['content'] .= '<form action = "auth.php?login" method = "post">
		<p>' .text('name'). '</p>
		<p>' .password(). '</p>
		<p>' .captcha(). '</p>
		<p>' .submit(). '</p>
		</form>';
	}
}
else if(isGET('logout') && isUser())
{
	$_SESSION['name'] = $_SESSION['role'] = '';
	$out['subtitle'] = $lang['logout'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>
	<p><a href = "index.php?forum">← ' .$lang['redirect']. ' : ' .$lang['forum']. '</a></p>';
}
else
{
	exit;
}

require 'footer.php';

?>