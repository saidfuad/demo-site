<?php
 ob_start();
 $date_format = $this->session->userdata('date_format');  
 $time_format = $this->session->userdata('time_format');  
 $js_date_format = $this->session->userdata('js_date_format');  
 $js_time_format = $this->session->userdata('js_time_format');
 $reload = base_url() . "index.php/home/assets"; 
 if($page<=0)  $page  = 1;
 $kunal = array();

function paginate($reload, $page, $tpages, $adjacents, $totalRecords, $limit, $language_formate) {

	$prevlabel = $language_formate->line("prev");
	$nextlabel = $language_formate->line("next");
	$firstlabel = $language_formate->line("First");
	$out = '<div class="sixteen columns centre" id="bottomPaging">';
	if($tpages>1 && $page!=1)
	{
		$out.= "<span><a class='ui-state-default' style='cursor:pointer;' onclick='changePage(1)'>".$language_formate->line("First")."</a></span>\n";
	}
	else
	{
		$out.= "<span><a class='ui-state-default paginDisabled' style='cursor:pointer;'>".$language_formate->line("First")."</a></span>\n";
	}
	// previous
	if($page==1) {
		$out.= "<span><a class='ui-state-default paginDisabled'>" . $prevlabel . "</a></span>\n";
	}
	else {
		$out.= "<span><a class='ui-state-default' style='cursor:pointer;' onclick='changePage(" . ($page-1) . ")'>" . $prevlabel . "</a></span>\n";
	}
	// first
	if($page>($adjacents+1)) {
		$out.= "<a class='pagelink' onclick='changePage(1)'>1</a>\n";
	}
	
	// interval
	if($page>($adjacents+2)) {
		$out.= "...\n";
	}
	
	// pages
	$pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
	$pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
	for($i=$pmin; $i<=$pmax; $i++) {
		if($i==$page) {
			$out.= "<a class='activePage'>" . $i . "</a>\n";
		}
		else {
			$out.= "<a class='pagelink' onclick=changePage($i)>" . $i . "</a>\n";
		}
	}
	
	// interval
	if($page<($tpages-$adjacents-1)) {
		$out.= "...\n";
	}
	
	// last
	if($page<($tpages-$adjacents)) {
		$out.= "<a class='pagelink' onclick=changePage(" . $tpages . ")>" . $tpages . "</a>\n";
	}
	
	// next
	if($page<$tpages) {
		$out.= "<span><a class='ui-state-default' style='cursor:pointer;' onclick='changePage(" . ($page+1) . ")'>" . $nextlabel . "</a></span>\n";
	}
	else {
		$out.= "<span><a class='ui-state-default paginDisabled'>" . $nextlabel . "</a></span>\n";
	}
	
	if($tpages>1 && $page!=$tpages)
	{
		$out.= "<span onclick=changePage(" . ($tpages) . ")><a style='cursor:pointer;' class='ui-state-default'>".$language_formate->line("Last")."</a></span>  | \n";
	}
	else
	{
		$out.= "<span><a style='cursor:pointer;' class='ui-state-default paginDisabled'>".$language_formate->line("Last")."</a></span>  | \n";
	}
	$out.= '<span style="display: inline-block; margin-top: 10px;">'.$language_formate->line("Total Assets").' : <strong> '.$totalRecords.' </strong> | '.$language_formate->line("Number of Assets per page").' : ';
	
	$out.= "<select onchange='changeLimit(this.value)' style='margin:0' id='numAsset' >";
	$out .= "<option";
	if($limit == 20)
		$out.= " selected='selected'";
	$out.= ">20</option><option";
	if($limit == 40)
		$out.= " selected='selected'";
	$out.= ">40</option><option";
	if($limit == 60)
		$out.= " selected='selected'";
	$out.= ">60</option><option";
	if($limit == 80)
		$out.= " selected='selected'";
	$out.= ">80</option><option";
	if($limit == 100)
		$out.= " selected='selected'";
	$out.= ">100</option><option value='10000'";
	if($limit == '10000')
		$out.= " selected='selected'";
	$out.= ">All</option>";
	$out.= "</select></span></div>";
	return $out;
}
?>
<script>

