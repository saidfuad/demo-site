<?php @session_start(); ?>
<?php if(!isset($_SESSION["user_id"]) || $_SESSION["user_id"] == "") { header("Location: login.php"); exit(); }?>
