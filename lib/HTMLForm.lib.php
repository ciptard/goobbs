<?php

function clean($text)
{
	if(get_magic_quotes_gpc())
		$text = stripslashes($text);
	return htmlspecialchars(trim($text), ENT_QUOTES);
}

function transNL($text)
{
	return preg_replace('/\n{3,}/', "\n\n", str_replace(array("\r\n", "\r"), "\n", $text));
}

function hide($text)
{
	return md5($text.md5($text));
}

function trip($name, $id)
{
	if ($name === '')
	{
		return substr($id, -5);
	}
	else
	{
		$parts = explode('#', $name, 2);
		return  $parts[0].(isset($parts[1])? '#' .substr(md5($parts[1]), -5) : '');
	}
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
	return '<div class="control-group">
		<label class="control-label">' .$lang[$name]. '</label>
		<div class="controls">'.
			err($name. 'ErrNotMatch', $lang['errNotMatch']).
			'<input type="password" name="' .$name. '"/>  <input type="password" name="' .$name. 'Confirm"/>
		</div>
	</div>';
}

function text($name, $default = '')
{
	global $lang;
	return '<div class="control-group">
		<label class="control-label">' .$lang[$name]. '</label>
		<div class="controls">'.
			err($name. 'ErrLen', $lang['errLen']).
			'<input type="text" name="' .$name. '" value="' .(isPOST($name)? clean($_POST[$name]) : $default). '"/>
		</div>
	</div>';
}

function textarea($name, $default = '')
{
	global $lang;
	return '<div class="control-group">
		<label class="control-label">' .$lang[$name]. '</label>
		<div class="controls">'.
			err($name. 'ErrLen', $lang['errLen']).
			'<textarea name="' .$name. '" cols="80" rows="10">' .(isPOST($name)? transNL(clean($_POST[$name])) : $default). '</textarea>
		</div>
	</div>';
}

function submit()
{
	global $lang;
	$num1 = rand(1, 10);
	$num2 = rand(1, 10);
	$_SESSION['captcha'] = (string) ($num1 * $num2);
	return '<div class="control-group">
		<label class="control-label">' .$num1. ' x ' .$num2. ' = ?</label>
		<div class="controls">'.
			err('ErrBot', $lang['errBot']).
			'<input type="text" name="captcha" style="width: 50px;"/>
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
	return isPOST($name)? '<div class="alert">' .content(transNL(clean($_POST[$name]))). '</div>' : '';
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
