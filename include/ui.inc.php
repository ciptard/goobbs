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
	$out['content'] .= '<div class="msg">' .$msg. '</div>';
}

function password()
{
	global $lang;
	return $lang['password']. ' <input type="password" name="password"/>  <input type="password" name="password2"/>';
}

function text($name, $default = '')
{
	global $lang;
	return $lang[$name]. ' <input type="text" name="' .$name. '" value="' .(isPOST($name)? clean($_POST[$name]) : $default). '"/>';
}

function textarea($default = '')
{
	global $lang;
	return $lang['content']. '
	<textarea name="content" cols="80" rows="10">' .(isPOST('content')? clean($_POST['content']) : $default). '</textarea>';
}

function submit()
{
	global $lang;
	$num1 = rand(1, 10);
	$num2 = rand(1, 10);
	$_SESSION['captcha'] = (string) ($num1 * $num2);
	return $num1. ' x ' .$num2. ' = ? <input type="text" name="captcha" style="width: 50px;"/> <input type="submit" value="' .$lang['confirm']. '"/>';
}

function select($name, $options, $default = '')
{
	global $lang;
	$selected = isPOST($name) && isset($options[$_POST[$name]])? $_POST[$name] : $default;
	$out = $lang[$name]. ' <select name="' .$name. '">';
	foreach($options as $value => $option)
	{
		$out .= '<option value="' .$value. '"' .($value === $selected? ' selected="selected"' : ''). '>' .$option. '</option>';
	}
	$out .= '</select>';
	return $out;
}

function check($name, $min = 1, $max = 40)
{
	global $lang;
	if(!isPOST($name))
		return false;
	$len = strlen(trim($_POST[$name]));
	if($len >= $min && $len <= $max)
		return true;
	message($lang[$name].$lang['errorLength']);
	return false;
}

function checkPass()
{
	return check('password') && isPOST('password2') && $_POST['password'] === $_POST['password2'];
}

function checkBot()
{
	global $lang;
	if(!isPOST('captcha'))
		return false;
	if(isset($_SESSION['captcha']) && $_POST['captcha'] === $_SESSION['captcha'])
		return true;
	message($lang['errorBot']);
	return false;
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

function part($do, $arr, $limit)
{
	$do($arr);
	return array_slice($arr, 0, $limit);
}

?>
