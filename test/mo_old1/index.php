<?php include("php/session.php"); ?>
<?php include("header.php"); ?>
		<div data-role="content"><!-- style='padding:0px;'-->
				<div class="ui-body ui-body-d">
					<div data-role="fieldcontain">
						<ul data-role="listview" data-divider-theme="b" data-inset="true">
    						<li data-theme='c'><a href='list_view.php?cmd=all_users' data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'><?php echo $lang['All Users']; ?></div></div></a></li>
    						<li data-theme='c'><a href='list_view.php?cmd=all_group' data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'><?php echo $lang['All Group']; ?></div></div></a></li>
    						<li data-theme='c'><a href='list_view.php?cmd=all_zones' data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'><?php echo $lang['All Zones']; ?></div></div></a></li>
    						<li data-theme='c'><a href='list_view.php?cmd=all_area' data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'><?php echo $lang['All Areas']; ?></div></div></a></li>
    						<li data-theme='c'><a
href='list_view.php?cmd=all_landmark' data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'><?php echo $lang['All Landmark']; ?></div></div></a></li>
<?php if($_SESSION['show_owners'] == 1) { ?>
    						<li data-theme='c'><a href='list_view.php?cmd=all_owner' data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'><?php echo $lang['All Owners']; ?></div></div></a></li>
<?php } ?>
<?php if($_SESSION['show_divisions'] == 1) { ?>
    						<li data-theme='c'><a href='list_view.php?cmd=all_divisition' data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'><?php echo $lang['All Division']; ?></div></div></a></li>
<?php } ?>
					</div>
					
				</div><!-- /body-d -->
		</div>

<?php include("footer.php"); ?>
