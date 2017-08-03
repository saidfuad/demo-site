<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 *
 * @author Benson
 */
class Gps_utilities {

    protected $CI;

    public function __construct() {
        $this->CI = & get_instance();
    }

    /**
     * Converts (Compass) degrees to decimal
     * @param type $deg
     * @return real
     */
    function deg_to_decimal($deg) {

        if ($deg == '')
            return 0.000000;

        $sign = substr($deg, -1);

        if (strtoupper($sign) == "N" || strtoupper($sign) == "E")
            $sign = 1;
        if (strtoupper($sign) == "W" || strtoupper($sign) == "S")
            $sign = -1;

        $deg = substr($deg, 0, strlen($deg) - 1);
        $degree = substr($deg, 0, -7);

        $decimal = substr($deg, -7);
        $decimal = $sign * number_format(floatval((($degree * 1.0) + ($decimal / 60))), 6);

        return $decimal;
    }

    /**
     * Gets the address for a GPS point
     * @param type $lat
     * @param type $lng
     * @return type
     */
    function getaddress($lat, $lng) {
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $lat . ',' . $lng . '&key=' . $this->CI->config->item('geocode_key');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $contents = curl_exec($ch);

        if (curl_errno($ch)) {

            echo curl_error($ch);

            $contents = '';
        } else {
            curl_close($ch);
        }

        if (!is_string($contents) || !strlen($contents)) {

            echo "Failed to get contents.";
            return $contents = '';
        }

        $obj = json_decode($contents, true);
        return !empty($obj["results"]) ? $obj["results"][0]["formatted_address"] : NULL;
    }

    /**
     * Calculate distance in Meters between two GPS 
     * @param type $lat_from
     * @param type $lng_from
     * @param type $lat_to
     * @param type $lng_to
     * @return type
     */
    function calculate_distance($lat_from, $lng_from, $lat_to, $lng_to) {
        $earth_radius = 6371000;

        $lat_from = deg2rad($lat_from);
        $lng_from = deg2rad($lng_from);

        $lat_to = deg2rad($lat_to);
        $lng_to = deg2rad($lng_to);

        $lon_delta = $lng_to - $lng_from;
        $a = pow(cos($lat_to) * sin($lon_delta), 2) +
                pow(cos($lat_from) * sin($lat_to) - sin($lat_from) * cos($lat_to) * cos($lon_delta), 2);
        $b = sin($lat_from) * sin($lat_to) + cos($lat_from) * cos($lat_to) * cos($lon_delta);

        $angle = atan2(sqrt($a), $b);
        $distance = $angle * $earth_radius;
        return $distance;
    }

    /**
     * Checks if a point is in a polygon
     * @param type $point
     * @param type $polygon
     * @return type
     */
    function is_in_polygon($point, $polygon) {
        if ($polygon[0] != $polygon[count($polygon) - 1]) {
            $polygon[count($polygon)] = $polygon[0];
        }
        $j = 0;
        $odd_nodes = false;
        $x = $point[1];
        $y = $point[0];
        $n = count($polygon);
        for ($i = 0; $i < $n; $i++) {
            $j++;
            if ($j == $n) {
                $j = 0;
            }
            if ((($polygon[$i][0] < $y) && ($polygon[$j][0] >= $y)) || (($polygon[$j][0] < $y) && ($polygon[$i][0] >= $y))) {
                if ($polygon[$i][1] + ($y - $polygon[$i][0]) / ($polygon[$j][0] - $polygon[$i][0]) * ($polygon[$j][1] -
                        $polygon[$i][1]) < $x) {
                    $odd_nodes = !$odd_nodes;
                }
            }
        }
        return $odd_nodes;
    }

}
