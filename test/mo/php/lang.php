<?php
session_start();
header('Cache-control: private'); // IE 6 FIX
/*
if(isSet($_GET['lang']))
{
$lang = $_GET['lang'];

// register the session and set the cookie
$_SESSION['lang'] = $lang;

setcookie('lang', $lang, time() + (3600 * 24 * 30));
}
else if(isset($_SESSION['lang']))
{
$lang = $_SESSION['lang'];
}
else if(isset($_COOKIE['lang']))
{
$lang = $_COOKIE['lang'];
}
else
{
$lang = 'english';
}

switch ($lang) {
  case 'english':
  $lang_file = 'english_lang.php';
  break;

  case 'hindi':
  $lang_file = 'hindi_lang.php';
  break;

  case 'gujarati':
  $lang_file = 'gujarati_lang.php';
  break;

  default:
  $lang_file = 'english_lang.php';

}
*/
$language 		= $_SESSION['lang'];
if($language == "English"){
$lang_file = 'english_lang.php';
}
else if($language == "Portuguese"){
$lang_file = 'portuguese_lang.php';
}else{
$lang_file = 'english_lang.php';
}

include_once 'language/'.$lang_file;
?>