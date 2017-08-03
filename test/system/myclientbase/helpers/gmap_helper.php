<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function getAddress($latitude, $longitude, $format = 'xml') {
	
	$CI =& get_instance();
	
	$url_base = "http://maps.googleapis.com/maps/api/geocode/";
	
	$parameters["latlng"] = "$latitude,$longitude";
	
	$parameters["sensor"] = "true";
	
	if(strtoupper($format) == 'XML' || strtoupper($format) == "JSON") {
	
		$CI->load->library('curl');
		
		$CI->curl->simple_get($url_base . $format . "?" . implode("&",$parameters));
		
		return $CI->curl->execute();
	
	}

	else {

		return NULL;

	}

}

?>