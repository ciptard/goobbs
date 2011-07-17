<?php

function hook($name, $param = null)
{
	global $plugins;
	$out = '';
	foreach($plugins as $plugin)
	{
		$hookName = $plugin. '_' .$name;
		if(function_exists($hookName))
		{
			$out .= $hookName($param);
		}
	}
	return $out;
}

?>
