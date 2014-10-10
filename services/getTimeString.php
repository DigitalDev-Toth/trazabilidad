<?php

function getTimeString($timeSeconds){
	$timeSeconds = round($timeSeconds);
	$seconds = ($timeSeconds%3600)%60;
	$minutes = (($timeSeconds%3600)-$seconds)/60;
	$hours = ($timeSeconds-($timeSeconds%3600))/3600;
	
	if($seconds<=9) $seconds = '0'.$seconds;
	if($minutes<=9) $minutes = '0'.$minutes;
	if($hours<=9) $hours = '0'.$hours;

	return $hours.':'.$minutes.':'.$seconds;
}

?>