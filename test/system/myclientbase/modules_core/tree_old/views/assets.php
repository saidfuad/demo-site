<?php
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format');  
	 $js_time_format = $this->session->userdata('js_time_format');  
?>
<style type="text/css">
.vehical{
	cursor : pointer;
}
#bottomBigPaging span a{
	cursor: pointer; 
	border-radius: 7px 7px 7px 7px ! important; 
	padding: 2px 5px;
}
#bottomBigPaging span a:hover{
	padding: 3px 5px;
}
.paginDisabled{
	cursor: default !important;
	background: none !important; 
	padding: 2px 4px !important;  
	text-decoration: none !important;
}
</style>
<div style="min-height:180px">
<?php 
	$i = 0;
	if(count($coords) > 0) { 
	foreach ($coords as $coord) {
		$minutes = round(abs(strtotime(date('Y-m-d H:i:s')) - strtotime($coord->add_date)) / 60,2); 
		
		if($minutes > 20){
			$clr = "#C82D43";
		}else{
			$clr = "#207335";
		}
		
		$disp_icon=""; 
		///$class = 'deviceMain';
		if($coord->speed > $coord->old_speed )
			{ $disp_icon="SpeedUp.png"; }
		if($coord->speed < $coord->old_speed )
		{ $disp_icon="SpeedDown.png";}
		if($clr!="#207335"){
				$disp_icon = '';
		}		
		if($coord->current_area != ""){
			$disp_icon="geofence.png";
		}
		if($coord->current_landmark != ""){
			$disp_icon="landmark.png";
		}
		
		if($coord->cross_speed == 1){
			$class = 'deviceMain_red';
		}
		$ast_class="";
		$minutes = $coord->beforeTime;
		if($minutes < $this->session->userdata('network_timeout') && $coord->speed > 0 && $minutes != ""){
				$ast_class='running_asts';
		}else if($minutes < $this->session->userdata('network_timeout')  && $coord->speed == 0 && $minutes != ""){
				$ast_class='parked_asts';
		}else if($minutes >= $this->session->userdata('network_timeout') && $minutes <= ($this->session->userdata('network_timeout')+36000) && $minutes != ""){
				$ast_class='out_of_network_asts';
		}else if($minutes > ($this->session->userdata('network_timeout')+36000) or $minutes ==""){
				$ast_class='device_fault_asts';
		}
	?>
		<script type="text/javascript">
		$(document).ready(function(){
			$('#main input[value]').click(function(e) {
				e.stopPropagation();
			});
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
		});
		assetNameArray[<?php echo $coord->assets_id; ?>] = '<?php echo $coord->assets_name; ?>';
		assetDeviceArray[<?php echo $coord->assets_id; ?>] = '<?php echo $coord->device_id; ?>';
		</script>
		<?php if($i == 0){ ?>
		<!-- <div class="sixteen columns row" > -->
		<?php } ?>
			<div class="one-third column box pullbelow" style="border:1px solid <?php if($coord->cross_speed == 1){	echo "red";	}else{echo "#C5DBEC";} ?>;display:inline-block;white-space:nowrap;float:none;margin-top:3px;cursor:pointer;" onClick="directTab(<?php echo $coord->device_id; ?>, <?php echo $coord->assets_id; ?>)">
			
			<div class="clearfix">	
				<span class="chk_loud" style="background-color: <?php echo $clr; ?>; border: 10px solid <?php echo $clr; ?>; border-radius: 5px 5px 5px 5px; clear: right; float: left; height: 20px; width: 20px;"><input onclick="selectedAssets()" name="assets_check[]" value="<?php echo $coord->assets_id; ?>" type="checkbox" class="<?php echo $ast_class; ?>" /></span>
				<span style="float: left; padding-top: 10px; padding-left: 5px;">
				<?php if(($clr=="green" && $coord->speed!=0) || $disp_icon != ""){ ?>
					<img src="<?php echo base_url(); ?>assets/images/<?php echo $disp_icon; ?>" title="Speed Change" />
				<?php } ?>
				</span>
				<span class="siren"></span>				
			</div>	
			
			<div class="clearfix" style="min-width:283px">	
					<div class="driver_pic">
						<?php if($coord->driver_image == ""){ ?>
						<img src="<?php echo base_url(); ?>assets/driver-photo/not_available.jpg" alt="Not Available" />
					<?php }else{ ?>
						<img src="<?php echo base_url(); ?>assets/driver-photo/<?php echo $coord->driver_image; ?>" alt="<?php echo $coord->driver_name; ?>" />
					<?php } ?>
					</div>
					<div class="speed">
					<?php $speedChange=""; if($coord->speed >= $coord->old_speed){ $speedChange="SpeedUp.png"; }else{ $speedChange="SpeedDown.png";}?>
						<?php if($clr=="green" && $coord->speed!=0){ ?>
						<img src="<?php echo base_url(); ?>assets/images/<?php echo $speedChange; ?>" title="Speed Change" style="width: 12px; height: 18px; position: absolute; margin-left: 53px; margin-top: -15px;"/>
						<?php }else { ?>
						<span style="width: 12px; height: 18px; position: absolute; margin-left: 53px; margin-top: -15px;"></span>
						<?php } ?>
						
						 <span class="figure"><?php if($coord->speed=='0') echo '00'; else if($coord->speed!="" || $coord->speed!=null) echo $coord->speed; else echo "00";?></span> <br/><span class="small">Km/H.</span>
					</div>
					<div class="vehical">
						<div class="numPlate"><a style="cursor:pointer;" onclick=""><?php echo $coord->assets_name; ?></a></div>
						<?php
							if($coord->assets_image_path != ''){
								if($coord->speed > 0){
									$assets_image = "assets_photo/".$coord->assets_image_path;
								}else{
									$assets_image_off = "assets_photo/gray_".$coord->assets_image_path;
								}
							}else{
								if($coord->speed > 0){
									$assets_image = "dashboard/images/truck.png";
								}else{
									$assets_image_off = "dashboard/images/truck_off.png";
								}
							}
						?>
						<?php if($coord->speed > 0){ ?>
						<img width="100px" height="66px" src="<?php echo base_url(); ?>assets/<?php echo $assets_image; ?>" alt="image" />
						<?php }else{ ?>
						<img width="100px" height="66px" src="<?php echo base_url(); ?>assets/<?php echo $assets_image_off; ?>" alt="image" />
						<?php } ?>
					</div>
				</div>	
			
				<div style="clear:both">
					<span class="data"><?php echo date($date_format, strtotime($coord->add_date)); ?>
					<?php echo date($time_format, strtotime($coord->add_date)); ?> (<?php echo $coord->received_time; ?>)</span>
				</div>
				<?php if($coord->address != ""){ ?>
				<div style="color:red;height:10px;">
					<?php echo $coord->address; ?>
				</div>
				<?php }else
				{?>
					<div style="color:red;height:10px;">
                    &nbsp;
					</div>
				
				<?php }?>
				<div></div>
					<div class="panel">									
						<a href="#"><span class="temp_login"></span></a>	
						<a href="#"><span class="map"></span></a>
						<a href="#"><span class="dashboard"></span></a>
						<a href="#"><span class="call"></span></a>
					</div>	
				<div class="blink"></div>	
			</div>
			
		<?php //if($i == 2){ ?>
		<!-- </div> -->
		<?php } ?>	
	<?php 
	//	$i++;
	//	if($i == 3) $i = 0;
	//	}
	}
	else
	{
		echo "<div style='padding-top:10%'>No Data Found</div>";
	}
	?>
	<?php 
		$reload = base_url() . "index.php/home/assets"; 
		if($page<=0)  $page  = 1;
	?>
	<span style="float:left"></span>
	<?php if($this->session->userdata('show_dash_distance_button')==1){ ?>
	<div id="distanceBtn_div" style="display:none">
	<!-- <img src="" height="10" width="10"/> -->
	
	<img src="<?php echo base_url(); ?>assets/upload_image/close.png" alt="close" style='background-color:white;height:12px;cursor:pointer;float:right;margin-top:-5px;border:1px solid lightblue;border-radius:3px;' onclick="closeDist()"/> 
	<a id="distanceBtn" style="cursor:pointer;" onClick="getAssetsDistance()" title="<?php echo $this->lang->line('view_distance'); ?>"><span class="float-distance"></span></a>
	</div>
	<?php }else { ?>
	<div id="distanceBtn_div" style="display:none"></div>
	<?php }?>
