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
	return date('Y-m-dHis').uniqid();
}

function entryDate($file, $pattern = 'Y/m/d h:i A')
{
	global $lang;
	$timestamp = strtotime(substr($file, 0, 16));
	$diff = time() - $timestamp;
	if($pattern !== 'c' && $diff < 604800) //1 week
	{
		$out = '';
		$len = 2;
		$periods = array($lang['day'] => 86400, $lang['hour'] => 3600, $lang['minute'] => 60, $lang['second'] => 1);
		foreach($periods as $key => $value)
		{
			if($diff >= $value)
			{
				$time = (int) ($diff / $value);
				$out .= $time. ' ' .$key.($time > 1? $lang['plural'] : ''). ' ';
				$diff %= $value;
				$len--;
			}
			if($len === 0) break;
		}
		return $out.$lang['ago'];
	}
	return date($pattern, $timestamp);
}

function clean($text)
{
	if(get_magic_quotes_gpc())
	{
		$text = stripslashes($text);
	}
	return htmlspecialchars($text, ENT_QUOTES);
}

function hide($text)
{
	return md5($text.md5($text));
}

?>