$(document).ready(function(){
	if (dashboardMarkers) {
		for (i in dashboardMarkers) {
			dashboardMarkers[i].setMap(null);
			dLabelArr[i].setMap(null);
		}
	}

	dashboardMarkers = [];
	dLabelArr = [];

	var x,y;
	var text;
	var running="<?php echo $running_1; ?>";
	var parked="<?php echo $parked_1; ?>";
	var out_of_network="<?php echo $out_of_network_1; ?>";
	var device_fault="<?php echo $device_fault_1; ?>";
	var total="<?php echo $total_1; ?>";
	/*
	dashboardMap = document.getElementById("map_div");
		if (dashboardMap != 'undefined' && dashboardMap != null) {
	
		mapOptionsmap = {
			zoom: 13,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			mapTypeControl: true,
			mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DEFAULT}
		};
	
		mapOptionsmap.center = new google.maps.LatLng(
			22.297744,
			70.792444
		);
		
		dMap = new google.maps.Map(dashboardMap,mapOptionsmap);
		dMap.enableKeyDragZoom();
		dashboardBounds = new google.maps.LatLngBounds();
	}
	*/
	if(running!="")
	{
		$("#assets_running_1").html(running);
	}
	if(parked!="")
	{
		$("#assets_parked_1").html(parked);
	}
	if(out_of_network!="")
	{
		$("#assets_out_1").html(out_of_network);
	}
	if(device_fault!="")
	{
		$("#assets_fault_1").html(device_fault);
	}
	if(total!="")
	{
		$("#assets_total_1").html(total);
	}
	$("#listview_Container ul li .hovertext").each(function(){
		var title= $(this).parent().children(".hidden").html();
			$(this).qtip({
			   content: title,
			   show: { solo: true, when: { event: 'click'} },
			   hide: {
				fixed: true,
			   },
			   position: {
				corner: {
					target: 'bottomMiddle',
					tooltip: 'topMiddle'
				  },
				  adjust: { screen: true } 
				},
			style: {
					width: 200,
					padding: 5,
					textAlign: 'center',
					//widget:true,
					border: {
					 width: 1,
					 radius: 7,
					 color: $(".ui-state-default").css("color")
					}
				}
			});
		});
});
function hideQTip()
{
	$(".deviceMain").qtip('hide');
}
function displayFirst(){
	$(".second_t").hide();
	$(".first_t").show();
	$(".first_t_link").css("text-decoration","underline");
	$(".second_t_link").css("text-decoration","none");
	//$(".first_t_link").attr("src", '<?php echo base_url();?>assets/images/green_dot.png');
	//$(".second_t_link").attr("src", '<?php echo base_url();?>assets/images/RedDot.png');
}
function displaySecond(){
	$(".first_t").hide();
	$(".second_t").show();
	$(".first_t_link").css("text-decoration","none");
	$(".second_t_link").css("text-decoration","underline");
	//$(".first_t_link").attr("src", '<?php echo base_url();?>assets/images/RedDot.png');
	//$(".second_t_link").attr("src", '<?php echo base_url();?>assets/images/green_dot.png');
}
</script>
<style>
	#main ul{padding-left:10px;}
	.alist { list-style-type:none;width:100%;max-width:1200px; }
	.alist li { width:175px; height:40px; float:left; padding:2px;}
	.deviceMain { width:170px; height:30px; padding-top:2px;padding-left:2px; padding-right:2px; }
	.hovertext { background: url('<?php echo base_url();?>assets/images/blue-triangle.png') no-repeat scroll right bottom transparent; cursor: pointer; float: right; margin-top: -4px; margin-right: -3px; }
	.border_red {border:1px solid red;}
	.border_black {border:1px solid #999;}
	
	#bottomPaging span a{
		cursor: pointer; 
		border-radius: 2px 2px 2px 2px ! important; 
		padding: 2px 5px;
	}
	#bottomPaging span a:hover{
		padding: 3px 5px;
	}
	.paginDisabled{
		cursor: default !important;
		background: none !important; 
		padding: 2px 4px !important;  
		text-decoration: none !important;
	}
	
