<?php
$js=file_get_contents('all_in_one.css');
$js.=file_get_contents('layout.css');
/* more files */

header('Cache-Control: max-age=2592000');
header('Expires-Active: On');
header('Expires: Fri, 1 Jan 2500 01:01:01 GMT');
header('Pragma:');
header('Content-type: text/javascript; charset=utf-8');

echo $js;

?>