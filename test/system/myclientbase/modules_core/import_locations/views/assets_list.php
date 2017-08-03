<?php
 $date_format = $this->session->userdata('date_format');  
 $time_format = $this->session->userdata('time_format');  
 $js_date_format = $this->session->userdata('js_date_format');  
 $js_time_format = $this->session->userdata('js_time_format');
?>
<script>
$(document).ready(function(){
	var x,y;
	var text;
	var running="<?php echo $running_1; ?>";
	var parked="<?php echo $parked_1; ?>";
	var out_of_network="<?php echo $out_of_network_1; ?>";
	var device_fault="<?php echo $device_fault_1; ?>";
	var total="<?php echo $total_1; ?>";
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
	$("#listview_Container ul li .deviceMain").each(function(){
		var title= $(this).children(".hidden").html();
			$(this).qtip({
			   content: title,
			   show: { solo: true, when: { event: 'mouseover'} },
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
	$(".first_t_link").attr("src", '<?php echo base_url();?>assets/images/green_dot.png');
	$(".second_t_link").attr("src", '<?php echo base_url();?>assets/images/RedDot.png');
}
function displaySecond(){
	$(".first_t").hide();
	$(".second_t").show();
	$(".first_t_link").attr("src", '<?php echo base_url();?>assets/images/RedDot.png');
	$(".second_t_link").attr("src", '<?php echo base_url();?>assets/images/green_dot.png');
}
</script>
<style>
	#main ul{padding-left:10px;}
	.alist { list-style-type:none; }
	.alist li { width:175px; height:40px; float:left; padding:2px;}
	.deviceMain { width:170px; height:30px; padding-top:2px;padding-left:2px; padding-right:2px; }
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

	<div id="listview_Container" align="center">
	 <ul class="alist" style="width:100%;min-height:180px;">
	<?php if(count($coords) > 0) { 
		foreach ($coords as $coord) {
			$minutes = round(abs(strtotime(date('Y-m-d H:i:s')) - strtotime($coord->add_date)) / 60,2); 
			if($minutes > 20){
				$clr = "red";
			}else{
				$clr = "green";
			}
			
	?>
		<script type="text/javascript">
		assetNameArray[<?php echo $coord->assets_id; ?>] = '<?php echo $coord->assets_name; ?>';
		assetDeviceArray[<?php echo $coord->assets_id; ?>] = '<?php echo $coord->device_id; ?>';
		</script>
		<?php 
		$disp_icon=""; 
		$class = 'border_black';
	//	if($coord->speed >= $coord->old_speed ){ $disp_icon="SpeedUp.png"; }else{ $disp_icon="SpeedDown.png";}
		if($coord->speed > $coord->old_speed && $coord->old_speed!=NULL) {
			$disp_icon="SpeedUp.png";
		}
		else if($coord->speed < $coord->old_speed ) {
			$disp_icon="SpeedDown.png";
		}
		/*else {
			$disp_icon="SpeedUp.png";
		}*/
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
		$gsm_register_arr = array(0=>"Not registered, Searching.", 1=>"Registered, home network.", 2=>"Not registered, Searching.", 3=>"Registration denied.", 4=>"Unknown.", 5=>"Registered, roaming."); 

		//echo in_array($coord->device_id, $in_area);
		if($coord->captured_image==""){
		?>
		<li style="display:inline-block;white-space:nowrap;float:none"><div class="deviceMain <?php echo $class; ?>">
			<div style="float:left;width:62px;height: 22px;padding-left: 5px;padding-top: 6px; background-color:<?php echo $clr;//if($coord->speed > 0) echo "green"; else echo "red"; ?>;color:#FFF;font-size:16px;font-weight:bold;">
			<?php if(($clr=="green" && $coord->speed!=0) || $disp_icon != ""){ ?>
			<img src="<?php echo base_url(); ?>assets/images/<?php echo $disp_icon; ?>" />
			<?php }else { ?>
            <!--img src="<?php echo base_url(); ?>assets/images/<?php echo $disp_icon; ?>" title="Speed Change" /-->
			<span style="height: 18px; float: left; display: block; width: 15px;"></span >
			<?php } ?>
			<input onclick="selectedAssets(<?php echo $coord->assets_id; ?>);" name="assets_check[]" value="<?php echo $coord->assets_id; ?>" type="checkbox" style='padding: 0px; margin: 0px;'/> <span style="color: #FFFFFF;"><?php if($coord->speed=='0') echo '00'; else if($coord->speed!="" || $coord->speed!=null) echo $coord->speed; else echo "00";?></span></div>
			<div style="float:right;width:100px;padding-top:7px;font-size:14px;font-weight:bold;"><a style="cursor:pointer;" onclick="directTab(<?php echo $coord->device_id; ?>, <?php echo $coord->assets_id; ?>)" value="<?php echo date('d.m.Y h:i a',strtotime($coord->add_date)); ?>(<?php echo $coord->received_time; ?>)"><?php echo $coord->assets_name; ?></a></div>
			
			<div class="hidden">
			
			<div  class="ui-widget-header" style='line-height:15px;border-radius: 7px 7px 7px 7px;'><?php echo $this->lang->line("Before"); ?> <?php echo $coord->received_time.", ".date($date_format." ".$time_format,strtotime($coord->add_date)); ?> </div>
			<div style="line-height:15px" class="ui-widget-content">
				<div style="font-weight:bold;color:#000;font-size:17px;"><img src="<?php echo base_url()?>assets/images/green_dot.png" title="Assets Details" class="first_t_link" onmouseover="displayFirst()" style="cursor:pointer;color:red;">&nbsp;&nbsp;<img src="<?php echo base_url()?>assets/images/RedDot.png" title="Data Details" class="second_t_link" onmouseover="displaySecond()" style="cursor:pointer"></div>
				<div class="first_t">
				<span style='padding-top: 5px;display:block'><strong><?php echo $this->lang->line('Vehicle').": "; if($coord->assets_friendly_nm!="") echo $coord->	assets_friendly_nm; else echo $this->lang->line('N/A'); ?></strong></span>
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
				<?php echo $this->lang->line('Status'); ?>: <?php $minutes = $coord->beforeTime;
					if($minutes <= 1200 && $coord->speed > 0 && $minutes != ""){
							echo $this->lang->line('running');
					}else if($minutes <= 1200  && $coord->speed == 0 && $minutes != ""){
							echo $this->lang->line('parked');
					}else if($minutes >= 1201 && $minutes <= 86399 && $minutes != ""){
							echo $this->lang->line('out_of_network');
					}else if($minutes >= 86400 or $minutes ==""){
							echo $this->lang->line('out_of_network');
					}
				?></span>
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
				
				<?php if($coord->fuel_time!=""){ ?>
				<span style='display:block'>
				<?php
					$fuel_percent = $coord->fuel_percent;
					$max_liters = $coord->max_fuel_liters;
					$current_litters = ($fuel_percent * $max_liters) / 100;
					$f_percent=round($coord->fuel_percent);
					if($f_percent>100){
						$f_percent=100;
					}
				?>
				<?php echo $this->lang->line('fuel').": ".round($current_litters)." Ltr, ".$f_percent." % (".date("$date_format $time_format",strtotime($coord->fuel_time)).")"; ?> 
				</span><?php } ?>
				<?php if($coord->km_reading!=""){ ?>
				<span style='display:block'>
				<?php echo $this->lang->line('km_reading').": ".$coord->km_reading; ?> 
				</span><?php } ?>
				<?php if($coord->temperature!=""){ ?>
				<span style='display:block'>
				<?php 
				$tempr=$coord->temperature;
				if($tempr<0){
					$tempr="<span style='font-size:14px;'>-</span>".abs($tempr);
				}
				echo $this->lang->line('temperature').": ".$tempr. "&deg; C"; ?>
				</span><?php } ?>
				<?php if($this->session->userdata('show_dash_dashboard_button')==1){ ?>
				<span><input type="button" value="View Dashboard" class="ui-button ui-widget ui-state-default ui-button-text-only" onClick="loadAssetsDash_tt(<?php echo $coord->assets_id; ?>,'<?php echo $coord->assets_name; ?>')"/></span>
				<?php } ?>
				</div>
				<div class="second_t" style="display:none;">
					<?php if($coord->reason_text!=""){ ?>
					<span style='display:block'>
					<?php echo "<b>Status :</b> ".$coord->reason_text; ?> 
					</span><?php } ?>
					<?php if($coord->in_batt!=""){ ?>
					<span style='display:block'>
					<?php echo "<b>Internal Battery :</b> ".number_format(($coord->in_batt)/1000, 2)." Volt"; ?> 
					</span><?php } ?>
					<?php if($coord->ext_batt_volt!=""){ ?>
					<span style='display:block'>
					<?php echo "<b>Ext. Battery :</b> ".number_format(($coord->ext_batt_volt)/100, 2)." Volt"; ?> 
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
			<div style="float:left;width:97%;height: 22px;padding-left: 5px;padding-top: 6px; background-color:<?php echo $clr;//if($coord->speed > 0) echo "green"; else echo "red"; ?>;color:#FFF;font-size:16px;font-weight:bold;"><a style="cursor:pointer;" onclick="directTab(<?php echo $coord->device_id; ?>, <?php echo $coord->assets_id; ?>)" value="<?php echo date('d.m.Y h:i a',strtotime($coord->add_date)); ?>(<?php echo $coord->received_time; ?>)"><?php echo $coord->assets_name; ?></a></div>
			
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
	<div style="clear:both"></div>
	<?php 
		$reload = base_url() . "index.php/home/assets"; 
		if($page<=0)  $page  = 1;
	?>
	<span style="font-size: 12px;font-weight: bold;"><?php echo $this->lang->line('Last Data Recieved'); ?> : <?php echo date("$date_format $time_format"); ?> </span>
	<?php if($this->session->userdata('show_dash_distance_button')==1){ ?>
	<div id="distanceBtn_div" style="display:none;-webkit-box-shadow:0px 0px 12px rgba(0, 0, 0,0.4);-moz-box-shadow: 0px 1px 6px rgba(23, 69, 88, .5);">
	<!-- <img src="" height="10" width="10"/> -->
	<img src="<?php echo base_url(); ?>assets/upload_image/close.png" alt="close" style='background-color:white;height:12px;cursor:pointer;float:right;margin-top:-5px;border:1px solid lightblue;border-radius:3px;' onclick="closeDist()"/> 
	<a id="distanceBtn" style="cursor:pointer;" onClick="getAssetsDistance()" title="<?php echo $this->lang->line('view_distance'); ?>"><span class="float-distance"></span></a>
	</div>
	<?php }else { ?>
	<div id="distanceBtn_div" style="display:none"></div>
	<?php }?>
<div style="clear:both"></div>
	<?php if($this->session->userdata('show_dash_paging')==1){
	
	
function paginate($reload, $page, $tpages, $adjacents, $totalRecords, $limit,$language_formate) {

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
	$out.= ">100</option><option value='all'";
	if($limit == 'all')
		$out.= " selected='selected'";
	$out.= ">All</option>";
	$out.= "</select></span></div>";
	return $out;
}
echo paginate($reload, $page, $totalPage, 5, $totalRecords, $limit,$this->lang);
}
?>