</style>
	<div style="clear:both"></div>
	<div style="font-size: 12px;font-weight: bold; padding-bottom: 10px;"><?php echo $this->lang->line('Last Data Recieved'); ?> : <?php echo date("$date_format $time_format"); ?> </div>
	<div style="clear:both"></div>
	<?php if($this->session->userdata('show_dash_legends')==1){ ?>
	<div style="padding-left:25px;margin-bottom:8px;">
		<center>
                    <ul style="list-style-type: none;">
			<li style="line-height: 16px; font-size:12px; display: inline-block; "><img src="<?php echo base_url(); ?>assets/images/running.png" />&nbsp;&nbsp;<?php echo $this->lang->line('running'); ?>/<?php echo $this->lang->line('idle'); ?>,</li>
			<li style="line-height: 16px; font-size:12px; display: inline-block; "><img src="<?php echo base_url(); ?>assets/images/parked.png" />&nbsp;&nbsp;<?php echo $this->lang->line('parked'); ?>,</li>
			<li style="line-height: 16px; font-size:12px; display: inline-block; "><img src="<?php echo base_url(); ?>assets/images/out_of_network.png" />&nbsp;&nbsp;<?php echo $this->lang->line('out_of_network'); ?><!-- / --><?php /* echo $this->lang->line('device_fault'); */ ?>,</li>
			<li style="line-height: 16px; font-size:12px; display: inline-block; "><img src="<?php echo base_url(); ?>assets/images/speed_limit.png" />&nbsp;&nbsp;<?php echo $this->lang->line('Speed Limit Cross'); ?>,</li>
			<li style="line-height: 16px; font-size:12px; display: inline-block; "><img src="<?php echo base_url(); ?>assets/images/landmark.png" />&nbsp;&nbsp;<?php echo $this->lang->line('Near Landmark'); ?>,</li>
			<li style="line-height: 16px; font-size:12px; display: inline-block; "><img src="<?php echo base_url(); ?>assets/images/geofence.png" />&nbsp;&nbsp;<?php echo $this->lang->line('In Area'); ?>,&nbsp;&nbsp;&nbsp;</li>
		</ul>
		</center>
	</div>
	<?php } ?>
	<div id="listview_Container" align="center">
	<?php
	foreach ($coords as $coord) {
		
		$group_by[$coord->username."(".$coord->first_name.")"][] = $coord;
	}
			
	foreach ($group_by as $key=>$coords) {
		if($this->session->userdata('usertype_id') == 1){
		echo "<br><div style='font-weight:bold;font-size:15px;color:black;'>".$key."</div>";
		}
	?> 
	<ul class="alist" style="">
	<?php if(count($coords) > 0) { 
		foreach ($coords as $coord) {
			
			if($coord->assets_category_id == 1 || $coord->assets_category_id == "" || $coord->assets_category_id == 0 || $coord->assets_category_id == 13){
				$image_type = "truck.png";
			}else if($coord->assets_category_id == 2){
				$image_type = "car.png";
			}
			else if($coord->assets_category_id == 3){
				$image_type = "bus.png";
			}
			else if($coord->assets_category_id == 4){
				$image_type = "mobile.png";
			}
			else if($coord->assets_category_id == 5){
				$image_type = "bike.png";
			}
			else if($coord->assets_category_id == 6){
				$image_type = "altenator.png";
			}
			else if($coord->assets_category_id == 7 || $coord->assets_category_id == 8){
				$image_type = "man.png";
			}
			else if($coord->assets_category_id == 9){
				$image_type = "stacker.png";
			}
			else if($coord->assets_category_id == 10){
				$image_type = "loader.png";
			}
			else if($coord->assets_category_id == 11){
				$image_type = "locomotive.png";
			}
			else if($coord->assets_category_id == 12){
				$image_type = "generator.png";
			}
			else if($coord->assets_category_id == 13){
				$image_type = "maintenance.png";
			}
			else if($coord->assets_category_id == 14){
				$image_type = "motor.png";
			}
			else if($coord->assets_category_id == 15){
				$image_type = "bobcat.png";
			}
			else if($coord->assets_category_id == 16){
				$image_type = "tractor.png";
			}
			else if($coord->assets_category_id == 17){
				$image_type = "car1.png";
			}
			else if($coord->assets_category_id == 18){
				$image_type = "satellite.png";
			}
			else if($coord->assets_category_id == 21){
				$image_type = "stacker.png";
			}
			else{
				$image_type = "truck.png";
			}
			
			$direction = $coord->angle_dir;
			$seconds_before = $coord->beforeTime;
			$text = "<b>".$coord->assets_name;
			if($coord->assets_friendly_nm!="" || $coord->assets_friendly_nm!=null)
				$text.=" (".$coord->assets_friendly_nm.") ";
				
			if($this->session->userdata('usertype_id')!=3){
				$text.=" (".$coord->device_id.")";
			}
			$text .= "</b><br>";
			$text .= $coord->received_time.", ".date("$date_format $time_format", strtotime($coord->add_date))."<br>";
			if($this->session->userdata('show_map_driver_detail_window')==1){
				
				if($coord->assets_image_path!= NULL || $coord->assets_image_path!="")
				{
					$text .= "<img src='".base_url()."assets/assets_photo/".$coord->assets_image_path."' /><br>";
				}
				
				if($coord->driver_image!= NULL || $coord->driver_image!="")
				{
					$text .= "<img src='".base_url()."assets/driver_photo/".$coord->driver_image."' /><br>";
				}
			}			
			if($coord->ignition == 0)
				$ignition = "OFF";
			else 
				$ignition = "ON";
			$text .="Ignition: ".$ignition." , Speed: ".$coord->speed." KM<br>";
			
			$tag = '';
			if($coord->driver_name != ""){
				//$tag = $row->driver_name.", ";
			}
			$tag .= substr($coord->assets_name, -4);
			//$tag .= $coord->speed." KM";
			
			if($coord->address != "")
				$text .= " ".$coord->address."<br>";
			
			if($this->session->userdata('show_dash_legends')==1){
				$text .="Status: ";
				if($seconds_before < $this->session->userdata('network_timeout') && $coord->speed > 10 && $seconds_before != ""){
						$status ="Running";
						$status_img = "green_dot.png";
						$color = "green";
				}else if($seconds_before < $this->session->userdata('network_timeout') && $coord->speed <= 10 && $coord->ignition == 0 && $seconds_before != ""){
						$status ="Parked";
						$status_img = "blue_dot.png";
						$color = "#06F";
				}else if($seconds_before < $this->session->userdata('network_timeout') && $coord->speed <= 10 && $coord->ignition == 1 && $seconds_before != ""){
						$status ="Idle";
						$status_img = "green_dot.png";
						$color = "green";
				}else if($seconds_before >= $this->session->userdata('network_timeout') && $seconds_before <= ($this->session->userdata('network_timeout')+36000) && $seconds_before != ""){
						$status ="Out of network";
						$status_img = "RedDot.png";
						$color = "orange";
				}else if($seconds_before > ($this->session->userdata('network_timeout')+36000) or $seconds_before ==""){
						$status ="Out of network";
						$status_img = "RedDot.png";
						 $color = "orange";
				}
			}
			$text .= $status."<br>";
							
			if($status == "Parked")
				$text .="Parked From : ".$coord->stop_from."<br>";
/*			
			if($coord->routename != ""){
				$text .="Route : ".$coord->routename."<br>";
				if($coord->landmark_n != ""){						
					$text .="Next Landmark : ".$coord->landmark_n."<br>";
				}
			}
*/			
				
			if($this->session->userdata('show_map_driver_detail_window')==1){
			
				if($coord->driver_name!="" || $coord->driver_name!=null) 
				$text .="Driver Name: ".$coord->driver_name."<br>"; 
			
				if($coord->driver_mobile!="" || $coord->driver_mobile!=null) 
				$text .="Driver Mob.:".$coord->driver_mobile."<br>"; 
			}
			
			
			$assets_name = explode("-", $coord->assets_name);
			$assets_name = end($assets_name);
			$prefix = $coord->assets_id;

			$seconds_before = round(abs(strtotime(date('Y-m-d H:i:s')) - strtotime($coord->add_date)) / 60,2); 
			if($seconds_before > ($this->session->userdata('network_timeout')/60)){
				$clr = "orange";
			}else if($coord->ignition == 0 && $coord->speed <= 10){
				$clr = "#06F";
			}else{
				$clr = "green";
			}
			
	?>
		<?php if((floatval($coord->lati) != 0) && (floatval($coord->longi) != 0)) {
						$kunal[] = array('assets_id' => $coord->assets_id, 'image_type' => $image_type, 'lat' => floatval($coord->lati), 'lng' => floatval($coord->longi), 'boxtext' => "<div style='line-height: 15px; font-size: 11px; font-weight: bold; color: #2E6E9E; border: 1px solid black; background: none repeat scroll 0% 0% #DFEFFC; padding: 2px; margin-top: 4px; text-align:center; -moz-border-radius: 8px; border-radius: 8px; white-space: nowrap;'><img src='".base_url()."assets/images/".$status_img."' title='".$status."'><img src='".base_url()."assets/images/direction.jpg' style='transform: rotate(".intval($direction)."deg);-ms-transform:rotate(".intval($direction)."deg);-webkit-transform:rotate(".intval($direction)."deg);' title='Direction'>&nbsp;".addslashes($coord->assets_name.", ". date('$date_format $time_format',strtotime($coord->add_date)))."</div>", 'assets_name' => $coord->assets_name, 'text' => $text, 'device_id' => $coord->device_id);
		} ?>
		<?php 
		$disp_icon=""; 
		$class = 'border_black';
	//	if($coord->speed >= $coord->old_speed ){ $disp_icon="SpeedUp.png"; }else{ $disp_icon="SpeedDown.png";}
		if($coord->speed > $coord->old_speed ) {
			$disp_icon="SpeedUp.png";
		}
		else if($coord->speed < $coord->old_speed ) {
			$disp_icon="SpeedDown.png";
		}
		else {
			$disp_icon="SpeedUp.png";
		}
		if($clr!="green"){
			$disp_icon = '';
		}		
		if($coord->current_area != ""){
			$disp_icon="geofence.png";
		}
		if($coord->current_landmark != ""){
			$disp_icon="landmark.png";
		}
		
		if($coord->cross_speed == 1){
			$class = 'border_red';
		} 
		$ast_class="";
		$seconds_before = $coord->beforeTime;
		if($seconds_before < $this->session->userdata('network_timeout') && $coord->speed > 0 && $seconds_before != ""){
				$ast_class='running_asts';
		}else if($seconds_before < $this->session->userdata('network_timeout') && $coord->speed == 0 && $seconds_before != ""){
				$ast_class='parked_asts';
		}else if($seconds_before >= $this->session->userdata('network_timeout') && $seconds_before <= ($this->session->userdata('network_timeout')+36000) && $seconds_before != ""){
				$ast_class='out_of_network_asts';
		}else if($seconds_before >= ($this->session->userdata('network_timeout')+36000) or $seconds_before ==""){
				$ast_class='device_fault_asts';
		}
		$gsm_register_arr = array(0=>"Not registered, Searching.", 1=>"Registered, home network.", 2=>"Not registered, Searching.", 3=>"Registration denied.", 4=>"Unknown.", 5=>"Registered, roaming."); 

		//echo in_array($coord->device_id, $in_area);
		if($coord->captured_image==""){
		?>
		<li style="display:inline-block;white-space:nowrap;float:none;"><div class="deviceMain <?php echo $class; ?>">
			<div style="float:left;width:62px;height: 22px;padding-left: 5px;padding-top: 6px; background-color:<?php echo $clr; ?>;color:#FFF;font-size:16px;font-weight:bold;">
			<?php if(($clr=="green" && $coord->speed!=0) || $disp_icon != ""){ ?>
			<img src="<?php echo base_url(); ?>assets/images/<?php echo $disp_icon; ?>" />
			<?php }else { ?>
            <!--img src="<?php echo base_url(); ?>assets/images/<?php echo $disp_icon; ?>" title="Speed Change" /-->
			<span style="height: 18px; float: left; display: block; width: 15px;"></span>
			<?php } ?>
			<input onclick="selectedAssets(<?php echo $coord->assets_id; ?>);" name="assets_check[]" value="<?php echo $coord->assets_id; ?>" type="checkbox" class="asset_checkbox <?php echo $ast_class; ?>" style='padding: 0px; margin: 0px;'/> <span style="color: #FFFFFF;"><?php if($coord->speed=='0') echo '00'; else if($coord->speed!="" || $coord->speed!=null) echo $coord->speed; else echo "00";?></span></div>
			<div style="float:right;width:100px;padding-top:7px;font-size:14px;font-weight:bold;"><a style="cursor:pointer;" onclick="directTab(<?php echo $coord->device_id; ?>, <?php echo $coord->assets_id; ?>)" value="<?php echo date("$date_format $time_format" ,strtotime($coord->add_date)); ?>(<?php echo $coord->received_time; ?>)"><?php echo $coord->assets_name; ?></a></div>
			<div class="hovertext">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>			
			<div class="hidden">
			
			<div  class="ui-widget-header" style='line-height:15px;border-radius: 7px 7px 7px 7px;'>
			<?php if($coord->add_date != ""){
				if($ast_class == 'out_of_network_asts' || $ast_class == 'device_fault_asts'){
					echo "<span class='blinking'>";
				}
				//echo $this->lang->line("Before"); ?> <?php echo $coord->received_time.", ".date($date_format." ".$time_format,strtotime($coord->add_date)); 
				if($ast_class == 'out_of_network_asts' || $ast_class == 'device_fault_asts'){
					echo "</span>";
				}
			}else{
				echo "Date Not Available";
			}
			?> 
			</div>
			<div style="line-height:15px" class="ui-widget-content">
				<div style="font-weight:bold;color:#000;font-size:17px;">
					<span class="first_t_link" onmouseover="displayFirst()" style="cursor:pointer;color:#2E6E9E;font-size:11px;text-decoration:underline;">Vehicle</span>
					&nbsp;&nbsp;
					
					<span class="second_t_link" onmouseover="displaySecond()" style="cursor:pointer;color:#2E6E9E;font-size:11px;">Data</span>
					
					<!--img src="<?php echo base_url()?>assets/images/green_dot.png" title="Assets Details" class="first_t_link" onmouseover="displayFirst()" style="cursor:pointer;color:red;">&nbsp;&nbsp;<img src="<?php echo base_url()?>assets/images/RedDot.png" title="Data Details" class="second_t_link" onmouseover="displaySecond()" style="cursor:pointer"-->
				</div>
				<div class="first_t">
				<span style='padding-top: 5px;display:block'><strong><?php echo $this->lang->line('Vehicle').": "; if($coord->assets_friendly_nm!="") echo $coord->assets_friendly_nm; else echo $this->lang->line('N/A'); ?></strong>(<?php echo $coord->device_id; ?>)</span>
				<span style='display:block'>
				<?php
				echo $this->lang->line('Drv_Nm').": ";
				if($coord->driver_name!=""){
					$arr=explode(",",$coord->driver_name);
					$arr_m=explode(",",$coord->driver_mobile);
					if(count($arr)>0){
						for($i=0;$i<count($arr);$i++){
							echo $arr[$i];
							if(array_key_exists($i,$arr_m)){
							echo " (".$arr_m[$i].")";
							}
							if($i<count($arr)-1){
								echo ",<br/>";
							}
						}
					}			
				}else { echo "N/A"; } 
				?>
				</span>
				<div style="display:block">
				<?php 
				if($coord->address!="")
				{ 
					echo $coord->address;
				}
				?>
				</div>
				<span style="display:block">
				<?php echo $this->lang->line('Status'); ?>: <?php $seconds_before = $coord->beforeTime;
					if($seconds_before < $this->session->userdata('network_timeout') && $coord->speed > 10 && $seconds_before != ""){
							echo $this->lang->line('running');
					}else if($seconds_before < $this->session->userdata('network_timeout') && $coord->speed <= 10 && $coord->ignition == 0 && $seconds_before != ""){
							echo $this->lang->line('parked');
					}else if($seconds_before < $this->session->userdata('network_timeout') && $coord->speed <= 10 && $coord->ignition == 1 && $seconds_before != ""){
							echo $this->lang->line('idle');
					}else if($seconds_before >= $this->session->userdata('network_timeout') && $seconds_before <= ($this->session->userdata('network_timeout')+36000) && $seconds_before != ""){
							echo $this->lang->line('out_of_network');
					}else if($seconds_before >= ($this->session->userdata('network_timeout')+36000) or $seconds_before ==""){
							echo $this->lang->line('out_of_network');
					}
				?></span>
				<?php	
				if($coord->current_zone!=""){ ?>
				<span style='display:block'>
				<?php echo "Current Zone: ".$coord->current_zone; ?>
				</span>
				<?php } ?>
				<?php	
				if($coord->current_area!=""){ ?>
				<span style='display:block'>
				<?php echo $this->lang->line('current_area').": ".$coord->current_area; ?>
				</span>
				<?php }   ?>
				<?php if($coord->current_landmark!=""){ ?>
				<span style='display:block'>
				<?php echo $this->lang->line('current_landmark').": ".$coord->current_landmark; ?>
				</span><?php } ?>
				
				<?php /*if($coord->fuel_time!=""){ ?>
				<span style='display:block'>
				<?php
					$fuel_percent = $coord->fuel_percent;
					$max_liters = $coord->max_fuel_liters;
					$current_litters = $coord->fuel_liter; //($fuel_percent * $max_liters) / 100;
					$f_percent=round($coord->fuel_percent);
					if($f_percent>100){
						$f_percent=100;
					}
				?>
				<?php echo $this->lang->line('fuel').": ".round($current_litters)." Ltr, ".$f_percent." % (".date("$date_format $time_format",strtotime($coord->fuel_time)).")"; ?> 
				</span><?php }*/ ?>
				<?php if($coord->km_reading!=""){ ?>
				<span style='display:block'>
				<?php echo $this->lang->line('km_reading').": ".$coord->km_reading; ?> 
				</span><?php } ?>
				<?php if($coord->runtime!=""){ ?>
				<span style='display:block'>
				<?php echo $this->lang->line('running_time').": ".$coord->runtime; ?> 
				</span><?php } ?>
				<?php if($ast_class=='parked_asts'){ ?>
				<span style='display:block'>
				Parked From : <?php echo $coord->stop_from; ?> 
				</span><?php } ?>
				<?php /* if($coord->routename!=''){ ?>
				<span style='display:block'>
				Route : <?php echo $coord->routename; ?> 
				</span><?php } */ ?>
				<?php if($coord->MainPower!=''){ ?>
				<span style='display:block'>
				Device Power Status : <?php echo $coord->MainPower; ?> 
				</span><?php } ?>
				<?php if($coord->ignition!=''){ ?>
				<span style='display:block'>
				Ignition Status : <?php echo ($coord->ignition == 1 ? "ON" : "OFF"); ?> 
				</span><?php } ?>
				<?php if($coord->Blender!=''){ ?>
				<span style='display:block'>
				Air Conditioner Status : <?php echo $coord->Blender; ?> 
				</span><?php } ?>
				<?php if($coord->alarm_status!=''){ ?>
				<span style='display:block'>
				Panic Status : <?php echo ($coord->alarm_status == 1 ? "ON" : "OFF"); ?> 
				</span><?php } ?>
				<?php /* if($coord->EmptyHeavy!=''){ ?>
				<span style='display:block'>
				EmptyHeavy : <?php echo $coord->EmptyHeavy; ?> 
				</span><?php } ?>
				<?php if($coord->FrontDoor !=''){ ?>
				<span style='display:block'>
				FrontDoor : <?php echo $coord->FrontDoor; ?> 
				</span><?php } ?>
				<?php if($coord->BackDoor!=''){ ?>
				<span style='display:block'>
				BackDoor : <?php echo $coord->BackDoor; ?> 
				</span><?php } ?>
				<?php if($coord->PutBack!=''){ ?>
				<span style='display:block'>
				PutBack : <?php echo $coord->PutBack; ?> 
				</span><?php } ?>
				<?php if($coord->Vibration!=''){ ?>
				<span style='display:block'>
				Vibration : <?php echo $coord->Vibration; ?> 
				</span><?php } */ ?>
				<?php if($coord->temperature!=""){ ?>
				<span style='display:block'>
				<?php 
				$tempr=$coord->temperature;
				if($tempr<0){
					$tempr="<span style='font-size:14px;'>-</span>".abs($tempr);
				}
				echo $this->lang->line('temperature').": ".$tempr. "&deg; C"; ?>
				</span><?php } ?>
				
				
				<?php if($coord->fuel_in_out_sensor==1 && $coord->fuel_in_per_lit>0 && $coord->fuel_out_per_lit>0 ){ ?>
				<span style='display:block'>
				<?php
					/*$query = "select command_key_value,CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date from tbl_track where command_key_value like '%I%' and assets_id	='".$coord->assets_id."' order by add_date desc limit 1 ";
					$in_res =$this->db->query($query);
					if($in_res->num_rows()==1){
						$in_row= $in_res->result_Array();
						$query = "select command_key_value from tbl_track where command_key_value like '%O%' and assets_id	='".$coord->assets_id."' order by add_date desc limit 1 ";
						$out_res =$this->db->query($query);
						if($out_res->num_rows()==1){
							$out_row= $out_res->result_Array();		
		
							$str_in = array(
								"I",
								"O",
							);
							
							$str_out = array(
								"",
								"",
							);
								$in_row[0]['command_key_value'] = preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $in_row[0]['command_key_value']);
								$out_row[0]['command_key_value'] = preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $out_row[0]['command_key_value']);
								$in = round(intval(str_replace($str_in,$str_out,$in_row[0]['command_key_value']))/$coord->fuel_in_per_lit,2);
								$out = round(intval(str_replace($str_in,$str_out,$out_row[0]['command_key_value']))/$coord->fuel_out_per_lit,2);
								echo $this->lang->line('Used_Fuel').": ".round($in-$out,2)." Liters"; 
								echo "<Br>(".ago($in_row[0]['add_date'])." Ago )"; 
						}
					}
					*/
					$fuel_in_per_lit = $coord->fuel_in_per_lit;
					$fuel_out_per_lit = $coord->fuel_out_per_lit;
					
					/*$url = "http://nkonnect.com/track/fuel_inout.php";
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$result = curl_exec($ch);
					curl_close($ch);
					$result = json_decode($result, true);
					*/
					$in_fuel = $coord->fuel_in;		//$result['in'];
					$in_fuel = preg_replace("/[^0-9,.]/", "", $in_fuel);
					$in_fuel = $in_fuel * 2;
					$in_fuel = $in_fuel/$fuel_in_per_lit;
					$out_fuel = $coord->fuel_out;	//$result['out'];
					$out_fuel = preg_replace("/[^0-9,.]/", "", $out_fuel);
					$out_fuel = $out_fuel/$fuel_out_per_lit;
					//echo "In Fuel : ".round($in_fuel-$out_fuel,2)." Liters"; 
					//echo "Out Fuel : ".round($in_fuel-$out_fuel,2)." Liters"; 
					echo "Used Fuel : ".round($in_fuel-$out_fuel,2)." Liters"; 
					
					//echo "Used Fuel : ".$coord->fuel_used." Liters";
				?>
				</span><?php } ?>
				<?php if($coord->xyz_sensor==1 ){ ?>
				<span style='display:block'>
				<?php
					$query = "select command_key_value,CONVERT_TZ(add_date,'+00:00','".$this->session->userdata('timezone')."') as add_date from tbl_track where (command_key_value NOT like '%I%' AND reason =  'V' and command_key_value NOT like '%O%') and  assets_id	='".$coord->assets_id."' order by add_date desc limit 1 ";
					$xyz_res =$this->db->query($query);
					if($xyz_res->num_rows()==1){
						$xyz_row= $xyz_res->result_Array();
						$xyz_row[0]['command_key_value'] = preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $xyz_row[0]['command_key_value']);
					$valve_array = array(900,1000,1100,5400,1300,1400,1500,1600);
					$valve_reading = $xyz_row[0]['command_key_value'];
					$valve_reading = substr($valve_reading, 0, 4);
					for($vi=0; $vi<count($valve_array);$vi++){
						if($valve_reading < $valve_array[$vi]){
							$image_no = $vi+1;
							break;
						}elseif($valve_reading >= $valve_array[$vi] && $valve_reading < $valve_array[$vi+1]){
							$image_no = $vi+1;
							break;
						}elseif($valve_reading >= end($valve_array)){
							$image_no = 8;
							break;
						}
					}
				?>
				<img src="<?php echo base_url(); ?>assets/images/valve_<?php echo $image_no; ?>.png" width="150" height="150"/>
				
				</span><?php
				echo "(".ago($xyz_row[0]['add_date'])." Ago )"; 
				} } ?>
				
				
				<?php if($this->session->userdata('show_dash_dashboard_button')==1){ ?>
				<span><input type="button" value="View Dashboard" class="ui-button ui-widget ui-state-default ui-button-text-only" onClick="loadAssetsDash_tt(<?php echo $coord->assets_id; ?>,'<?php echo $coord->assets_name; ?>')"/></span>
				<?php } ?>
				</div>
				<div class="second_t" style="display:none;">
					<?php if($coord->reason_text!=""){ ?>
					<span style='display:block'>
					<?php echo "<b>Status :</b> ".$coord->reason_text; ?> 
					</span><?php } ?>
					<?php if($coord->sim_number!=""){ ?>
					<span style='display:block'>
					<?php echo "<b>Sim Number :</b> ".$coord->sim_number; ?> 
					</span><?php } ?>
					<?php if($coord->assets_owner!=""){ ?>
					<span style='display:block'>
					<?php echo "<b>Onwer :</b> ".$coord->assets_owner; ?> 
					</span><?php } ?>
					<?php if($coord->assets_division!=""){ ?>
					<span style='display:block'>
					<?php echo "<b>Division :</b> ".$coord->assets_division; ?> 
					</span><?php } ?>
					<?php if($coord->in_batt!=""){ ?>
					<span style='display:block'>
					<?php echo "<b>Internal Battery :</b> ".number_format(($coord->in_batt)/1000, 2)." Volt"; ?> 
					</span><?php } ?>
					<?php if($coord->ext_batt_volt!=""){ ?>
					<span style='display:block'>
					<?php /* echo "<b>Ext. Battery :</b> ".number_format(($coord->ext_batt_volt)/100, 2)." Volt"; */ ?> 
					<?php echo "<b>Ext. Battery :</b> ".number_format($coord->ext_batt_volt, 2)." Volt"; ?> 
					</span><?php } ?>
					<?php if($coord->command_key!="" && $coord->command_key!=0){ ?>
					<span style='display:block'>
					<?php echo "<b>Command Key :</b> ".$coord->command_key; ?> 
					</span><?php } ?>
					<?php if($coord->command_key_value!=""){ ?>
					<span style='display:block'>
					<?php echo "<b>Command Key Value :</b> ".$coord->command_key_value; ?> 
					</span><?php } ?>
					<?php if($coord->msg_key!="" && $coord->msg_key!=0){ ?>
					<span style='display:block'>
					<?php echo "<b>Msg Key :</b> ".$coord->msg_key; ?> 
					</span><?php } ?>
					<?php if($coord->sat_mode!=""){ ?>
					<span style='display:block'>
					<?php echo "<b>Satellite Mode :</b> ";
						if($coord->sat_mode == "0")
							echo "No Satellite";
						else if($coord->sat_mode == "1")
							echo "Searching for satellite";
						else if($coord->sat_mode == "2")
							echo "2 dimensional satellite";
						else if($coord->sat_mode == "3")
							echo "3 dimensional satellite solution";
					?> 
					</span><?php } ?>
					<?php if($coord->gsm_strength!=""){ ?>
					<span style='display:block'>
					<?php 
						if($coord->gsm_strength==99){ 
							$gsm_strength = "No Network";
						}else if($coord->gsm_strength > 24 && $coord->gsm_strength < 32){
							$gsm_strength = "FULL";
						}else if($coord->gsm_strength > 10 && $coord->gsm_strength < 25){
							$gsm_strength = "MEDIUM";
						}else if($coord->gsm_strength < 11){
							$gsm_strength = "LOW";
						}
						$gsm_strength = $gsm_strength."(".$coord->gsm_strength.")";
					?>
					<?php echo "<b>GSM Strength :</b> ".$gsm_strength; ?> 
					</span><?php } ?>
					<?php if($coord->gps_fixed!=""){ ?>
					<span style='display:block'>
					<?php echo "<b>GPS Fixed :</b> "; 
						if($coord->gps_fixed == "A")
							echo "Valid Data";
						else if($coord->gps_fixed == "V")
							echo "Invalid Or Void Data";
					?> 
					</span><?php } ?>
					<?php if($coord->gsm_register!=""){ ?>
					<span style='display:block'>
					<?php echo "<b>GSM Register :</b> ".$gsm_register_arr[$coord->gsm_register]; ?> 
					</span><?php } ?>
					<?php if($coord->gprs_register!=""){ 
					if($coord->gprs_register == 5 || $coord->gprs_register == 14){
						$gprs_register = "OK";
					}else{
						$gprs_register = "NO GPRS";
					}
					?>
					<span style='display:block'>
					<?php echo "<b>GPRS Register :</b> ".$gprs_register; ?> 
					</span><?php } ?>
				</div>
			</div>
			</div>
		</li><?php }else{ ?>
		<li style="display:inline-block;white-space:nowrap;float:none"><div class="deviceMain <?php echo $class; ?>">
			<div style="float:left;width:97%;height: 22px;padding-left: 5px;padding-top: 6px; background-color:<?php echo $clr;//if($coord->speed > 0) echo "green"; else echo "red"; ?>;color:#FFF;font-size:16px;font-weight:bold;"><a style="cursor:pointer;" onclick="directTab(<?php echo $coord->device_id; ?>, <?php echo $coord->assets_id; ?>)" value="<?php echo date("$date_format $time_format",strtotime($coord->add_date)); ?>(<?php echo $coord->received_time; ?>)"><?php echo $coord->assets_name; ?></a></div>
			
			<div class="hidden">
			<div  class="ui-widget-header" style='line-height:15px;border-radius: 7px 7px 7px 7px;'><?php echo $this->lang->line("Before"); ?> <?php echo $coord->received_time.", ".date($date_format." ".$time_format,strtotime($coord->add_date)); ?> </div>
			<div style="line-height:15px" class="ui-widget-content">
			<div style="height:132px;width:175px">
			<img src="<?php echo base_url(); ?>assets/captured/<?php echo $coord->captured_image?>" width="175" height="131.25"/>
			</div>
			<span><input type="button" value="View Images" class="ui-button ui-widget ui-state-default ui-button-text-only" onClick="loadImagesTab(<?php echo $coord->assets_id; ?>,'<?php echo $coord->assets_name; ?>')"/></span>
			</div>
			</div>
		</li>
	<?php
		}
		}
	}
	else
	{
		echo "<li style='padding-top:10%;float:none'>No Data Found</li>";
	}
	
	?>
	</ul>
	<?php } ?>
	<div style="clear:both"></div>
	<?php echo paginate($reload, $page, $totalPage, 5, $totalRecords, $limit, $this->lang); ?>
	<?php if($this->session->userdata('show_dash_distance_button')==1){ ?>
	<!-- div id="distanceBtn_div" style="display:none;-webkit-box-shadow:0px 0px 12px rgba(0, 0, 0,0.4);-moz-box-shadow: 0px 1px 6px rgba(23, 69, 88, .5);">
	<img src="<?php echo base_url(); ?>assets/upload_image/close.png" alt="close" style='background-color:white;height:12px;cursor:pointer;float:right;margin-top:-5px;border:1px solid lightblue;border-radius:3px;' onclick="closeDist()"/> 
	<a id="distanceBtn" style="cursor:pointer;" onClick="getAssetsDistance()" title="<?php echo $this->lang->line('view_distance'); ?>"><span class="float-distance"></span></a>
	</div -->
	<?php }else { ?>
	<!-- div id="distanceBtn_div" style="display:none"></div -->
	<?php }?>
