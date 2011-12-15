<?php

function _max($arr, $limit)
{
	$out = array_slice($arr, 0, $limit);
	$min = minIndex($out);
	foreach(array_slice($arr, $limit) as $value)
	{
		if($value > $out[$min])
		{
			$out[$min] = $value;
			$min = minIndex($out);
		}
	}
	rsort($out);
	return $out;
}

function minIndex($arr)
{
	$end = count($arr);
	$key = 0;
	for($i=1; $i<$end; $i++)
	{
		if ($arr[$i] < $arr[$key])
			$key = $i;
	}
	return $key;
}

function redirect($loc)
{
	header('Location: '. $loc);
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

function clean($text)
{
	if(get_magic_quotes_gpc())
		$text = stripslashes($text);
	return htmlspecialchars(trim($text), ENT_QUOTES);
}

function hide($text)
{
	return md5($text.md5($text));
}

function fdir($dir)
{
	$files = array();
	$dh = opendir($dir);
	while(false !== ($file = readdir($dh)))
	{
		if($file !== '.' && $file !== '..')
		{
			$file = explode('.', $file, 2);
			$files[] = $file[0];
		}
	}
	closedir($dh);
	return $files;
}

function indir($file, $dir, $ext = '')
{
	return strpos($file, '/') === false && strpos($file, '.') === false && strpos($file, "\0") === false && file_exists($dir. '/' .$file.$ext);
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
