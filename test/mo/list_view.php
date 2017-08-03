<?php include("php/session.php"); ?>
<?php include("header.php"); 
$cmd = isset($_REQUEST['cmd'])?$_REQUEST['cmd']:"";
$user 		= $_SESSION['user_id'];
$view_All = "";
$all_devices = array();
$landUsers = array($user);
if($cmd =="all_users"){
	
	$subUserOpt = '';
	$today=strtolower(date("l"));
	$query = "select user_id, username, first_name, last_name from tbl_users where status=1 And del_date is null And (find_in_set('$today',display_day) or display_day='all') and (admin_id = $user  or user_id='$user')";
	$res = mysql_query($query) or die($query. mysql_error());
	if(mysql_num_rows($res)>0){
		while($row = mysql_fetch_array($res)){
			$query1 ="select device_id from assests_master am where am.status=1 AND am.del_date is null AND find_in_set(am.id, (SELECT assets_ids FROM user_assets_map where user_id =". $row['user_id']."))";
			$res1 = mysql_query($query1) or die($query1. mysql_error());
			$user_device = array();
			if(mysql_num_rows($res1)>0){
				while($row1 = mysql_fetch_array($res1)){
					if(!in_array($row1['device_id'],$all_devices)){
						$all_devices[] = $row1['device_id'];
					}
					$user_device[] = $row1['device_id'];
				}
			} else { $user_device[] = 0; }
			$user_device = implode(',',$user_device);
			$landUsers[] = $row['user_id'];
			$view .= "<li data-theme='c'><a href=\"javascript:loadUrl('list_vehicle.php', '".$user_device."')\" data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>".$row['username']." (".addslashes($row['first_name'])." ".addslashes($row['last_name']).")</div></div></a></li>";
			// $view_All .= "<li data-theme='c'><a href='list_vehicle.php?device_ids=".$user_device."' data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>".$row['username']." (".addslashes($row['first_name'])." ".addslashes($row['last_name']).")</div></div></a></li>";
		}
	}
	
	if(mysql_num_rows($res)==0){
		echo "<li data-theme='c'><a href='index.php' data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>".$lang['No Users to View Click Here To Home']."</div></div></a></li>";
	}else{
		
		$all_devices = implode(',',$all_devices);
		$view_All .= "<li data-theme='c'><a href=\"javascript:loadUrl('list_vehicle.php', '".$all_devices."')\" data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>".$lang['All Users']."(".$lang['All Users'].")</div></div></a></li>";
		$view_All .= $view;
		// $view_All = "<li data-theme='c'><a href='list_vehicle.php?device_ids=".$all_devices."' data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>All Users</div></div></a></li>".$view_All;
	}
	
}else if($cmd =="all_group"){
	
	$groupOpt = '';
	if($user != 1){
		$query = "SELECT gm.id, gm.group_name, GROUP_CONCAT( am.id ) as assets FROM assests_master am LEFT JOIN group_master gm ON gm.id = assets_group_id, user_assets_map um WHERE find_in_set( assets_group_id, um.group_id ) AND um.user_id = $user GROUP BY assets_group_id ORDER BY gm.group_name";
	}else{
		$query = "SELECT gm.id, gm.group_name, GROUP_CONCAT( am.id ) as assets FROM assests_master am LEFT JOIN group_master gm ON gm.id = assets_group_id, user_assets_map um WHERE find_in_set( assets_group_id, um.group_id ) GROUP BY assets_group_id ORDER BY gm.group_name";
	}
	
//	$query = "select id, group_name, assets from group_master where status=1 AND del_date is null AND add_uid = $user";
	$res = mysql_query($query) or die($query. mysql_error());
	if(mysql_num_rows($res)>0){
		while($row = mysql_fetch_array($res)){
			$query1 ="select device_id from assests_master am where am.status=1 AND am.del_date is null AND am.id IN (".$row['assets'].")";
			$res1 = mysql_query($query1) or die($query1. mysql_error());
			$user_device = array();
			if(mysql_num_rows($res1)>0){
				while($row1 = mysql_fetch_array($res1)){
					if(!in_array($row1['device_id'],$all_devices)){
						$all_devices[] = $row1['device_id'];
					}
					$user_device[] = $row1['device_id'];
				}
			} else { $user_device[] = 0; }
			$user_device = implode(',',$user_device);
			$view_All .= "<li data-theme='c'><a href=\"javascript:loadUrl('list_vehicle.php', '".$user_device."')\" data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>".addslashes($row['group_name'])."</div></div></a></li>";
			
		}
	}
	if(mysql_num_rows($res)==0){
		$view_All .=  "<li data-theme='c'><a href='index.php' data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>".$lang['No Group to View Click Here To Home']."</div></div></a></li>";
	}else{
		
		$all_devices = implode(',',$all_devices);
		$view_All = "<li data-theme='c'><a href=\"javascript:loadUrl('list_vehicle.php', '".$all_devices."')\" data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>".$lang['All Group']."</div></div></a></li>".$view_All;
	}
	
}else if($cmd =="all_zones"){

	$areasOpt = '';
	$today=strtolower(date("l"));
	$user_ids = implode(',', $landUsers);
	$query = "SELECT area.polyid, area.polyname,(select group_concat(am.device_id)  from assests_master am left join tbl_last_point tlp on tlp.device_id=am.device_id  where am.status=1 AND am.del_date is null  and tlp.zone_id=area.polyid) as  deviceid FROM `landmark_areas` area  WHERE area.Audit_Status = 1 AND area.Audit_Del_Dt is null AND  find_in_set(area.Audit_Enter_uid, ('". $user_ids."')) group by area.polyid";
	$res = mysql_query($query) or die($query. mysql_error());
	if(mysql_num_rows($res)>0){
		while($row = mysql_fetch_array($res)){
			$explods = explode(",",$row['deviceid']);
			foreach($explods AS $val){
				if(!in_array($val,$all_devices)){
					$all_devices[] = $val;
				}
			}
			
			$view_All .= "<li data-theme='c'><a href=\"javascript:loadUrl('list_vehicle.php', '".$row['deviceid']."')\" data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>".addslashes($row['polyname'])."</div></div></a></li>";
		}
	}
	if(mysql_num_rows($res)==0){
		$view_All .=  "<li data-theme='c'><a href='index.php' data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>".$lang['No Zone to View Click Here To Home']."</div></div></a></li>";
	}else{
		
		$all_devices = implode(',',$all_devices);
		$view_All = "<li data-theme='c'><a href=\"javascript:loadUrl('list_vehicle.php', '".$all_devices."')\" data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>".$lang['All Zone']."</div></div></a></li>".$view_All;
	}
	
}else if($cmd =="all_area"){

	$areasOpt = '';
	$today=strtolower(date("l"));
	$user_ids = implode(',', $landUsers);
	$query = "SELECT area.polyid, area.polyname,(select group_concat(am.device_id)  from assests_master am left join tbl_last_point tlp on tlp.device_id=am.device_id  where am.status=1 AND am.del_date is null  and tlp.area_id=area.polyid) as  deviceid FROM `areas` area  WHERE area.Audit_Status = 1 AND area.Audit_Del_Dt is null AND  find_in_set(area.Audit_Enter_uid, ('". $user_ids."')) group by area.polyid";
	$res = mysql_query($query) or die($query. mysql_error());
	if(mysql_num_rows($res)>0){
		while($row = mysql_fetch_array($res)){
			$explods = explode(",",$row['deviceid']);
			foreach($explods AS $val){
				if(!in_array($val,$all_devices)){
					$all_devices[] = $val;
				}
			}
			
			$view_All .= "<li data-theme='c'><a href=\"javascript:loadUrl('list_vehicle.php', '".$row['deviceid']."')\" data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>".addslashes($row['polyname'])."</div></div></a></li>";
		}
	}
	if(mysql_num_rows($res)==0){
		$view_All .=  "<li data-theme='c'><a href='index.php' data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>".$lang['No Area to View Click Here To Home']."</div></div></a></li>";
	}else{
		
		$all_devices = implode(',',$all_devices);
		$view_All = "<li data-theme='c'><a href=\"javascript:loadUrl('list_vehicle.php', '".$all_devices."')\" data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>".$lang['All Areas']."</div></div></a></li>".$view_All;
	}
	
}else if($cmd =="all_landmark"){
	
	$landOpt = '';
	$today=strtolower(date("l"));
	$user_ids = implode(',', $landUsers);
 	$query = "SELECT name FROM landmark WHERE status=1 AND del_date is null AND find_in_set(add_uid, ('". $user_ids."'))";
	$res = mysql_query($query) or die($query. mysql_error());
	if(mysql_num_rows($res)>0){
		while($row = mysql_fetch_array($res)){
			$sql = "SELECT GROUP_CONCAT( am.device_id ) AS device_ids FROM assests_master am LEFT JOIN tbl_last_point lm ON lm.device_id = am.device_id WHERE am.status = 1 AND am.del_date IS NULL AND find_in_set( am.id, (SELECT assets_ids FROM user_assets_map WHERE user_id IN ('". $user_ids."'))) AND lm.current_landmark = '".$row['name']."'";
			$res1 = mysql_query($sql);
			$row1 = mysql_fetch_array($res1);
			$explods = explode(",",$row1['device_ids']);
			foreach($explods AS $val){
				if(!in_array($val,$all_devices)){
					$all_devices[] = $val;
				}
			}
			$view_All .= "<li data-theme='c'><a href=\"javascript:loadUrl('list_vehicle.php', '".$row1['device_ids']."')\" data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>".addslashes($row['name'])."</div></div></a></li>";
		}
	}
	if(mysql_num_rows($res)==0){
		$view_All .=  "<li data-theme='c'><a href='index.php' data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>".$lang['No Landmark to View Click Here To Home']."</div></div></a></li>";
	}else{
		
		$all_devices = implode(',',$all_devices);
		$view_All = "<li data-theme='c'><a href=\"javascript:loadUrl('list_vehicle.php', '".$all_devices."')\" data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>".$lang['All Landmarks']."</div></div></a></li>".$view_All;
	}
	
}else if($cmd =="all_owner"){
	
	$ownerOpt = '';
	$today=strtolower(date("l"));
	$query = "SELECT id, owner FROM `assests_owner_master` WHERE status = '1' AND del_date is Null";
	$res = mysql_query($query) or die($query. mysql_error());
	if(mysql_num_rows($res)>0){
		while($row = mysql_fetch_array($res)){
			$query1 ="select am.device_id, am.assets_owner from assests_master am where am.status=1 AND am.del_date is null AND am.assets_owner = '".$row['id']."'";
			$res1 = mysql_query($query1) or die($query1. mysql_error());
			$user_device = array();
			if(mysql_num_rows($res1)>0){
				while($row1 = mysql_fetch_array($res1)){
					if(!in_array($row1['device_id'],$all_devices)){
						$all_devices[] = $row1['device_id'];
					}
					$user_device[] = $row1['device_id'];
				}
			} else { $user_device[] = 0; }
			$user_device = implode(',',$user_device);
			$view_All .= "<li data-theme='c'><a href=\"javascript:loadUrl('list_vehicle.php', '".$user_device."')\" data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>".addslashes($row['owner'])."</div></div></a></li>";
		}
	}
	if(mysql_num_rows($res)==0){
		$view_All .=  "<li data-theme='c'><a href='index.php' data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>".$lang['No Owner to View Click Here To Home']."</div></div></a></li>";
	}else{
		
		$all_devices = implode(',',$all_devices);
		$view_All = "<li data-theme='c'><a href=\"javascript:loadUrl('list_vehicle.php', '".$all_devices."')\" data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>".$lang['All Owners']."</div></div></a></li>".$view_All;
	}
	
}else if($cmd =="all_divisition"){
	
	$divisionOpt = '';
	$today=strtolower(date("l"));
	$query = "SELECT id, division FROM `assests_division_master` WHERE status= '1' AND del_date IS NULL";
	$res = mysql_query($query) or die($query. mysql_error());
	if(mysql_num_rows($res)>0){
		while($row = mysql_fetch_array($res)){
			$query1 ="select am.device_id, am.assets_division from assests_master am where am.status=1 AND am.del_date is null AND am.assets_division = '".$row['id']."'";
			$res1 = mysql_query($query1) or die($query1. mysql_error());
			$user_device = array();
			if(mysql_num_rows($res1)>0){
				while($row1 = mysql_fetch_array($res1)){
					if(!in_array($row1['device_id'],$all_devices)){
						$all_devices[] = $row1['device_id'];
					}
					$user_device[] = $row1['device_id'];
				}
			} else { $user_device[] = 0; }
			$user_device = implode(',',$user_device);
			$view_All .= "<li data-theme='c'><a href=\"javascript:loadUrl('list_vehicle.php', '".$user_device."')\" data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>".addslashes($row['division'])."</div></div></a></li>";;
		}
	}
	if(mysql_num_rows($res)==0){
		$view_All .=  "<li data-theme='c'><a href='index.php' data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>".$lang['No Divisition to View Click Here To Home']."</div></div></a></li>";
	}else{
		
		$all_devices = implode(',',$all_devices);
		$view_All = "<li data-theme='c'><a href=\"javascript:loadUrl('list_vehicle.php', '".$all_devices."')\" data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>".$lang['All Division']."</div></div></a></li>".$view_All;
	}
	
}else{
$view_All = "<li data-theme='c'><a href='list_view.php?cmd=all_users' data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>All Users</div></div></a></li>
<li data-theme='c'><a href='list_view.php?cmd=all_group' data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>All Group</div></div></a></li>
<li data-theme='c'><a href='list_view.php?cmd=all_area' data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>All Areas</div></div></a></li>
<li data-theme='c'><a href='list_view.php?cmd=all_landmark' data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>All Landmark</div></div></a></li>
<li data-theme='c'><a href='list_view.php?cmd=all_owner' data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>All Owners</div></div></a></li>
<li data-theme='c'><a href='list_view.php?cmd=all_divisition' data-transition='slide'><div class='ui-grid-a'><div class='ui-block-b'>All Division</div></div></a></li>";
}
?>
		<div data-role="content"><!-- style='padding:0px;'-->
				<div class="ui-body ui-body-d">
					<div data-role="fieldcontain">
						<ul data-role="listview" data-filter="true" data-divider-theme="b" data-inset="true">
 							<?php echo $view_All; ?>
						</ul>
						<a data-icon="back" data-rel="back"  href="#" data-role="button" data-theme="e" data-inline="false"><?php echo $lang['back']; ?></a>
					</div>
					
				</div><!-- /body-d -->
		</div>

<?php include("footer.php"); ?>
