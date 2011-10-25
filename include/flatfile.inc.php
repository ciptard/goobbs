<?php

function fdir($dir)
{
	$files = array();
	$dh = opendir($dir);
	while(false !== ($file = readdir($dh)))
	{
		if($file !== '.' && $file !== '..')
		{
			$files[] = $file;
		}
	}
	closedir($dh);
	return $files;
}

function isEntry($file)
{
	return strpos($file, '/') === false && strpos($file, '.') === false && strpos($file, "\0") === false;
}

function readEntry($type, $file)
{
	return json_decode(substr(file_get_contents('data/' .$type. '/' .$file. '.dat.php'), 13), true);
}

function saveEntry($type, $file, $data)
{
	file_put_contents('data/' .$type. '/' .$file. '.dat.php', '<?php exit;?>' . "\n" .json_encode($data));
}

function deleteEntry($type, $file)
{
	unlink('data/' .$type. '/' .$file. '.dat.php');
}

function listEntry($type)
{
	return array_map('pathToEntry', fdir('data/' .$type));
}

function isValidEntry($type, $file)
{
	return isEntry($file) && is_file('data/' .$type. '/' .$file. '.dat.php');
}

function pathToEntry($path)
{
	return substr($path, 0, -8);
}

function newEntry()
{
	return date('Y-m-dHis').substr(uniqid(), -5);
}

function entryDate($file, $pattern = 'Y/m/d H:i')
{
	global $lang;
	$timestamp = strtotime(substr($file, 0, 16));
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
	{
		$text = stripslashes($text);
	}
	return htmlspecialchars(trim($text), ENT_QUOTES);
}

function hide($text)
{
	return md5($text.md5($text));
}

?>
