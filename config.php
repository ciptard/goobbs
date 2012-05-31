<?php

$out['self'] = 'config';
require 'header.php';

if(isGET('main') && isAdmin())
{
	$out['subtitle'] = $lang['config'];
	if(checkBot() && checkPass('password') && check('title') &&
		isPOST('theme') && indir($_POST['theme'], 'theme') &&
		isPOST('lang') && indir($_POST['lang'], 'lang', '.lng.php'))
	{
		$config['admin'] = hide($_POST['password']);
		$config['title'] = clean($_POST['title']);
		$config['theme'] = $_POST['theme'];
		$config['lang'] = $_POST['lang'];
		saveEntry('config', 'config', $config);
		$out['content'] .= '<p><a href="index.php/forum">← ' .$lang['redirect']. ' : ' .$lang['forum']. '</a></p>';
	}
	else
	{
		$themes = fdir('theme');
		$langs = fdir('lang');
		$out['content'] .= form('config.php/main',
			password('password').
			text('title', $config['title']).
			select('theme', array_combine($themes, $themes), $config['theme']).
			select('lang', array_combine($langs, $langs), $config['lang']).
			submit());
	}
}
else if(isGET('worker') && isAdmin())
{
	$out['subtitle'] = $lang['worker'];
	$out['sub_prefix'] = '<a href="add.php/worker"><i class="icon-plus"></i></a>';
	$out['content'] .= '<ul>';
	if($config['worker'])
	{
		foreach($config['worker'] as $key => $password)
		{
			$out['content'] .= '<li><a href="delete.php/worker/' .$key. '"><i class="icon-remove"></i></a>' .$password. '</li>';
		}
	}
	else
	{
		$out['content'] .= '<li>' .$lang['none']. '</li>';
	}
	$out['content'] .= '</ul>';
}
else if(isGET('plugin') && isAdmin())
{
	if(isValidHook('config', $_GET['plugin']))
	{
		$out['subtitle'] = $lang['config']. ' ' .strtolower($_GET['plugin']);
		$out['content'] .= myHook('config', $_GET['plugin']);
	}
	else
	{
		$out['subtitle'] = $lang['plugin'];
		$out['content'] .= '<ul>';
		if($plugins)
		{
			foreach($plugins as $plugin)
			{
				$out['content'] .= '<li>' .$plugin.(isValidHook('config', $plugin)? ' - <a href="config.php/plugin/' .$plugin. '">' .$lang['config']. '</a>' : ''). '</li>';
			}
		}
		else
		{
			$out['content'] .= '<li>' .$lang['none']. '</li>';
		}
		$out['content'] .= '</ul>';
	}
}
else
{
	exit;
}

require 'footer.php';

?>
