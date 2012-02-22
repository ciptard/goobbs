<?php

function pageControl($p, $total, $loc)
{
	$out = '<div class="pagination pagination-centered"><ul>';
	for($i=1; $i<=$total; $i++)
	{
		if($p === $i)
			$out .= '<li class="active"><a href="#">' .$i. '</a></li>';
		else
			$out .= '<li><a href="' .$loc. '/p/' .$i. '">' .$i. '</a></li>';	
	}
	$out .= '</ul></div>';
	return $out;
}

function totalPage($items)
{
	$itemNum = count($items);
	if($itemNum === 0)
		return 1;
	else
		return (int) ceil($itemNum / 8);
}

function viewPage($items, $p)
{
	return array_slice($items, 8*($p-1), 8);
}

function pid($total)
{
	return isGET('p') && $_GET['p'] >= 1 && $_GET['p'] <= $total? (int) $_GET['p'] : 1;
}

?>
