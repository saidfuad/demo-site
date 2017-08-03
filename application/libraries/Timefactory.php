<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Timefactory {
	
	public function secs_to_time ($seconds) {

		//$hours = 0;
		//$minutes = 0;
		//$seconds =0;
		// extract hours
		$hr =floor($seconds / (60 * 60));
		$min =floor($seconds / 60);

		$days =  floor($seconds / (60 * 60 * 24));

		$divisor_for_hours = $seconds % (60 * 60 * 24);
	    $hours = floor($divisor_for_hours / (60 * 60));
	 
	    // extract minutes
	    $divisor_for_minutes = $seconds % (60 * 60);
	    $minutes = floor($divisor_for_minutes / 60);
	 
	    // extract the remaining seconds
	    $divisor_for_seconds = $divisor_for_minutes % 60;
	    $secs = ceil($divisor_for_seconds);
	 
	    // return the final array
	    $obj = array(
	        "h" => (int) $hours,
	        "m" => (int) $minutes,
	        "s" => (int) $seconds,
	    );

	    if ($days > 365) {
	    	return floor($days/365) . ' Years(s)';
	    } else if ($days > 30) { 
	    	return floor($days/30) . ' Month(s)';
	    } else if ($hr > 24) { 
	    	return $days . 'days ' . $hours . 'hours ' . $minutes . 'min ' . $secs . 'seconds';
	    } else if ($min > 60) {
	    	return $hours . 'hours ' . $minutes . 'min ' . $secs . 'seconds';
	    } else if ($seconds > 60) {
	    	return $minutes . 'min ' . $secs . 'seconds';
	    } else {
	    	return $minutes . 'min ' . $secs . 'seconds';
	    }	

	    
	}


	
}