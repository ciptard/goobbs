<?php

if(!isset($template))
{
	exit;
}

require 'include/flatfile.inc.php';
require 'include/ui.inc.php';

if(!isValidEntry('config', 'config'))
{
	redirect('install.php');
}

session_start();
if(!isset($_SESSION['role']))
{
	$_SESSION['role'] = '';
}

//load config
$config = readEntry('config', 'config');

require 'lang/' .$config['lang']. '.lng.php';
require 'include/user.inc.php';
require 'include/plugin.inc.php';

$plugins = fdir('plugin');

foreach($plugins as $plugin)
{
	require 'plugin/' .$plugin. '/' .$plugin. '.plg.php';
}

$out['content'] = '';

?>
