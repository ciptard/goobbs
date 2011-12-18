<?php

function pageControl($p, $total, $loc)
{
	$out = '<div id="page"><ul>';
	for($i=1; $i<=$total; $i++)
	{
		if($p === $i)
			$out .= '<li>' .$i. '</li>';
		else
			$out .= '<li><a href="' .$loc. '/p/' .$i. '">' .$i. '</a></li>';	
	}
	$out .= '</ul></div>';
	return $out;
}

function totalPage($items)
{
	return (int) (count($items) / 8) + 1;
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