<div style="clear:both"></div>
<script type="text/javascript">
var kunal = [];

<?php foreach($kunal as $key => $val) { ?>
<?php
	$str = array();
	
	foreach($val as $name => $value) {
		$str[] = "'".$name."' : \"".$value."\"";
	?>
<?php } ?>
	kunal.push({<?php echo implode(',', $str);?>});
	
<?php } ?>


	//dMap.fitBounds(dashboardBounds);
	<?php /* google analytic code. */ ?>
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-37380597-1']);
	_gaq.push(['_trackPageview']);

	(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script>
<script type="text/javascript">
//	alert("Lat: " + kunal[0].lat+", Lng: "+ kunal[0].lng+", Ass: "+ kunal[0].assets_name+", Txt: "+ kunal[0].text+", Type: "+ kunal[0].image_type+", Id: "+ kunal[0].assets_id+", Device: "+ kunal[0].device_id);

	for(i=0; i< kunal.length; i++) {
		var point = new google.maps.LatLng(kunal[i].lat, kunal[i].lng);

		var myOptions1 = {
			 content: kunal[i].boxText
			,disableAutoPan: true
			,maxWidth: 500
			,position: point
			,pixelOffset: new google.maps.Size(-90,0)
			,zIndex: null
			,boxStyle: { 
			  //background: "url('tipbox.gif') no-repeat"
			  opacity: 0.75
			  ,width: "auto"
			 }
			,closeBoxMargin: "10px 2px 2px 2px"
			,closeBoxURL: ""
			//http://www.google.com/intl/en_us/mapfiles/close.gif
			,infoBoxClearance: new google.maps.Size(1, 1)
			,isHidden: false
			,pane: "floatPane"
			,enableEventPropagation: true
		};
		dLabel = new InfoBox(myOptions1);                
		dLabel.open(dMap);
		dLabelArr[kunal[i].assets_id] = dLabel;

		dashboardMarkers[kunal[i].assets_id] = createMarker(dMap, point, kunal[i].assets_name, "<div style='text-align:left;'>"+kunal[i].text+"</div>", kunal[i].image_type, '', 'sidebar_map', '', '', kunal[i].assets_id);

		assetNameArray[kunal[i].assets_id] = "'"+kunal[i].assets_name+"'";
		assetDeviceArray[kunal[i].assets_id] = "'"+kunal[i].device_id+"'";
			
	}
		</script>


	<?php if($this->session->userdata('show_dash_paging')==1){
}

ob_end_flush()
?>