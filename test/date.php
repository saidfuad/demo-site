<?php
// date_default_timezone_set('Australia/Melbourne');
$datetime = new DateTime();
echo $datetime->format('Y-m-d H:i:s') . "\n<br />";

$la_time = new DateTimeZone('Asia/Magadan');
$datetime->setTimezone($la_time);
echo $datetime->format('Y-m-d H:i:s');

?>