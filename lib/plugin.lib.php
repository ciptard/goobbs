<?php

function hook($name, $param = null)
{
	global $plugins;
	$out = '';
	foreach($plugins as $plugin)
	{
		if(isValidHook($name, $plugin))
			$out .= myHook($name, $plugin, $param);
	}
	return $out;
}

function isValidHook($hook, $plugin)
{
	return function_exists($plugin. '_' .$hook);
}

function myHook($hook, $plugin, $param = null)
{
	$hookFunc = $plugin. '_' .$hook;
	return $hookFunc($param);
}

?>
