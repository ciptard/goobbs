<?php

function pageControl($p, $total, $loc)
{
	global $lang;
	return '<div id="page"><ul>' .
	($p >= 2? '<li><a href="?' .$loc. '&p=' .($p-1). '">← ' .$lang['prev']. '</a></li>' : '').
	'<li>' .$lang['page']. ' : ' .$p. ' / ' .$total. '</li>' .
	($p <= $total-1? '<li><a href="?' .$loc. '&p=' .($p+1). '">' .$lang['next']. ' →</a></li>' : '').
	'</ul></div>';
}

function pageNum($total)
{
	return isGET('p') && $_GET['p'] >= 1 && $_GET['p'] <= $total? $_GET['p'] : 1;
}

?>
