<?php

function isGET($name)
{
	return isset($_GET[$name]) && is_string($_GET[$name]);
}

function isPOST($name)
{
	return isset($_POST[$name]) && is_string($_POST[$name]);
}

function message($msg)
{
	global $out;
	$out['content'] .= '<div class = "important">' .$msg. '</div>';
}

function password($default = '')
{
	global $lang;
	return $lang['password']. ' <input type = "password" name = "password" value = "' .$default. '"/>';
}

function text($name, $default = '')
{
	global $lang;
	return $lang[$name]. ' <input type = "text" name = "' .$name. '" value = "' .(isPOST($name)? clean($_POST[$name]) : $default). '"/>';
}

function textarea($default = '')
{
	global $lang;
	return $lang['content']. '
	<textarea name = "content" cols = "80" rows = "10">' .(isPOST('content')? clean($_POST['content']) : $default). '</textarea>';
}

function submit()
{
	global $lang;
	$_SESSION['token'] = uniqid();
	return '<input type = "hidden" name = "token" value = "' .$_SESSION['token']. '"/>
	<input type = "submit" value = "' .$lang['confirm']. '"/>';
}

function select($name, $options, $default = '')
{
	global $lang;
	$selected = isPOST($name) && isset($options[$_POST[$name]])? $_POST[$name] : $default;
	$out = $lang[$name]. ' <select name = "' .$name. '">';
	foreach($options as $value => $option)
	{
		$out .= '<option value = "' .$value. '"' .($value === $selected? ' selected = "selected"' : ''). '>' .$option. '</option>';
	}
	$out .= '</select>';
	return $out;
}

function captcha()
{
	global $lang;
	$num1 = rand(1, 10);
	$num2 = rand(1, 10);
	$_SESSION['captcha'] = (string) ($num1 * $num2);
	$lang['captcha'] = $num1. ' x ' .$num2. ' = ?';
	return text('captcha');
}

function check($name, $min = 1, $max = 40)
{
	global $lang;
	if(!isPOST($name))
		return false;
	if(!isset($_POST[$name][$min-1]) || isset($_POST[$name][$max]))
	{
		message($lang[$name].$lang['errorLength']);
		return false;
	}
	return true;
}

function checkBot($name)
{
	global $lang;
	if(!isPOST($name))
		return false;
	if(!isset($_SESSION[$name]) || $_POST[$name] !== $_SESSION[$name])
	{
		message($lang['errorBot']);
		unset($_SESSION[$name]);
		return false;
	}
	return true;
}

?>
