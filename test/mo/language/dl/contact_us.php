<?php include("php/session.php"); ?>
<?php include("header.php"); ?>
<div data-role="content"><!-- style='padding:0px;'-->
	<div class="ui-body ui-body-d">
		<h3><u>Contact Us</u></h3>
		<h3><u><?php echo $lang['Company']; ?></u></h3>
		<?php echo $lang['devindia_address']; ?>
		<?php echo $lang['Contact No']?> : <?php echo $lang['contact']?><br>
		<?php echo $lang['Mobile No']?> : <?php echo $lang['mobile']?><br>
		<?php echo $lang['Email']; ?> : <?php if($lang['email'] != '') { ?><a href='mailto:<?php echo $lang['email']?>'><?php echo $lang['email']?></a> <?php } ?><br>
		<?php echo $lang['Website']; ?> : <?php if($lang['website'] != '') { ?><a href='http://<?php echo $lang['website']; ?>'><?php echo $lang['website']; ?></a><?php } ?><br>
		<a href="./" data-role="button" data-theme="e" data-inline="false"><?php echo $lang['back']; ?></a>
		
	</div><!-- /body-d -->
</div>
<?php include("footer.php"); ?>