</div>
<script type="text/javascript">
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
	<?php echo paginate($reload, $page, $totalPage, 5, $totalRecords, $limit,$this); ?>
<?php
/*function ago($time)
{
   $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
   $lengths = array("60","60","24","7","4.35","12","10");

   $now = time();
	   $time = strtotime($time);
       $difference     = $now - $time;
       $tense         = "ago";

   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
       $difference /= $lengths[$j];
   }

   $difference = round($difference);

   if($difference != 1) {
       $periods[$j].= "s";
   }

   return "$difference $periods[$j]";
}
*/


function paginate($reload, $page, $tpages, $adjacents, $totalRecords, $limit,$language_formate) {
	
	/*$prevlabel = $language_formate->lang->line("Prev");
	$nextlabel = $language_formate->lang->line("next");
	//.$language_formate->lang->line("First").*/
	$prevlabel = $language_formate->lang->line("prev");
	$nextlabel = $language_formate->lang->line("next");
	$firstlabel = $language_formate->lang->line("First");
 	$out = '<div class="sixteen columns centre" id="bottomBigPaging">';
 	$out .= '<span style="font-size: 12px;font-weight: bold;display:block;margin-top:7px;margin-bottom:7px;">'.$language_formate->lang->line("Last Data Recieved").' : '.date($language_formate->session->userdata('date_format')." ".$language_formate->session->userdata('time_format')).'</span>';

	if($tpages>1 && $page!=1)
	{
		$out.= "<span><a class='ui-state-default' style='cursor:pointer;' onclick='changePage(1)'>".$language_formate->lang->line("First")."</a></span>\n";
	}
	else
	{
		$out.= "<span><a class='ui-state-default paginDisabled' style='cursor:pointer;'>".$language_formate->lang->line("First")."</a></span>\n";
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
		$out.= "<a class='ui-state-default' class='pagelink' onclick='changePage(1)'>1</a>\n";
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
	
	//last
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
		$out.= "<span onclick=changePage(" . ($tpages) . ")><a style='cursor:pointer;' class='ui-state-default'>".$language_formate->lang->line("Last")."</a></span>  | \n";
	}
	else
	{
		$out.= "<span><a style='cursor:pointer;' class='ui-state-default paginDisabled'>".$language_formate->lang->line("Last")."</a></span>  | \n";
	}
	
	$out.= '<span>'.$language_formate->lang->line("Total Assets").' : <strong> '.$totalRecords.' </strong> | '.$language_formate->lang->line("Number of Assets per page").' :';
	
	$out.= "<select onchange='changeLimit(this.value)' id='numAsset' style='margin:0'>";
	$out .= "<option";
	if($limit == 8)
		$out.= " selected='selected'";
	$out.= ">8</option><option";
	if($limit == 10)
		$out.= " selected='selected'";
	$out.= ">10</option><option";
	if($limit == 12)
		$out.= " selected='selected'";
	$out.= ">12</option><option";
	if($limit == 15)
		$out.= " selected='selected'";
	$out.= ">15</option><option";
	if($limit == 18)
		$out.= " selected='selected'";
	$out.= ">18</option><option";
	if($limit == 36)
		$out.= " selected='selected'";
	$out.= ">36</option><option";
	if($limit == 50)
		$out.= " selected='selected'";
	$out.= ">50</option><option";
	if($limit == 100)
		$out.= " selected='selected'";
	$out.= ">100</option><option value='all'";
	if($limit == 'all')
		$out.= " selected='selected'";
	$out.= ">All</option>";
	$out.= "</select></span>";
	
	return $out;
}
/*
<style>
.pagin ul li.inactive,
.pagin ul li.inactive:hover{
	background-color:#ededed;
	color:#bababa;
	border:1px solid #bababa;
	cursor: default;
}
.pagin ul li{
	list-style: none;
	font-family: verdana;
	margin: 5px 0 5px 0;
	color: #000;
	font-size: 13px;
}

.pagin{
	width: 800px;
	float:left;
	padding-left:10px;
}
.pagin ul li{
	list-style: none;
	float: left;
	border: 1px solid #006699;
	padding: 2px 6px 2px 6px;
	margin: 0 3px 0 3px;
	font-family: arial;
	font-size: 14px;
	color: #006699;
	font-weight: bold;
	background-color: #f2f2f2;
}
.pagin ul li:hover{
	color: #fff;
	background-color: #006699;
	cursor: pointer;
}
</style>
function paginate111($reload, $page, $tpages, $adjacents, $totalRecords, $limit) {
	
	$prevlabel = "Prev";
	$nextlabel = "Next";
	
	$out = "<div class=\"pagin\"><ul>\n";
	
	// previous
	if($page==1) {
		$out.= "<li class='inactive'>" . $prevlabel . "</span>\n";
	}
	else {
		$out.= "<li class='active' onclick='changePage(" . ($page-1) . ")'>" . $prevlabel . "\n";
	}
	$out.= "</li>";
	// first
	if($page>($adjacents+1)) {
		$out.= "<li class='active' onclick='changePage(1)'>1</li>\n";
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
			$out.= "<li class='active' style='color:#fff;background-color:#006699;' class=\"current\">" . $i . "</li>\n";
		}
		else {
			$out.= "<li class='active' onclick=changePage($i)>" . $i . "</li>\n";
		}
	}
	
	// interval
	if($page<($tpages-$adjacents-1)) {
		$out.= "...\n";
	}
	
	// last
	if($page<($tpages-$adjacents)) {
		$out.= "<li class='active' onclick=changePage(" . $tpages . ")>" . $tpages . "</li>\n";
	}
	
	
	// next
	if($page<$tpages) {
		$out.= "<li class='active' onclick=changePage(" . ($page+1) . ")>" . $nextlabel . "\n";
	}
	else {
		$out.= "<li class='inactive'>".$nextlabel . "\n";
	}
	$out .= "</li>";
	$out.= "<span style='color: #006699;font-family: arial;font-size: 14px;font-weight: bold;'>&nbsp;&nbsp;&nbsp;&nbsp;Total Assets : $totalRecords &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Number of Assets per page:";
	
	$out.= "<select onchange='changeLimit(this.value)' id='numAsset' style='float:right;'>";
	$out .= "<option";
	if($limit == 6)
		$out.= " selected='selected'";
	$out.= ">6</option><option";
	if($limit == 9)
		$out.= " selected='selected'";
	$out.= ">9</option><option";
	if($limit == 12)
		$out.= " selected='selected'";
	$out.= ">12</option><option";
	if($limit == 15)
		$out.= " selected='selected'";
	$out.= ">15</option><option";
	if($limit == 18)
		$out.= " selected='selected'";
	$out.= ">18</option></select></span></div>";
	
	return $out;
}
*/
?>
