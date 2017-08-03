<?php 
	$site_url = base_url()."assets_gatti/";
	$data['site_url']=$site_url;
	$data['page']="gatti_feature";
	$this->load->view("sessions/header",$data);
?>

<!-- THE PAGETITLE CONTENT PART :) -->
<div class="bgcolor">
<div class="container withverticalpadding content">
<h1 class="pagetitle">Features And Benefits</h1>
<p class="intro"></p>
</div> <!-- END OF GRAYVERTICALPADDING CONTENT-->
</div> <!-- END OF PAGETITLE CONTENT PART -->


<!-- A COLORED BG AROUND THE WHLE CONTENT PART -->
<div class="bgcolor light">
	<!-- THE CONTENT PART STARTS HERE :) -->
	<div class="container withverticalpadding content">
	<div class="row"> 
		<h2>Features</h2>
	</div>
	<!-- SOCIAL BAR IS INVOLVED -->
	<?php
		$img = array(
			"vehicle-tracking-system-Vehicle Recovery.png",
			"vehicle-tracking-system-RFID.png",
			"vehicle-tracking-system-Alerts.png",
			"vehicle-tracking-system-Stoppages.png",
			
			"vehicle-tracking-system-Over speed.png",
			"vehicle-tracking-system-Identify un-utilized.png",
			"vehicle-tracking-system-Temperature sensor.png",
			"vehicle-tracking-system-Fuel saving.png",
			
			"vehicle-tracking-system-Geo Fencing.png",
			"vehicle-tracking-system-Locate the nearest vehicle.png",
			"vehicle-tracking-system-Multi language.png",
			"vehicle-tracking-system-Mileage by GPS.png",
			
			"vehicle-tracking-system-Tamper protection.png",
			"vehicle-tracking-system-Control room.png",
			"vehicle-tracking-system-Mobile_Tablet Compatible.png",
			
			
		);
		$title = array(
			"Vehicle Recovery",
			"RFID",
			"Alerts",
			"Stoppages",
			
			"Over speed",
			"Identify un-utilized",
			"Temperature sensor",
			"Fuel saving",
			
			"Geo Fencing",
			"Locate the nearest vehicle",
			"Multi language",
			"Mileage by GPS",
			
			"Tamper protection",
			"Control room",
			"Mobile & Tablet Compatible",
		);
		$content = array(
			"In case of vehicle get stolen or hijack its help to get back vehicle by vehicle tracking system.",
			"RFID in Gatti is used for schedule attendance facility to BPO employees or school students etc.",
			"On over-speeding or undue stoppages of vehicale, expected time of arrival of the bus or truck, if driver try to move out vehicle from defined limited boundaries user get alerts by sms or email or by any social networking.",
			"Its show exact location where vehicle is stopped and how long it's take halt.",
			
			"Owner get alert on over speeding vehicle by driver.",
			"Its shows you the duration of un-utilized of vehicle",
			"Ideal for the company having transportation of perishable where temperature is most important factor. Its raise alert as soon as temperature goes out of desirable temperature.",
			"With help of Gatti owner can find out the shortest way to reach destination and even can track the mileage of vehicle which help to save fuel.",
			
			"Create virtual fence on map, along with list of vehicle, area designated with some name like \"black-listed motel\", alerts will be activated, if the vehicle enters in the area, or leave the area. Multiple alerts options are available like SMS, Email and chat. ",
			"Locate the nearest vehicle: Owner can locate the nearest vehicle with help of landmark.",
			"Web application support multi language like English, Hindi and Gujarati.",
			"Algorithm to calculate the vehicle's mileage based on GPS.",
			
			"GPS Device is Temper protected. So no season affect this device.",
			"Control room support multi screen.",
			"Gui is compatible with any type of mobile or tablet.",
		);
			for($i=0;$i<count($img);$i=$i+4){
			$next = $i+4;
			echo '<div class="row"><div class="divide20"></div>';
			for($j=$i;$j<$next;$j++){
				$res = "";
				if($j==($next-1)){
					$res =" lastcolumn";
				}
				echo '<div class="one_fourth '.$res.'">';
				if(isset($img[$j])){
					echo '<div class="txt-center" style="padding: 21px;"><img alt="'.str_replace(".png", "", $img[$j]).'" src="'.$site_url.'gatti/'.$img[$j].'"/></div>';
					echo '<h5 class="centered" style="min-height: 51px;">'.$title[$j].'</h5>';
					echo '<h5  style="font-size:14px;text-align:justify">'.$content[$j].'</h5>';
				}
				echo "</div>";
			}
			echo '</div>';
		}
		
	?>

</div>
</div>


<div class="bgcolor white">
<!-- THE CONTENT PART STARTS HERE :) -->
<div class="container withverticalpadding content">
<!-- SOCIAL BAR IS INVOLVED -->
<div class="row">
<h2 class="strong">Functionalities And Benefits</h2>
<div class="fun_ol dark">
<ol>
	<li> <img src="<?php echo $site_url; ?>gatti/arrow.png" style='width: 14px;' alt="gatti_arrow">  Mileage</li>
	<li><img src="<?php echo $site_url; ?>gatti/arrow.png" style='width: 14px;' alt="gatti_arrow">  Stoppages</li>
	<li><img src="<?php echo $site_url; ?>gatti/arrow.png" style='width: 14px;' alt="gatti_arrow">  Geo-fencing</li>
	<li><img src="<?php echo $site_url; ?>gatti/arrow.png" style='width: 14px;' alt="gatti_arrow">  Asset tracking</li>
	<li><img src="<?php echo $site_url; ?>gatti/arrow.png" style='width: 14px;' alt="gatti_arrow">  Vehicle recovery</li>
	<li><img src="<?php echo $site_url; ?>gatti/arrow.png" style='width: 14px;' alt="gatti_arrow">  Fleet management</li>
	<li><img src="<?php echo $site_url; ?>gatti/arrow.png" style='width: 14px;' alt="gatti_arrow">  Battery backup</li>
	<li><img src="<?php echo $site_url; ?>gatti/arrow.png" style='width: 14px;' alt="gatti_arrow">  Internal logging</li>
	<li><img src="<?php echo $site_url; ?>gatti/arrow.png" style='width: 14px;' alt="gatti_arrow">  Route deviation</li>
	<li><img src="<?php echo $site_url; ?>gatti/arrow.png" style='width: 14px;' alt="gatti_arrow">  Mileage by GPS</li>
	<li><img src="<?php echo $site_url; ?>gatti/arrow.png" style='width: 14px;' alt="gatti_arrow">  Engine running status</li>
	<li><img src="<?php echo $site_url; ?>gatti/arrow.png" style='width: 14px;' alt="gatti_arrow">  Google Earth (3d map integration )</li>
	
</ol>
</div>
<div class="divide45"></div>
<!-- SKILLS -->
</div>
</div>
</div>



<?php $this->load->view("sessions/footer",$data); ?>