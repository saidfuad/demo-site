<?php include("php/session.php"); ?>
<?php include("header.php"); ?>
		<div data-role="content"><!-- style='padding:0px;'-->
			<div class="ui-body ui-body-d">
			<h4><u><?php echo $lang['About Us']; ?></u></h4>
			<h3><u><?php echo $lang['Company']; ?></u></h3>
			<ul style='list-style:square inside none;'>
				<li><?php echo $lang['about_us_1']; ?></li><br>
				<li><?php echo $lang['about_us_2']; ?></li><br>
				<li><?php echo $lang['about_us_3']; ?></li><br>
				<li><?php echo $lang['about_us_4']; ?></li>
			</ul>
				<a href="./" data-role="button" data-theme="e" data-inline="false"><?php echo $lang['back']; ?></a>
				</div><!-- /body-d -->
		</div>

<?php include("footer.php"); ?>
