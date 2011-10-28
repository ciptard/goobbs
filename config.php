<?php

$template = 'main';
require 'header.php';

if(isAdmin())
{
	$out['subtitle'] = $lang['config'];
	$out['content'] .= '<h1>' .$out['subtitle']. '</h1>';
	if(checkBot() && checkPass() && check('title') &&
		isPOST('theme') && isEntry($_POST['theme']) && is_file('theme/' .$_POST['theme']. '.thm.css') &&
		isPOST('lang') && isEntry($_POST['lang']) && is_file('lang/' .$_POST['lang']. '.lng.php'))
	{
		$config['admin'] = hide($_POST['password']);
		$config['title'] = clean($_POST['title']);
		$config['theme'] = $_POST['theme'];
		$config['lang'] = $_POST['lang'];
		saveEntry('config', 'config', $config);
		$out['content'] .= '<p><a href="index.php?forum">← ' .$lang['redirect']. ' : ' .$lang['forum']. '</a></p>';
	}
	else
	{
		$themes = fdir('theme');
		$themeOptions = array_combine($themes, $themes);
		$languages = fdir('lang');
		$langOptions = array_combine($languages, $languages);
		$out['content'] .= '<form action="config.php" method="post">
		<p>' .password(). '</p>
		<p>' .text('title', $config['title']). '</p>
		<p>' .select('theme', $themeOptions, $config['theme']). ' ' .select('lang', $langOptions, $config['lang']). '</p>
		<p>' .submit(). '</p>
		</form>';
	}
}
else
{
	exit;
}

require 'footer.php';

?>
