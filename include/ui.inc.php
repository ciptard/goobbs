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
		return '<div class="alert alert-error"><i class="icon-arrow-down"></i> ' .$msg. '</div>';
	}
	return '';
}

function password($name)
{
	global $lang;
	return err($name. 'ErrNotMatch', $lang['errNotMatch']).
	'<div class="control-group">
		<label class="control-label">' .$lang[$name]. '</label>
		<div class="controls">
			<input type="password" name="' .$name. '"/>  <input type="password" name="' .$name. 'Confirm"/>
		</div>
	</div>';
}

function text($name, $default = '')
{
	global $lang;
	return err($name. 'ErrLen', $lang['errLen']).
	'<div class="control-group">
		<label class="control-label">' .$lang[$name]. '</label>
		<div class="controls">
			<input type="text" name="' .$name. '" value="' .(isPOST($name)? clean($_POST[$name]) : $default). '"/>
		</div>
	</div>';
}

function textarea($name, $default = '')
{
	global $lang;
	return err($name. 'ErrLen', $lang['errLen']).
	'<div class="control-group">
		<label class="control-label">' .$lang[$name]. '</label>
		<div class="controls">
			<textarea name="' .$name. '" cols="80" rows="10">' .(isPOST($name)? clean($_POST[$name]) : $default). '</textarea>
		</div>
	</div>';
}

function submit()
{
	global $lang;
	$num1 = rand(1, 10);
	$num2 = rand(1, 10);
	$_SESSION['captcha'] = (string) ($num1 * $num2);
	return err('ErrBot', $lang['errBot']).
	'<div class="control-group">
		<label class="control-label">' .$num1. ' x ' .$num2. ' = ?</label>
		<div class="controls">
			<input type="text" name="captcha" style="width: 50px;"/>
		</div>
	</div>
	<div class="form-actions">
		<input class="btn btn-primary" type="submit" value="' .$lang['confirm']. '"/>
	</div>';
}

function select($name, $options, $default = '')
{
	global $lang;
	$selected = isPOST($name) && isset($options[$_POST[$name]])? $_POST[$name] : $default;
	$out = 
	'<div class="control-group">
		<label class="control-label">' .$lang[$name]. '</label>
		<div class="controls">
			<select name="' .$name. '">';
			foreach($options as $value => $option)
			{
				$out .= '<option value="' .$value. '"' .($value == $selected? ' selected="selected"' : ''). '>' .$option. '</option>';
			}
			$out .= '</select>
		</div>
	</div>';
	return $out;
}

function form($action, $controls)
{
	return '<form action="' .$action. '" method="post" class="form-horizontal">
		<fieldset>' .$controls. '</fieldset>
	</form>';
}

function preview($name)
{
	return isPOST($name)? '<div class="alert">' .content(clean($_POST[$name])). '</div>' : '';
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
