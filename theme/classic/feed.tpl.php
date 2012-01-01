<?php

if(!isset($out))
{
	exit;
}
header('Content-Type: application/atom+xml; charset=UTF-8');

?>
<feed xmlns="http://www.w3.org/2005/Atom" xml:base="<?php echo $out['baseURL'];?>">
	<id><?php echo $out['baseURL'];?>feed.php/<?php echo $out['type'];?></id>
	<title><?php echo $out['subtitle'];?> - <?php echo $config['title'];?></title>
	<updated><?php echo date('c');?></updated>
	<link href="feed.php/<?php echo $out['type'];?>" rel="self"/>
	<author><name><?php echo $lang['poweredBy'];?> goobbs</name></author>
	<?php echo $out['content'];?>
</feed>
