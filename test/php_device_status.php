<?php include("db.php"); ?>
<?php 
	$cmd 		= $_REQUEST['cmd'];
	$current	= date(DATE_TIME);
	
	$page 		= $_REQUEST['page']; // get the requested page
	$start		= $_REQUEST["start"];
	$limit 		= $_REQUEST['rows']; // get how many rows we want to have into the grid
	$sidx 		= $_REQUEST['sidx']; // get index row - i.e. user click to sort
	$sord 		= $_REQUEST['sord']; // get the direction
	
	if(!$sidx) $sidx =1;
	
	$totalrows = isset($_REQUEST['totalrows']) ? $_REQUEST['totalrows']: false;
	if($totalrows) $limit = $totalrows;
	
	//select all area that s not deleted
	if($cmd=="fault")
	{
		$responce = new stdClass();
		$qry="SELECT device_id from tbl_last_point where add_date < DATE_SUB(NOW(), INTERVAL 30 MINUTE) and (device_id!='' and device_id is not null)";
		$rs = mysql_query($qry);
		if(mysql_num_rows($rs)>0){
		$devices='';
		while($ans=mysql_fetch_array($rs)){
			$devices.=$ans['device_id'].",";
		}
		$devices=trim($devices,",");
		$query = "SELECT tu.user_id, tu.username, concat(tu.first_name,' ',tu.last_name) as name, tu.mobile_number, tu.email_address from assests_master am left join user_assets_map uam on find_in_set(am.id,uam.assets_ids) left join tbl_users tu on uam.user_id=tu.user_id where username is not null and find_in_set(am.device_id,'".$devices."') and am.del_date is null and tu.del_date is null";
		$res = mysql_query($query);
		//$tbl="<u><Strong>Devices which are not connected from Last 2 Hours or more.</strong></u>";
		$tbl="<html><head></head><body>";
		$tbl.="<table border=1 >";
		$tbl.="<thead><tr><th>No.</th><th>Client Name</th><th >Username</th><th>Device Down</th><th >Mobile</th><th >Email ID</th></tr></thead><tbody>";
		$client_name=array();
		$mobile=array();
		$email=array();
		$users=array();
		$usersName=array();
		$usersId=array();
		$innerTbl=array();
		$countr=0;
		if(mysql_num_rows($res)>0){
			while($result1=mysql_fetch_array($res)){
				if($result1['username']!='vtslog'){
					$countr++;
					if(!array_key_exists($result1['username'],$users)){
						$users[$result1['username']]=0;
					}
					$users[$result1['username']]=$users[$result1['username']]+1;
					$email[$result1['username']]=$result1['email_address'];
					$mobile[$result1['username']]=$result1['mobile_number'];
					$client_name[$result1['username']]=$result1['username'];
					$usersName[$result1['username']]=$result1['name'];
					$usersId[$result1['username']]=$result1['user_id'];
				}
				
			}
		}
		
		$detailed_table=array();
		$totaldata=array();
		
		$subject=$countr." Devices are Down";
		//print_r($detailed_table);
		$total_pages=1;
		$responce = new stdClass();
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = count($users);		
		$s = 0;
		foreach($users as $key => $value){
				
				//$detailed_table[]['no']=$x;
				$detailed_table['user_id']=addslashes($usersId[$key]);
				$detailed_table['name']=addslashes($client_name[$key]);
				$detailed_table['username']=addslashes($usersName[$key]);
				$detailed_table['device_down']=addslashes($users[$key]);
				$detailed_table['mobile']=addslashes($mobile[$key]);
				$detailed_table['email']=addslashes($email[$key]);
				$responce->rows[$s]=$detailed_table;
				$s++;							
		}
		print(json_encode($responce));
	}
	}
	if($cmd=="subgrid_fault"){
		$userId=$_REQUEST['id'];
		$qry="SELECT am.device_id from tbl_last_point tlp left join assests_master am on am.device_id=tlp.device_id left join user_assets_map uam on find_in_set(am.id,uam.assets_ids) where tlp.add_date < DATE_SUB(NOW(), INTERVAL 30 MINUTE) and (am.device_id!='' and am.device_id is not null) and uam.user_id=$userId";		
		
		$rs = mysql_query($qry);
		if(mysql_num_rows($rs)>0){
			$devices='';
			while($ans=mysql_fetch_array($rs)){
				$devices.=$ans['device_id'].",";
			}
			$devices=trim($devices,",");			
			$query = "SELECT am.assets_name, am.device_id, CONVERT_TZ(lm.add_date,'+00:00','+05:30') as add_date from assests_master am left join tbl_last_point lm on am.device_id=lm.device_id left join user_assets_map uam on find_in_set(am.id,uam.assets_ids) where find_in_set(lm.device_id,'".$devices."') and am.del_date is null and uam.user_id=$userId";
			$res = mysql_query($query);
			$responce = new stdClass();
			$responce->page = 1;
			$responce->total = 1;
			$responce->records = mysql_num_rows($res);
			$s=0;
			while ($row=mysql_fetch_array($res))
			{
				$row['close_since']=timeBetween(date('Y-m-d H:i:s'), date("Y-m-d H:i:s",strtotime($row['add_date'])), 2);
				$row['add_date']=date('d.m.Y h:i:s A',strtotime($row['add_date']));						
															
				$responce->rows[$s] = $row;
				$s++;
			}
			echo json_encode($responce);
		}
	}
	if($cmd=="ignition")
	{
		$responce = new stdClass();
		$qry="SELECT device_id from tbl_last_point where speed!=0 and ignition=0 and (device_id!='' and device_id is not null)";
		$rs = mysql_query($qry);
		if(mysql_num_rows($rs)>0){
		$devices='';
		while($ans=mysql_fetch_array($rs)){
			$devices.=$ans['device_id'].",";
		}
		$devices=trim($devices,",");
		$query = "SELECT tu.user_id, tu.username, concat(tu.first_name,' ',tu.last_name) as name, tu.mobile_number, tu.email_address from assests_master am left join user_assets_map uam on find_in_set(am.id,uam.assets_ids) left join tbl_users tu on uam.user_id=tu.user_id where username is not null and find_in_set(am.device_id,'".$devices."') and am.del_date is null and tu.del_date is null";
		$res = mysql_query($query);
		//$tbl="<u><Strong>Devices which are not connected from Last 2 Hours or more.</strong></u>";
		$tbl="<html><head></head><body>";
		$tbl.="<table border=1 >";
		$tbl.="<thead><tr><th>No.</th><th>Client Name</th><th >Username</th><th>Device Down</th><th >Mobile</th><th >Email ID</th></tr></thead><tbody>";
		$client_name=array();
		$mobile=array();
		$email=array();
		$users=array();
		$usersName=array();
		$usersId=array();
		$innerTbl=array();
		$countr=0;
		if(mysql_num_rows($res)>0){
			while($result1=mysql_fetch_array($res)){
				if($result1['username']!='vtslog'){
					$countr++;
					if(!array_key_exists($result1['username'],$users)){
						$users[$result1['username']]=0;
					}
					$users[$result1['username']]=$users[$result1['username']]+1;
					$email[$result1['username']]=$result1['email_address'];
					$mobile[$result1['username']]=$result1['mobile_number'];
					$client_name[$result1['username']]=$result1['username'];
					$usersName[$result1['username']]=$result1['name'];
					$usersId[$result1['username']]=$result1['user_id'];
				}
				
			}
		}
		
		$detailed_table=array();
		$totaldata=array();
		
		$subject=$countr." Devices are Down";
		//print_r($detailed_table);
		$total_pages=1;
		$responce = new stdClass();
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = count($users);		
		$s = 0;
		foreach($users as $key => $value){
				
				//$detailed_table[]['no']=$x;
				$detailed_table['user_id']=addslashes($usersId[$key]);
				$detailed_table['name']=addslashes($client_name[$key]);
				$detailed_table['username']=addslashes($usersName[$key]);
				$detailed_table['device_down']=addslashes($users[$key]);
				$detailed_table['mobile']=addslashes($mobile[$key]);
				$detailed_table['email']=addslashes($email[$key]);
				$responce->rows[$s]=$detailed_table;
				$s++;							
		}
		print(json_encode($responce));
	}
	}
	if($cmd=="subgrid_ignition"){
		$userId=$_REQUEST['id'];
		$qry="SELECT am.device_id from tbl_last_point tlp left join assests_master am on am.device_id=tlp.device_id left join user_assets_map uam on find_in_set(am.id,uam.assets_ids) where speed!=0 and ignition=0 and (am.device_id!='' and am.device_id is not null) and uam.user_id=$userId";
		
		$rs = mysql_query($qry);
		if(mysql_num_rows($rs)>0){
			$devices='';
			while($ans=mysql_fetch_array($rs)){
				$devices.=$ans['device_id'].",";
			}
			$devices=trim($devices,",");			
			$query = "SELECT am.assets_name, am.device_id, lm.ignition, lm.speed from assests_master am left join tbl_last_point lm on am.device_id=lm.device_id left join user_assets_map uam on find_in_set(am.id,uam.assets_ids) where find_in_set(lm.device_id,'".$devices."') and am.del_date is null and uam.user_id=$userId";
			$res = mysql_query($query);
			$responce = new stdClass();
			$responce->page = 1;
			$responce->total = 1;
			$responce->records = mysql_num_rows($res);
			$s=0;
			while ($row=mysql_fetch_array($res))
			{
				$responce->rows[$s] = $row;
				$s++;
			}
			echo json_encode($responce);
		}
	}
	if($cmd=="export_fault"){
		header("Content-Type: application/vnd.ms-excel"); 
		header("Content-Disposition: attachment; filename=Device_Down.xls"); 
		$qry="SELECT device_id, CONVERT_TZ(add_date,'+00:00','+05:30') as add_date, reason_text from tbl_last_point where add_date < DATE_SUB(NOW(), INTERVAL 30 MINUTE) and (device_id!='' and device_id is not null)";
		$rs = mysql_query($qry);
		$detailed_table="<table width='100%' border=1 cellpadding='4'>";
	
		$tbl="<table border=1 cellpadding='4' cellpadding='4'>";
		if(mysql_num_rows($rs)>0){
			$devices='';
			while($ans=mysql_fetch_array($rs)){
				$devices.=$ans['device_id'].",";
				$add_dates[$ans['device_id']]=$ans['add_date'];
				$reason_text[$ans['device_id']]=$ans['reason_text'];
			}
			$devices=trim($devices,",");
			$query = "SELECT am.id, am.device_id, am.assets_name, tu.username, concat(tu.first_name,' ',tu.last_name) as name, tu.mobile_number, tu.email_address from assests_master am left join user_assets_map uam on find_in_set(am.id,uam.assets_ids) left join tbl_users tu on uam.user_id=tu.user_id where username is not null and find_in_set(am.device_id,'".$devices."') and am.del_date is null and tu.del_date is null";
			$res = mysql_query($query);
			//$tbl="<u><Strong>Devices which are not connected from Last 2 Hours or more.</strong></u>";
			
			$tbl.="<thead><tr><th>No.</th><th>Client Name</th><th >Username</th><th>Device Down</th><th>Mobile</th><th >Email ID</th></tr></thead><tbody>";
			$client_name=array();
			$mobile=array();
			$email=array();
			$users=array();
			$usersName=array();
			$innerTbl=array();
			$countr=0;
			if(mysql_num_rows($res)>0){
				
				$y=1;
				while($result1=mysql_fetch_array($res)){
					
					if($result1['username']!='vtslog'){
						$countr++;
						$str="";
						if(!array_key_exists($result1['username'],$users)){
							$users[$result1['username']]=0;
							$str.="<tr><th>No.</th><th>Asset Name</th><th>Device ID</th><th>Close since (Hrs)</th><th>Last Recieved Data</th><th>Details</th></tr>";
						}
						$users[$result1['username']]=$users[$result1['username']]+1;
						$email[$result1['username']]=$result1['email_address'];
						$mobile[$result1['username']]=$result1['mobile_number'];
						$client_name[$result1['username']]=$result1['username'];
						$usersName[$result1['username']]=$result1['name'];
						/*if($countr%2 == 0){ 
							$str.='<tr bgcolor=\'#999933\'>';
						}else{
							$str.="<tr>";
						}*/
						$str.="<tr>";
						$str.="<td align='center'>".addslashes($users[$result1['username']])."</td>";
						$str.="<td align='center'>".addslashes($result1['assets_name'])." </td>";
						$str.="<td align='center'>".addslashes($result1['device_id'])." </td>";
						$str.="<td align='center'>";
						$str.=addslashes(timeBetween(date('Y-m-d H:i:s'), $add_dates[$result1['device_id']], 2));
						$str.="&nbsp;</td>";
						$str.="<td align='center'>";
						$str.=addslashes(date(DISP_TIME, strtotime($add_dates[$result1['device_id']])));
						$str.="&nbsp;</td>";
						$str.="<td align='center'>";
						$str.=addslashes($reason_text[$result1['device_id']]);
						$str.="&nbsp;</td>";
						$str.="</tr>";				
						$innerTbl[$result1['username']].=$str;
					}
				}
			}
			$x=1;
			foreach($users as $key => $value){
					$deviceDown_bool=1;
					$tbl.="<tr>";
					$tbl.="<td align='center'>";
					$tbl.=$x;
					$tbl.="&nbsp;</td>";
					$tbl.="<td align='center'>";
					$tbl.=addslashes($client_name[$key]);
					$tbl.="&nbsp;</td>";
					$tbl.="<td align='center'>";
					$tbl.=addslashes($usersName[$key]);
					$tbl.="</td>";
					$tbl.="<td align='center'>";
					$tbl.=addslashes($users[$key]);
					$tbl.="&nbsp;</td>";
					$tbl.="<td align='center'>";
					$tbl.=addslashes($mobile[$key]);
					$tbl.="&nbsp;</td>";
					$tbl.="<td align='center'>";
					$tbl.=addslashes($email[$key]);
					$tbl.="&nbsp;</td>";
					$tbl.="</tr>";
					$x++;
			}
			$tbl.="</table><br/><br/>";
			
			$detailed_table.="<tr><th align='center'>No.</th><th align='center'>Client Name</th><th align='center'>Username</th><th align='center'>Device Down</th><th align='center'>Mobile</th><th align='center'>Email ID</th></tr>";
			$x=1;
			foreach($users as $key => $value){
					
					$detailed_table.="<tr>";
					$detailed_table.="<td align='center'>";
					$detailed_table.=$x;
					$detailed_table.="&nbsp;</td>";
					$detailed_table.="<td align='center'>";
					$detailed_table.=addslashes($client_name[$key]);
					$detailed_table.="&nbsp;</td>";
					$detailed_table.="<td align='center'>";
					$detailed_table.=addslashes($usersName[$key]);
					$detailed_table.="&nbsp;</td>";
					$detailed_table.="<td align='center'>";
					$detailed_table.=addslashes($users[$key]);
					$detailed_table.="&nbsp;</td>";
					$detailed_table.="<td align='center'>";
					$detailed_table.=addslashes($mobile[$key]);
					$detailed_table.="&nbsp;</td>";
					$detailed_table.="<td align='center'>";
					$detailed_table.=addslashes($email[$key]);
					$detailed_table.="&nbsp;</td>";
					$detailed_table.="</tr>";
					$detailed_table.="<tr>";
					$detailed_table.="<td align='center'>";
					$detailed_table.=" ";
					$detailed_table.="</td>";
					$detailed_table.="<td colspan=5 align='center'>";
					$detailed_table.="<table width='100%' border=1 cellpadding='4'>";
					$detailed_table.=$innerTbl[$key];
					$detailed_table.="</table>";
					$detailed_table.="</td>";
					$detailed_table.="</tr>";
					$x++;
			}
			
		}
		$detailed_table.="</table>";
		$expTable=$tbl.$detailed_table;
		echo $expTable;
		die();
	}
	if($cmd=="export_ignition"){
		header("Content-Type: application/vnd.ms-excel"); 
		header("Content-Disposition: attachment; filename=Ignition_Fault.xls");
			$qry1="SELECT device_id, ignition, speed from tbl_last_point where speed!=0 and ignition=0 and (device_id!='' and device_id is not null)";
			$ignition_tbl="<table border=1 cellpadding='4'>";
			$ignition_detailed_table="<table width='100%' border=1 cellpadding='4'>";
		$rs1 = mysql_query($qry1);
		
		if(mysql_num_rows($rs1)>0){
			$devices='';
			while($ans=mysql_fetch_array($rs1)){
				$devices.=$ans['device_id'].",";
				$ignition[$ans['device_id']]=$ans['ignition'];
				$speed[$ans['device_id']]=$ans['speed'];
			}
			$devices=trim($devices,",");
			$query1 = "SELECT am.id, am.device_id, am.assets_name, tu.username, concat(tu.first_name,' ',tu.last_name) as name, tu.mobile_number, tu.email_address from assests_master am left join user_assets_map uam on find_in_set(am.id,uam.assets_ids) left join tbl_users tu on uam.user_id=tu.user_id where username is not null and find_in_set(am.device_id,'".$devices."') and am.del_date is null and tu.del_date is null";
			$res1 = mysql_query($query1);
			//$tbl="<u><Strong>Devices which are not connected from Last 2 Hours or more.</strong></u>";
			
			$ignition_tbl.="<thead><tr><th>No.</th><th>Client Name</th><th >Username</th><th>Device Down</th><th>Mobile</th><th >Email ID</th></tr></thead><tbody>";
			$client_name=array();
			$mobile=array();
			$email=array();
			$users=array();
			$usersName=array();
			$innerignition_tbl=array();
			$countr1=0;
			if(mysql_num_rows($res1)>0){
				
				$y=1;
				while($result1=mysql_fetch_array($res1)){
					
					if($result1['username']!='vtslog'){
						$countr1++;
						$str="";
						if(!array_key_exists($result1['username'],$users)){
							$users[$result1['username']]=0;
							$str.="<tr><th>No.</th><th>Asset Name</th><th>Device ID</th><th>Ignition</th><th>Speed</th></tr>";
						}
						$users[$result1['username']]=$users[$result1['username']]+1;
						$email[$result1['username']]=$result1['email_address'];
						$mobile[$result1['username']]=$result1['mobile_number'];
						$client_name[$result1['username']]=$result1['username'];
						$usersName[$result1['username']]=$result1['name'];
						$str.="<tr>";
						$str.="<td align='center'>".addslashes($users[$result1['username']])."</td>";
						$str.="<td align='center'>".addslashes($result1['assets_name'])." </td>";
						$str.="<td align='center'>".addslashes($result1['device_id'])." </td>";
						$str.="<td align='center'>";
						$str.=addslashes($ignition[$result1['device_id']]);
						$str.="&nbsp;</td>";
						$str.="<td align='center'>";
						$str.=addslashes($speed[$result1['device_id']]);
						$str.="&nbsp;</td>";
						$str.="</tr>";				
						$innerignition_tbl[$result1['username']].=$str;
					}
				}
			}
			$x=1;
			foreach($users as $key => $value){
					$ignition_bool=1;
					$ignition_tbl.="<tr>";
					$ignition_tbl.="<td align='center'>";
					$ignition_tbl.=$x;
					$ignition_tbl.="&nbsp;</td>";
					$ignition_tbl.="<td align='center'>";
					$ignition_tbl.=addslashes($client_name[$key]);
					$ignition_tbl.="&nbsp;</td>";
					$ignition_tbl.="<td align='center'>";
					$ignition_tbl.=addslashes($usersName[$key]);
					$ignition_tbl.="</td>";
					$ignition_tbl.="<td align='center'>";
					$ignition_tbl.=addslashes($users[$key]);
					$ignition_tbl.="&nbsp;</td>";
					$ignition_tbl.="<td align='center'>";
					$ignition_tbl.=addslashes($mobile[$key]);
					$ignition_tbl.="&nbsp;</td>";
					$ignition_tbl.="<td align='center'>";
					$ignition_tbl.=addslashes($email[$key]);
					$ignition_tbl.="&nbsp;</td>";
					$ignition_tbl.="</tr>";
					$x++;
			}
			
			
			$ignition_detailed_table.="<tr><th align='center'>No.</th><th align='center'>Client Name</th><th align='center'>Username</th><th align='center'>Device Down</th><th align='center'>Mobile</th><th align='center'>Email ID</th></tr>";
			$x=1;
			foreach($users as $key => $value){				
					$ignition_detailed_table.="<tr>";
					$ignition_detailed_table.="<td align='center'>";
					$ignition_detailed_table.=$x;
					$ignition_detailed_table.="&nbsp;</td>";
					$ignition_detailed_table.="<td align='center'>";
					$ignition_detailed_table.=addslashes($client_name[$key]);
					$ignition_detailed_table.="&nbsp;</td>";
					$ignition_detailed_table.="<td align='center'>";
					$ignition_detailed_table.=addslashes($usersName[$key]);
					$ignition_detailed_table.="&nbsp;</td>";
					$ignition_detailed_table.="<td align='center'>";
					$ignition_detailed_table.=addslashes($users[$key]);
					$ignition_detailed_table.="&nbsp;</td>";
					$ignition_detailed_table.="<td align='center'>";
					$ignition_detailed_table.=addslashes($mobile[$key]);
					$ignition_detailed_table.="&nbsp;</td>";
					$ignition_detailed_table.="<td align='center'>";
					$ignition_detailed_table.=addslashes($email[$key]);
					$ignition_detailed_table.="&nbsp;</td>";
					$ignition_detailed_table.="</tr>";
					$ignition_detailed_table.="<tr>";
					$ignition_detailed_table.="<td align='center'>";
					$ignition_detailed_table.=" ";
					$ignition_detailed_table.="</td>";
					$ignition_detailed_table.="<td colspan=5 align='center'>";
					$ignition_detailed_table.="<table width='100%' border=1 cellpadding='4'>";
					$ignition_detailed_table.=$innerignition_tbl[$key];
					$ignition_detailed_table.="</table>";
					$ignition_detailed_table.="</td>";
					$ignition_detailed_table.="</tr>";
					$x++;
			}
			
		}
		$ignition_tbl.="</table><br/><br/>";
		$ignition_detailed_table.="</table>";
		$expTable=$ignition_tbl.$ignition_detailed_table;
		echo $expTable;
		die();
	}
	function timeBetween($startDate, $endDate, $format = 1)
	{
		list($date,$time) = explode(' ',$endDate);
		$startdate = explode("-",$date);
		$starttime = explode(":",$time);
	
		list($date,$time) = explode(' ',$startDate);
		$enddate = explode("-",$date);
		$endtime = explode(":",$time);
	
		$secondsDifference = mktime($endtime[0],$endtime[1],$endtime[2],
			$enddate[1],$enddate[2],$enddate[0]) - mktime($starttime[0],
				$starttime[1],$starttime[2],$startdate[1],$startdate[2],$startdate[0]);
		
		switch($format){
			// Difference in Minutes
			case 1: 
				return floor($secondsDifference/60);
			// Difference in Hours    
			case 2:
				return floor($secondsDifference/60/60);
			// Difference in Days    
			case 3:
				return floor($secondsDifference/60/60/24);
			// Difference in Weeks    
			case 4:
				return floor($secondsDifference/60/60/24/7);
			// Difference in Months    
			case 5:
				return floor($secondsDifference/60/60/24/7/4);
			// Difference in Years    
			default:
				return floor($secondsDifference/365/60/60/24);
		}                
	}   
?>