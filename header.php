<?php

if(!isset($out))
{
	exit;
}

require 'lib/db.lib.php';
require 'lib/util.lib.php';

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
require 'lib/user.lib.php';
require 'lib/HTMLform.lib.php';
$plugins = fdir('plugin');
foreach($plugins as $plugin)
{
	require 'plugin/' .$plugin. '/' .$plugin. '.plg.php';
}
if(in_array($out['self'], array('add', 'edit', 'feed', 'index', 'view', 'service')))
{
	hook('bbcode');
	require 'lib/parser.lib.php';
}
if(in_array($out['self'], array('index', 'search', 'view')))
{
	require 'lib/entryLink.lib.php';
}
if($out['self'] === 'view')
{
	require 'lib/page.lib.php';
}

$_GET = fURL();


$out['content'] = '';
$out['sub_prefix'] = '';
$out['baseURL'] = baseURL();

?>
