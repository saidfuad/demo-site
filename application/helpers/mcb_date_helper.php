<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function format_date($unix_timestamp_date = NULL) {

	if ($unix_timestamp_date) {

		global $CI;

		return date($CI->mdl_mcb_data->default_date_format, $unix_timestamp_date);

	}

	return '';

}

function standardize_date($date) {

	global $CI;

	if (strstr($date, '/')) {

		$delimiter = '/';

	}

	elseif (strstr($date, '-')) {

		$delimiter = '-';

	}

	elseif (strstr($date, '.')) {

		$delimiter = '.';

	}

	else {

		// do not standardize
		return $date;

	}

	$date_format = explode($delimiter, $CI->mdl_mcb_data->default_date_format);

	$date = explode($delimiter, $date);

	foreach ($date_format as $key=>$value) {

		$standard_date[strtolower($value)] = $date[$key];

	}

	return $standard_date['m'] . '/' . $standard_date['d'] . '/' . $standard_date['y'];

}

function ago($time)
{

   $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
   $lengths = array("60","60","24","7","4.35","12","10");

   $now = time();
	   $time = strtotime($time);
       $difference     = $now - $time;
       $tense         = "ago";

   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
       $difference /= $lengths[$j];
   }

   $difference = round($difference);

   if($difference != 1) {
       $periods[$j].= "s";
   }

   return "$difference $periods[$j]";
}


?>