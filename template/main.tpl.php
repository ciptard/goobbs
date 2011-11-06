<?php

if(!isset($template))
{
	exit;
}
header('Content-Type: text/html; charset=UTF-8');

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"/>
	<meta name="description" content="<?php echo $out['subtitle'];?>"/>
	<title><?php echo $out['subtitle'];?> - <?php echo $config['title'];?></title>
	<link rel="stylesheet" type="text/css" href="theme/<?php echo $config['theme'];?>.css"/>
	<link rel="alternate" type="application/atom+xml" href="feed.php?topic" title="<?php echo $lang['topic'];?> - <?php echo $config['title'];?>"/>
	<link rel="alternate" type="application/atom+xml" href="feed.php?reply" title="<?php echo $lang['reply'];?> - <?php echo $config['title'];?>"/>
	<script src="http://code.jquery.com/jquery.min.js"></script>
	<?php echo hook('head');?>
</head>
<body>
	<div id="container">
		<div id="header"><h2><?php echo $config['title'].hook('header');?></h2></div>
		<div id="menu"><ul>
			<li><a href="index.php?new"><?php echo $lang['new'];?></a></li>
			<li><a href="index.php?forum"><?php echo $lang['forum'];?></a></li>
			<li><a href="search.php"><?php echo $lang['search'];?></a></li>
			<?php echo hook('menu').
			(isAdmin()? '<li><a href="config.php?main">' .$lang['config']. '</a></li>
			<li><a href="config.php?plugin">' .$lang['plugin']. '</a></li>
			<li><a href="config.php?worker">' .$lang['worker']. '</a></li>' : '').
			(isWorker()?
			'<li><a href="auth.php?logout">' .$lang['logout']. ' (' .$lang[$_SESSION['role']]. ')</a></li>' :
			'<li><a href="auth.php?login">' .$lang['login']. '</a></li>');?>
		</ul></div>
		<div id="main"><?php echo $out['content'].hook('main');?></div>
		<div id="footer"><ul>
			<li><?php echo $lang['poweredBy'];?> <a href="http://github.com/taylorchu/goobbs">goobbs</a></li>
			<li><a href="feed.php?topic"><?php echo $lang['feed'];?> (<?php echo $lang['topic'];?>)</a></li>
			<li><a href="feed.php?reply"><?php echo $lang['feed'];?> (<?php echo $lang['reply'];?>)</a></li>
			<?php echo hook('footer');?>
		</ul></div>
	</div>
</body>
</html>
