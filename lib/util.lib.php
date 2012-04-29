<?php

function isGET($name)
{
	return isset($_GET[$name]) && is_string($_GET[$name]);
}

function isPOST($name)
{
	return isset($_POST[$name]) && is_string($_POST[$name]);
}

function fURL()
{
	$out = array();
	if(isset($_SERVER['PATH_INFO']))
	{
		$info = explode('/', $_SERVER['PATH_INFO']);
		$infoNum = count($info);
		for($i=1; $i<$infoNum; $i+=2)
		{
			if($info[$i] !== '')
				$out[$info[$i]] = isset($info[$i+1])? $info[$i+1] : '';
		}
	}
	return $out;
}

function baseURL()
{
	$dir = dirname($_SERVER['SCRIPT_NAME']);
	return 'http://' .$_SERVER['SERVER_NAME'].$dir.($dir === '/'? '' : '/');
}

function _max($arr, $limit)
{
	$size = count($arr);
	if($size <= $limit)
	{
		rsort($arr);
		return $arr;
	}
	$out = array();
	for($i=0; $i<$limit; $i++)
	{	
		$maxI = 0;
		for($j=1; $j<$size; $j++)
		{
			if ($arr[$j] > $arr[$maxI])
				$maxI = $j;
		}
		$out[] = $arr[$maxI];
		unset($arr[$maxI]);
		$size--;
	}
	return $out;
}

function redirect($loc)
{
	header('Location: ' .baseURL().$loc);
	exit;
}

function onPage($item, $items)
{
	return (int) (array_search($item, array_values($items), true) / 8) + 1;
}

function shortNum($int)
{
	if($int < 1000)
		return $int;
	else
		return round($int/1000, 1). 'K';
}

function toDate($id, $pattern = 'Y/m/d H:i')
{
	global $lang;
	$timestamp = strtotime(substr($id, 0, 16));
	$diff = time() - $timestamp;
	if($pattern !== 'c' && $diff < 604800) //1 week
	{
		$periods = array(86400 => $lang['day'], 3600 => $lang['hour'], 60 => $lang['minute'], 1 => $lang['second']);
		foreach($periods as $key => $value)
		{
			if($diff >= $key)
			{
				$num = (int) ($diff / $key);
				return $num. ' ' .$value.($num > 1? $lang['plural'] : ''). ' ' .$lang['ago'];
			}
		}
	}
	return date($pattern, $timestamp);
}

function hook($name, $param = null)
{
	global $plugins;
	$out = '';
	foreach($plugins as $plugin)
	{
		$hookName = $plugin. '_' .$name;
		if(function_exists($hookName))
			$out .= $hookName($param);
	}
	return $out;
}

?>
