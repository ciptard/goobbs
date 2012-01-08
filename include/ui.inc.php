<?php

function isGET($name)
{
	return isset($_GET[$name]) && is_string($_GET[$name]);
}

function isPOST($name)
{
	return isset($_POST[$name]) && is_string($_POST[$name]);
}

function err($eid, $msg)
{
	if (isset($_SESSION[$eid]))
	{
		unset($_SESSION[$eid]);
		return '<span class="msg">' .$msg. '</span>';
	}
	return '';
}

function password($name)
{
	global $lang;
	return $lang[$name]. ' <input type="password" name="' .$name. '"/>  <input type="password" name="' .$name. 'Confirm"/>' .err($name. 'ErrNotMatch', $lang['errNotMatch']);
}

function text($name, $default = '')
{
	global $lang;
	return $lang[$name]. ' <input type="text" name="' .$name. '" value="' .(isPOST($name)? clean($_POST[$name]) : $default). '"/>' .err($name. 'ErrLen', $lang['errLen']);
}

function textarea($name, $default = '')
{
	global $lang;
	return $lang[$name].err($name. 'ErrLen', $lang['errLen']). '
	<textarea name="' .$name. '" cols="80" rows="10">' .(isPOST($name)? clean($_POST[$name]) : $default). '</textarea>';
}

function submit()
{
	global $lang;
	$num1 = rand(1, 10);
	$num2 = rand(1, 10);
	$_SESSION['captcha'] = (string) ($num1 * $num2);
	return $num1. ' x ' .$num2. ' = ? <input type="text" name="captcha" style="width: 50px;"/> <input type="submit" value="' .$lang['confirm']. '"/>' .err('ErrBot', $lang['errBot']);
}

function select($name, $options, $default = '')
{
	global $lang;
	$selected = isPOST($name) && isset($options[$_POST[$name]])? $_POST[$name] : $default;
	$out = $lang[$name]. ' <select name="' .$name. '">';
	foreach($options as $value => $option)
	{
		$out .= '<option value="' .$value. '"' .($value == $selected? ' selected="selected"' : ''). '>' .$option. '</option>';
	}
	$out .= '</select>';
	return $out;
}

function check($name, $min = 1, $max = 40)
{
	if(!isPOST($name))
		return false;
	$len = strlen(trim($_POST[$name]));
	if($len >= $min && $len <= $max)
		return true;
	$_SESSION[$name. 'ErrLen'] = '';
	return false;
}

function checkPass($name)
{
	if(check($name) && isPOST($name. 'Confirm') && $_POST[$name] === $_POST[$name. 'Confirm'])
		return true;
	$_SESSION[$name. 'ErrNotMatch'] = '';
	return false;
}

function checkBot()
{
	if(!isPOST('captcha'))
		return false;
	if(isset($_SESSION['captcha']) && $_POST['captcha'] === $_SESSION['captcha'])
		return true;
	$_SESSION['ErrBot'] = '';
	return false;
}

?>
