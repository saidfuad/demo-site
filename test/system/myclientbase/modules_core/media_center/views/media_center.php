<?php 
	$site_url = base_url()."assets_gatti/";
	$data['site_url']=$site_url;
	$data['page']="media_center";
	$this->load->view("sessions/header",$data);
?>
<style>
.txt-center{
	text-align:center;
}
.headers{
padding-top: 21px;font-size: 34px;padding-bottom: 21px;text-align:center;
}
.blues { background:#00AFEF !important; }
.blues:hover { background:#109bce!important; }

.greens{ background:rgb(166,207,84) !important; }
.greens:hover{ background:rgb(102,173,0) !important; }
.yellows{ background:rgb(233,218,66) !important; }
.yellows:hover{ background:rgb(255,189,18) !important; }
.reds{	background:rgb(252,118,103) !important; }
.reds:hover{	background:rgb(250,79,67) !important; }
.btn.primary.small{
	width: 111px  !important;
	padding: 5px  !important;
	margin-top: 5px  !important;
	margin-bottom: 5px  !important;
	box-shadow:white 0px 0px 0px 0px  !important;
	-webkit-box-shadow:white 0px 0px 0px 0px  !important;
	-moz-box-shadow:white 0px 0px 0px 0px  !important;
	border:0px !important;
	text-shadow:0px !important;
	color:black !important;
	font-size:20px !important;
}
</style>
	<!-- THE PAGETITLE CONTENT PART :) -->
	<div class="bgcolor">
		<div class="container withverticalpadding content">
			<h1 class="pagetitle">Media Center</h1>
			<p class="intro"></p>
		</div> <!-- END OF GRAYVERTICALPADDING CONTENT-->
	</div> <!-- END OF PAGETITLE CONTENT PART -->

	<!-- A COLORED BG AROUND THE WHLE CONTENT PART -->
	<div class="bgcolor white">
	<!-- THE TOP IMAGE -->
	<!-- THE CONTENT PART STARTS HERE :) -->
	<div class="container withverticalpadding content">
		<h1 class="strong txt-left">Presentation</h1>
		<div class="row"> 
			<div class='one_fifth txt-center'>
				&nbsp;
			</div>
<div class='one_fifth txt-center'>
				&nbsp;
			</div>
			<div class='one_fifth txt-center'>
				<div class='headers' style='background-color:#00AFEF '>English</div>
				<div style='padding: 26px;background-color:#E7E8E9;text-align:center'>
				<a href='http://goo.gl/pJcjmp' target='_blank' class="btn primary blues small"  >View</a><br>
				<a href='http://goo.gl/pKAOrC' class="btn primary blues small" target='_blank' >Download</a><br>
				<a href='http://goo.gl/Dr9VzY' class="btn primary blues eens small" target='_blank'>Pdf</a><br>
				<a href='http://goo.gl/FJ1dXc' class="btn primary blues small" target='_blank'>Video</a>
				</div>
				
			</div>
			<?php /* <div class='one_fifth txt-center'>
				<div class='headers' style='background-color:rgb(233,218,66)'>Hindi</div>
				<div style='padding: 26px;background-color:#E7E8E9;text-align:center'>
				<a href='http://goo.gl/ylucc8' target='_blank' class="btn primary yellows small" >View</a><br>
				<a href='http://goo.gl/jUR8NG' class="btn primary yellows small"  target='_blank'>Download</a><br>
				<a href='http://goo.gl/6ajuCy' class="btn primary yellows small"  target='_blank'>Pdf</a><br>
				<a href='http://goo.gl/wn63B2' class="btn primary yellows small" target='_blank'>Video</a>
				</div>
			</div>
			<div class='one_fifth'>
				<div class='headers' style='background-color:rgb(252,118,103)'>Gujarati</div>
				<div style='padding: 26px;background-color:#E7E8E9;text-align:center'>
				<a href='http://goo.gl/FhBkQS' target='_blank' class="btn primary reds small" >View</a><br>
				<a href='http://goo.gl/Kh8CfI' class="btn primary reds small" target='_blank' >Download</a><br>
				<a href='http://goo.gl/JXuBEf' class="btn primary reds small" target='_blank' >Pdf</a><br>
				<a href='http://goo.gl/yHteTt' class="btn primary reds small" target='_blank' >Video</a>
				</div>
			</div> */ ?>
<div class='one_fifth txt-center'>
				&nbsp;
			</div>
			<div class='one_fifth lastcolumn'>
				&nbsp;
			</div>
		</div>
		<h1 class="strong txt-left">Videos</h1>
		<div class="row"> 
		<div class='one_half txt-center'>
<iframe width="444" height="250" src="//www.youtube.com/embed/5feTe4fKQCQ?rel=0" frameborder="0" allowfullscreen></iframe>
		</div>
		<div class='one_half txt-center lastcolumn'>
<iframe width="333" height="250" src="//www.youtube.com/embed/cH_n19k8M7Y?rel=0" frameborder="0" allowfullscreen></iframe>
		</div>
		</div>

		<h1 class="strong txt-left">Photos</h1>
		<div class="row"> 
			<img src="<?php echo $site_url; ?>gatti/vehicle tracking system main1.png" alt="vehicle tracking system main1">
		</div>
		<div class="divide45"></div>
	</div> <!-- END OF THE CONTENTCONTAINERS, NOW FOOTER AND SUBFOOTER IS COMING -->

</div> <!-- END OF COLORED BG FOR CONTENT -->

<?php $this->load->view("sessions/footer",$data); ?>