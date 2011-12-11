<?php

function pageControl($p, $total, $loc)
{
	return '<div id="page"><ul>' .
	($p > 1? '<li><a href="?' .$loc. '&amp;p=1">| ←</a></li>
	<li><a href="?' .$loc. '&amp;p=' .($p-1). '">← ' .($p-1). '</a></li>' : '').
	'<li>' .$p. ' / ' .$total. '</li>' .
	($p < $total? '<li><a href="?' .$loc. '&amp;p=' .($p+1). '">' .($p+1). ' →</a></li>
	<li><a href="?' .$loc. '&amp;p=' .$total. '">→ |</a></li>' : '').
	'</ul></div>';
}

function totalPage($items)
{
	return ceil(count($items) / 8);
}

function viewPage($items, $p)
{
	return array_slice($items, 8*($p-1), 8);
}

function pid($total)
{
	return isGET('p') && $_GET['p'] >= 1 && $_GET['p'] <= $total? $_GET['p'] : 1;
}

?>
