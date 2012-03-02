<?php

function pageControl($p, $total, $loc)
{
	$start = ($p-4) >= 1? $p-4 : 1;
	$end = ($p+4) <= $total? $p+4 : $total;
	$out = '<div class="pagination pagination-centered"><ul>'.
		($p === 1? '' : '<li><a href="' .$loc. '/p/' .($p-1). '">«</a></li>').
		($start === 1? '' : '<li class="active"><a href="#">...</a></li>');
		for($i=$start; $i<=$end; $i++)
		{
			if($p === $i)
				$out .= '<li class="active"><a href="#">' .$i. '</a></li>';
			else
				$out .= '<li><a href="' .$loc. '/p/' .$i. '">' .$i. '</a></li>';	
		}
		$out .= ($end === $total? '' : '<li class="active"><a href="#">...</a></li>').
		($p === $total? '' : '<li><a href="' .$loc. '/p/' .($p+1). '">»</a></li>').
	'</ul></div>';
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
	if(!isGET('p'))
		return 1;
	$p = (int) $_GET['p'];
	if($p >= 1 && $p <= $total)
		return $p;
	else
		return 1;
}

?>
