<?php

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

function readEntry($type, $file)
{
	return json_decode(substr(file_get_contents('data/' .$type. '/' .$file. '.dat.php'), 13), true);
}

function _json_encode($var)
{
	$list = array();
	foreach($var as $key => $value)
	{
		list($key, $value) = str_replace(
			array('"', "\r", "\n", "\t"), 
			array('\"', '\r', '\n', '\t'), 
			array($key, $value));
		$tmp = '"' .$key. '": ';
		if(is_array($value))
			$tmp .= _json_encode($value);
		else
			$tmp .= '"' .$value. '"';
		$list[] = $tmp;
	}
	return '{' .implode(',', $list). '}';
}

function saveEntry($type, $file, $data)
{
	file_put_contents('data/' .$type. '/' .$file. '.dat.php', '<?php exit;?>' . "\n" ._json_encode($data));
}

function deleteEntry($type, $file)
{
	unlink('data/' .$type. '/' .$file. '.dat.php');
}

function listEntry($type)
{
	return fdir('data/' .$type);
}

function isValidEntry($type, $file)
{
	return indir($file, 'data/' .$type, '.dat.php');
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
