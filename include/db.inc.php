<?php

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
		$tmp = '"' .$key. '":';
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

?>