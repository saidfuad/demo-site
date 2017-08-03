<?php include("php/session.php"); ?>
<?php include("header.php"); ?>
		<div data-role="content"><!-- style='padding:0px;'-->
				
				
				<div class="ui-body ui-body-d">

					<div data-role="fieldcontain">
						<a  data-icon="gear" href="stop_report.php"  data-role="button" data-theme="b"  data-inline="false"><?php echo $lang['Stop Report']; ?></a>
						<a data-icon="gear"  href="area_in_out_report.php" data-role="button"  data-theme="b" data-inline="false"><?php echo $lang['Area In/Out Report']; ?></a>
						<a data-icon="gear"  href="landmark_report.php" data-role="button" data-theme="b" data-inline="false"><?php echo $lang['Landmark Report']; ?></a>
						<a data-icon="gear"  href="distance_report.php" data-role="button" data-theme="b" data-inline="false"><?php echo $lang['Distance Report']; ?></a>
						<a data-icon="gear"  href="trip_report.php" data-role="button" data-theme="b" data-inline="false"><?php echo $lang['Trip Report']; ?></a>
						<a data-icon="gear"  href="route_break_report.php" data-role="button" data-theme="b" data-inline="false"><?php echo $lang['Route Break Report']; ?></a>
						<a data-icon="gear"  href="distance_graph.php" data-role="button" data-theme="b" data-inline="false"><?php echo $lang['Distance Graph']; ?></a>
						<a data-icon="gear"  href="speed_graph.php" data-role="button" data-theme="b" data-inline="false"><?php echo $lang['Speed Graph']; ?></a>
						<a data-icon="back" data-rel="back"  href="#" data-role="button" data-theme="e" data-inline="false"></a>
					</div>
				
				</div><!-- /body-d -->
		</div>

<?php include("footer.php"); ?>
