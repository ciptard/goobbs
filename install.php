<?php

require 'include/flatfile.inc.php';

if(!isValidEntry('config', 'config'))
{
	mkdir('data');
	mkdir('data/topic');
	mkdir('data/reply');
	mkdir('data/forum');
	mkdir('data/user');
	mkdir('data/plugin');
	mkdir('data/config');

	$config['title'] = 'goobbs demo';
	$config['theme'] = 'classic';
	$config['lang'] = 'en';
	saveEntry('config', 'config', $config);

	$userEntry['name'] = 'admin';
	$userEntry['role'] = 'admin';
	$userEntry['password'] = hide('demo');
	$userEntry['topic'] = array();
	$userEntry['reply'] = array();
	saveEntry('user', md5($userEntry['name']), $userEntry);

	session_start();
	$_SESSION['name'] = $userEntry['name'];
	$_SESSION['role'] = $userEntry['role'];
}

header('Location: index.php?forum');

?>
