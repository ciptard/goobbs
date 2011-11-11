<?php

function pageControl($p, $total, $loc)
{
	return '<div id="page"><ul>' .
	($p >= 2? '<li><a href="?' .$loc. '&amp;p=' .($p-1). '">← ' .($p-1). '</a></li>' : '').
	'<li>' .$p. ' / ' .$total. '</li>' .
	($p <= $total-1? '<li><a href="?' .$loc. '&amp;p=' .($p+1). '">' .($p+1). ' →</a></li>' : '').
	'</ul></div>';
}

function pageNum($total)
{
	return isGET('p') && $_GET['p'] >= 1 && $_GET['p'] <= $total? $_GET['p'] : 1;
}

?>
