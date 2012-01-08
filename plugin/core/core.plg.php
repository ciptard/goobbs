<?php

function core_head()
{
	if(!isValidEntry('plugin', 'core'))
		core_init();
	$core = readEntry('plugin', 'core');
	$out = '';
	foreach(array('candy', 'bbcode', 'loadreply', 'loadform', 'imgzoom') as $feature)
	{
		$out .= $core[$feature]? '<script src="plugin/core/js/' .$feature. '.js"></script>' : '';
	}
	return $out;
}

function core_init()
{
	foreach(array('candy', 'bbcode', 'loadreply', 'loadform', 'imgzoom') as $feature)
	{
		$core[$feature] = true;
	}
	saveEntry('plugin', 'core', $core);
}

function core_config()
{
	global $lang;
	if(checkBot() && core_checkConfig())
	{
		saveEntry('plugin', 'core', core_getConfig());
		$out = '<p><a href="config.php/plugin">← ' .$lang['redirect']. ' : ' .$lang['config']. '</a></p>';
	}
	else
	{
		$lang['candy'] = 'eye candy';
		$lang['bbcode'] = 'bbcode editor';
		$lang['loadreply'] = 'hover to read reply';
		$lang['loadform'] = 'quick reply';
		$lang['imgzoom'] = 'zoom image on click';
		
		$core = readEntry('plugin', 'core');
		$options = array('yes' => $lang['yes'], 'no' => $lang['no']);
		$out = '<form action="config.php/plugin/core" method="post">';
		foreach(array('candy', 'bbcode', 'loadreply', 'loadform', 'imgzoom') as $feature)
		{
			$out .= '<p>' .select($feature, $options, $core[$feature]? 'yes' : 'no'). '</p>';
		}
		$out .= '<p>' .submit(). '</p>
		</form>';
	}
	return $out;
}

function core_checkConfig()
{
	foreach(array('candy', 'bbcode', 'loadreply', 'loadform', 'imgzoom') as $feature)
	{
		if(!isPOST($feature) || ($_POST[$feature] !== 'yes' && $_POST[$feature] !== 'no'))
			return false;
	}
	return true;
}

function core_getConfig()
{
	foreach(array('candy', 'bbcode', 'loadreply', 'loadform', 'imgzoom') as $feature)
	{
		$out[$feature] = $_POST[$feature] === 'yes';
	}
	return $out;
}

?>
