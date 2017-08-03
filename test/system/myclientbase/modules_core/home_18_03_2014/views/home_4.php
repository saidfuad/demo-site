<?php
	$uid = $this->session->userdata('usertype_id');
	$ans_cp=$ans_hist = "";
	
	if($uid==1){
		$data = "Allow";
		$ans_hist = '1';
	}
	else
	{
		$data = "Not Allow";
		$va1l = $this->db;
		
		$va1l->where("user_id",$this->session->userdata('user_id'));
		$va1l ->where("del_date",NULL);
		$res_val = $va1l->get("tbl_users");
		
		foreach($res_val ->result_Array() as $row)
		{
			$create_user_datea=0;
			$ans_cp=0;
			$ans_hist=0;
			if(isset($row['change_password'])){
				$ans_cp = $row['change_password'];
			}
			if(isset($row['history'])){
				$ans_hist = $row['history'];
			}
			if(isset($row['allow_user_profile'])){
				$create_user_datea = $row['allow_user_profile'];
			}
		}
		if($ans_cp=='1')
			$data = "Allow";
		else
			$data = "Not Allow";
	}
	
	

?>
<?php
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format'); 
	 $js_time_format = $this->session->userdata('js_time_format');
	 $site_ref = $this->session->userdata('site_referer');


	$filename = base_url().'assets/all.css';
	$file_headers = @get_headers($filename);
	$obj_xhr;
	/*
	if(strtoupper($file_headers[0]) == strtoupper('HTTP/1.1 404 Not Found')) {
?>
<link rel="stylesheet" type="text/css"  href="<?php echo base_url(); ?>assets/all_css_final.php">
<?php } ?>
<?php $filename = base_url().'assets/all.js';
	$file_headers = @get_headers($filename);
	if(strtoupper($file_headers[0]) == strtoupper('HTTP/1.1 404 Not Found')) {
?>
	<link rel="stylesheet" type="text/css"  href="<?php echo base_url(); ?>assets/all_js_final.php">
<?php } */ ?>
<!DOCTYPE html>
	<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
	<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
	<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
	<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]--> 
<head>
<!-- Basic Page Needs  ================================================== -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<?php if('test.trackeron.com' == $_SERVER['HTTP_HOST'] || 'vts.trackeron.com' == $_SERVER['HTTP_HOST']){ ?>
<title><?php echo $this->lang->line('trackon'); ?></title>
<?php } else if('vehicle.worldwidetrackingservices.com' == $_SERVER['HTTP_HOST']){ ?>
<title><?php echo $this->lang->line('wts'); ?></title>
<?php } else { ?>
<title><?php echo $this->lang->line('myclientbase'); ?></title>
<?php } ?>
<meta name="description" content="">
<meta http-equiv="Cache-control" content="description">
<meta name="author" content="">
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=3">
<!-- Mobile Specific Metas  ================================================== -->
<meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=3">
<!-- CSS  ================================================== -->
    <!--[if lte IE 6]>
            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/style/css/jquery.tabs-ie.css" type="text/css" media="projection, screen">
            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/style/css/basic_ie.css" type="text/css" media="projection, screen">
        <![endif]-->
	<!--[if lte IE 7]>
			<link rel="stylesheet" href="<?php echo base_url(); ?>assets/style/css/jquery.tabs-ie.css" type="text/css" media="projection, screen">
	<![endif]-->

<!--
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dashboard/stylesheets/base.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dashboard/stylesheets/skeleton.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dashboard/stylesheets/home.css"> -->
<!--link href="<?php echo base_url(); ?>assets/style/css/jquery-ui-timepicker.css" rel="stylesheet" type="text/css" /-->
<link type="text/css" rel="stylesheet" media="all" href="<?php echo base_url(); ?>assets/chat/css/chat.css" />
<link href="<?php echo base_url(); ?>assets/jquery/jquery-ui-timepicker-addon.css" rel="stylesheet" type="text/css"/>
<!-- <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&v=3&libraries=geometry"></script> -->

<link rel="stylesheet" type="text/css"  href="<?php echo base_url(); ?>assets/all.css">
<link type="text/css" href="<?php echo base_url(); ?>assets/jquery/ui-themes/redmond/jquery-ui-1.8.5.custom.css" rel="stylesheet" />
<link rel='stylesheet' type='text/css' href='<?php echo base_url(); ?>assets/jqgrid/css/ui.jqgrid_min.css' />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/jqwidgets/styles/jqx.base.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/jqwidgets/styles/jqx.ui-redmond.css" />
<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/dashboard/images/nk.png">

<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<!--[if IE]><script type="text/javascript" src="http://nkonnect.com/trackassets/jqplot/excanvas.js"></script><![endif]-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/all.js"></script>
<!--script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery-ui-timepicker-addon.js"></script-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/jquery-ui-timepicker-addon.js"></script>
<script type='text/javascript' src='<?php echo base_url(); ?>assets/jqgrid/js/i18n/grid.locale-en_min.js'></script>
<script type='text/javascript' src='<?php echo base_url(); ?>assets/jqgrid/js/jquery.jqGrid.src_min.js'></script>
<script type='text/javascript' src='<?php echo base_url(); ?>assets/javascript/jquery.progressbar.min.js'></script>
<?php /*
<!-- Atul --->
<script type="text/javascript" src="http://www.google.com/jsapi?hl=en&key=<?php echo "AIzaSyD8xKuVpOZb-0kNhTRFGcHChk1dpduNO2Y";?>"></script>
 <!-- end - Atul --->
 */	?>
<?php if (isset($header_insert)) { $this->load->view($header_insert); } ?>
<?php echo $headerjs;	// Loading the Google Map javascript api file  ?>

<style>
#ui_tpicker_hour_label_from_date,#ui_tpicker_hour_label_to_date
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}

#ui_tpicker_minute_label_from_date,#ui_tpicker_minute_label_to_date
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}

#ui_tpicker_second_label_from_date,#ui_tpicker_second_label_to_date
{
padding: 0px !important;
margin-top: 4px !important;
text-align: left !important;
line-height:0px !important;
}
#tabs{
	padding:5px !important;
}
dt
{
	width:auto !important
}
.ui-tabs .ui-tabs-hide {
    position: absolute !important;
    left: -10000px !important;
    display:block !important;
}
.gm-style-iw div{
	overflow:hidden !important;
}

.ui-accordion .ui-accordion-content {
	padding: 0em !important;
	/*padding-left: 1em !important;*/
	overflow-x: hidden;
}
div.minimap {
	width:200px; height:200px;
	border: solid 1px gray; 
	border-width: 1px 2p 3px 4px;
	padding: 3px;
	font-size: 1.0em;
	overflow:auto; 
  }
</style>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascript/jQueryRotate.2.2.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascript/richmarker.js"></script>
<script type="text/javascript">
var auto_refresh_setting = <?php echo $auto_refresh_setting; ?>;
var base_url = '<?php echo base_url(); ?>';
$xhr = null;
var assets_count=<?php if($usr_assets_cmb_count!="" || $usr_assets_cmb_count!=0){ echo $usr_assets_cmb_count; }else{ ?>0<?php } ?>;
<?php if($usr_assets_cmb_count>1){ ?>
var assets_combo_opt="<option value=''>Please Select</option><?php echo $usr_assets_cmb; ?>";
<?php }else{ ?>
var assets_combo_opt="<?php echo $usr_assets_cmb; ?>";
<?php } ?>
<?php if($usr_assets_cmb_count>1){ ?>
var assets_combo_opt_report="<option value=''>Select All</option><?php echo $usr_assets_cmb; ?>";
<?php }else{ ?>
var assets_combo_opt_report="<?php echo $usr_assets_cmb; ?>";
<?php } ?>
var loadCancel=1;
$req_obj_xhr = null;
var lastUrl="";
var all_URL= new Array();
var all_URL_id= new Array();
var lastUrlName="";
var limit = 40;
var page = 1;
var url = "<?php echo base_url(); ?>index.php/home/assets_list";
var url_main = "<?php echo base_url(); ?>";
var file;
var menu;
var tId;
var tabArrKey = new Array();
var tabArrValue = new Array();
var selected_assets_ids="";
var assets_id;
var gsr=Array();
var assetNameArray = new Array();
var user_DisplayArray = new Array();
var assetDeviceArray = new Array();
var tab_id;
var myLayout;
var grid_paging=100;
var loadedGrid;
var home_template_obj;
var stop_report_lat;
var stop_report_lang;
var stop_report_html;
var plot1;
var plot2;
var mapDivHidden="<div align='center' id='dispMap' style='display:block;text-align:center;width:100%;height:130px;'></div>";

var fromVehicle = [];
var toVehicle = [];
var fromVehicleId = [];
var toVehicleId = [];
var distanceVehicle = [];
var vDStart = [];
var vDEnd = [];
var vDStartLatLong = [];
var vDEndLatLong = [];
var sel_users = '';
var sel_groups= '';
var sel_areas = '';
var sel_landmarks = '';
var sel_owners = '';
var sel_divisions = '';
var vt_menu = '<div id="accordion"><h3><a href="<?php echo base_url(); ?>index.php/home/userList">Users</a></h3> <div> <p> Loading... Please wait.</p></div> <h3><a href="<?php echo base_url(); ?>index.php/home/groupList">Groups</a></h3> <div> <p> Loading... Please wait.</p></div> <h3><a href="<?php echo base_url(); ?>index.php/home/areaList">Areas</a></h3> <div> <p> Loading... Please wait.</p></div> <h3><a href="<?php echo base_url(); ?>index.php/home/landmarkList">Landmarks</a></h3> <div> <p> Loading... Please wait.</p></div> <h3><a href="<?php echo base_url(); ?>index.php/home/ownerList">Owners</a></h3> <div> <p> Loading... Please wait.</p></div> <h3><a href="<?php echo base_url(); ?>index.php/home/divisionList">Divisions</a></h3> <div> <p> Loading... Please wait.</p></div> <h3><a href="<?php echo base_url(); ?>index.php/home/assList">Assets</a></h3><div> <p> Loading... Please wait.</p> </div> </div>';

dashboardMarkers = [];
dLabelArr = [];
var opts_dmap = null;

$(document).ready(function () {
	
	$("marquee").hover(function () { 
		this.stop();
	}, function () {
		this.start();
	});

//	$(".ui-layout-west").html(vt_menu);
/*	
	$( "#accordion" ).accordion({
		collapsible: true, 
		active : false,
		autoHeight: false,
		change: function(event, ui){
			$url = $(ui.newHeader[0]).children('a').attr('href');
			$(ui.newHeader[0]).next().html('<p> Loading... Please wait.</p>');
			if($url != undefined){
				$.get($url, function (data) {
					$(ui.newHeader[0]).next().html(data);
				});
			}
		}
	});
*/	
	 opts_dmap = {
	   disableDefaultUI: false,
	   scrollwheel: false,
	   mapTypeControl: true,
	   mapTypeControlOptions: {
		style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
		},
	   navigationControl: true,
	   navigationControlOptions: {
		style: google.maps.NavigationControlStyle.SMALL
		}
	 };// end opts

	
	$.post("<?php echo base_url(); ?>index.php/home/extra_js",
			 function(data) {	
				$("body").append(data);
				loadInfo_Rotate();
				loadAssets();
				
				dashboardMap = document.getElementById("map_div");
					if (dashboardMap != 'undefined' && dashboardMap != null) {
				
					mapOptionsmap = {
						zoom: 2,
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
					google.maps.event.addDomListener(window, "resize", function() {
						resizeDMap();
					});
				}
				
				setTimeout(function(){
					// loadInfo_Rotate();
					stop_resume_toggle();
				},1000);				
			});
	
	$( "#pbar" ).progressbar({value: 0});
	$(".imageHolder").html("<img src='<?php echo base_url(); ?>assets/upload_image/Images_upload/<?php echo $this->session->userdata('photo'); ?>' class='user_img_set' alt='image'></img>");
	$("#img_upload_setid").attr("src","<?php echo base_url(); ?>assets/upload_image/Images_upload/<?php echo $this->session->userdata('photo'); ?>");
	$("#chage_profile_photo").attr("src","<?php echo base_url(); ?>assets/upload_image/Images_upload/<?php echo $this->session->userdata('photo'); ?>");
	$("#user_img_form a").html("<img src='<?php echo base_url(); ?>assets/upload_image/Images_upload/<?php echo $this->session->userdata('photo'); ?>' class='user_img_set' alt='image' width='148'></img>");
	if($.browser.msie) {
		$("#header_msg").show();
	}
	//$('#switcher').themeswitcher();
	$('#menu1').buttonset();
	$('#menu1').ptMenu();
	//setTimeout("$('.box').css('background-color', $('.ui-widget-header:first').css('background-color'))", 2000);
	//$('.box').css('background-color', '#');			
	//$('.box').css('-moz-border-radius', '2px');
	//$('body').layout({ applyDefaultStyles: true });
	$('body').layout({
	resizerClass: 'ui-state-default',
	applyDefaultStyles: true,
	west__onresize: function (pane, $Pane) {
		  jQuery("#west-grid").jqGrid('setGridWidth',$Pane.innerWidth()-2);
      },
	center__onresize: function (pane, $Pane) {
		if($.jqplot != undefined)
		{
			if(typeof plot1==='object' && plot1 != null)
			{
			$('#tabs ul.ui-tabs-nav li.ui-tabs-selected a').each(function(i) {
				if (this.text == "Speed Graph") {
					if(plot1!=undefined)
					setTimeout(function(){
					plot1.replot( { resetAxes: false } );},100);
				}
			});
			}
			if(typeof plot2==='object' && plot2 != null)
			{
			$('#tabs ul.ui-tabs-nav li.ui-tabs-selected a').each(function(i) {
				if (this.text == "Distance Graph") {
					if(plot2!=undefined)
					setTimeout(function(){plot2.replot( { resetAxes: false } );},100);
				}
			});
			}
		}
		jQuery("#" + loadedGrid+"_list").jqGrid('setGridWidth',jQuery("#RightPane").width()-50);
		resizeDMap();
      }
    });
	myLayout = $('body').layout({
		onclose_end: function () {
			var obj = document.getElementById('imgmaxmin');
			var is_close_west  = myLayout.state.west.isClosed;
			var is_close_south = myLayout.state.south.isClosed;
			var is_close_north = myLayout.state.north.isClosed;
			if(is_close_west==true && is_close_north==true && is_close_south==true){
				obj.alt="min";
				obj.title = "<?php echo $this->lang->line("Minimize"); ?>";
				obj.src="<?php echo base_url(); ?>assets/style/img/icons/window_no_full_screen.png";
			}
		},
		onopen_end: function () {
			var obj = document.getElementById('imgmaxmin');
			var is_close_west  = myLayout.state.west.isClosed;
			var is_close_south = myLayout.state.south.isClosed;
			var is_close_north = myLayout.state.north.isClosed;
			if(is_close_west==false || is_close_north==false || is_close_south==false){
				obj.alt="max";
				obj.title = "<?php echo $this->lang->line("Maximize"); ?>";
				obj.src="<?php echo base_url(); ?>assets/style/img/icons/window_full_screen.png";
			}
		}
	});
	

	$("#menu1").click(function(){
		setTimeout("myLayout.resizeAll();", 500);
	})
	$('#tabs').tabs({
		closable: true, 
		cache:true,
		height:'auto',
		remove: function(e, ui){
			
			$("#loading_top").css("display","none");
			var p_test=true;
			var p_test2=true;
			$('#tabs ul.ui-tabs-nav li.ui-state-default').each(function(i) {
				
				if ($(this).children('a:first-child').children('span').html() == "Speed Graph") {
					p_test=false;
				}
				if ($(this).children('a:first-child').children('span').html() == "Distance Graph") {
					p_test2=false;
				}
				if ($(this).text() != "Distance") {
					$(".ui-dialog-content").dialog("close");
				}
				
			});
			if(p_test!=false){
				plot1=null;
			}
			if(p_test2!=false){
				plot2=null;
			}
		},
		select: function (e,ui) {
			if(ui.panel.id=="tabs-1")
			{
				/*if(selected_assets_ids.length>0)
				{
				$("#float-icons").show();
				myLayout.close( "west" );
				}*/
				//subMenu("home_sidebar");
				$("#opt_users").val(sel_users);
				$("#opt_groups").val(sel_groups);
				$("#opt_areas").val(sel_areas);
				$("#opt_landmarks").val(sel_landmarks);
				$("#opt_owners").val(sel_owners);
				$("#opt_divisions").val(sel_divisions);
			}
			/*else
			{
				$("#float-icons").hide();
			}
			*/
			$("#selected_tab").html(ui.panel.id);
			loadedGrid = ui.panel.id;
			if ($("a[href='#"+ui.panel.id+"'] span").html() == "Speed Graph"){
				if((plot1 != undefined || plot1 != null) && typeof plot1==='object')
				{
					setTimeout(function(){plot1.replot( { resetAxes: false } );},100);
				}
			}
			if ($("a[href='#"+ui.panel.id+"'] span").html() == "Distance Graph"){
				if((plot2 != undefined || plot2 != null) && typeof plot2==='object')
				{
					setTimeout(function(){plot2.replot( { resetAxes: false } );},100);
				}
			}
		},
		add: function(e, ui){			
			loadJGrid();
			$('#tabs').tabs('select', '#'+ui.panel.id);
		},
		ajaxOptions: {
			error: function( xhr, status, index, anchor ) {
				$( anchor.hash ).html("Loading...");
			},
			success: function( xhr, status ) {
				$("ul.tabs a").css('height', $("ul.tabs").height());
				$("ul.tabs a").css('overflow', 'hidden');
			}
			
		}
	});
	
	function removeItems(array, item) {
		var i = 0;
		while (i < array.length) {
			if (array[i] == item) {
				array.splice(i, 1);
			} else {
				i++;
			}
		}
		return array;
	}
	$("#ui-datepicker-div").hide();
	jQuery("input:button, input:submit, input:reset").button();
	
	var availableOpt = [
			'<?php echo implode("','", $option); ?>'
		];
	$( "#srcTxt" ).autocomplete({
		source: availableOpt
	},{minLength: 0});
	$("#srcTxt" ).keyup(function(e){
		if(typeof  $obj_xhr==='object') {$obj_xhr.abort()};
		var key = e.charCode || e.keyCode || 0;
		if(key == 116 || key == 27 || (key >= 33 && key <= 40 && e.shiftKey === false) || (key >= 112 && key <= 123))
		{
			return;
		}
		else
		{
			searchAssets();
		}
		
	});
	//myLayout.toggle('south');
	//myLayout.toggle('north');
	
	$("#user_dialog").dialog({
		autoOpen: false,
		modal: true,
		height: 'auto',
		width:'70%',
		title:'<?php echo $this->lang->line('Create_Users'); ?>',
		draggable: true,
		resizable: true,
		open : function(){
			$('#frm_users')[0].reset();
			username = $("#username");
			add_to_user = $("#add_to_user");
			password = $("#password");
			from_date = $("#from_date");
			to_date = $("#to_date");
			first_name = $("#first_name");
			last_name = $("#last_name");			
			mobile_number = $("#mobile_number");
			email_address  = $("#email_address");
			sms_alert  = $("#sms_alert");
			email_alert  = $("#email_alert");
			AllFieldsUser = $([]).add(add_to_user).add(username).add(password).add(from_date).add(to_date).add(first_name).add(last_name).add(mobile_number).add(email_address).add(sms_alert).add(email_alert);
			tips = $("#user_error");
			$("#assets_ids").val('');
		}
	});
	$("#loading_dialog").dialog({
		autoOpen: false,
		modal: true,
		height: '45',
		width:'30%',
		resizable: false,
	});
	if($("#loading_dialog").prev().is(".ui-dialog-titlebar"))
	{
		$("#loading_dialog").prev().hide();
	}
	$("#right_click_disabled").dialog({
		autoOpen: false,
		modal: true,
		height: '45',
		width:'30%',
		resizable: false,
	});
	if($("#right_click_disabled").prev().is(".ui-dialog-titlebar"))
	{
		$("#right_click_disabled").prev().hide();
	}
//	//$("#loading_dialog").dialog("open");
	$("#loading_dialog_btn_line").click(function(){
		loadCancel=0;
		$("#loading_dialog").dialog("close");
	});
	
	$("#group_dialog").dialog({
		autoOpen: false,
		modal: true,
		height: 'auto',
		width:'70%',
		title:'Add To Group',
		draggable: true,
		resizable: true,
		open : function(){
			$("#new_group_tr").hide();
			$("#group_combo option:first").attr('selected','selected');
		}
	});
	$("#alert_dialog").dialog({
		autoOpen: false,
		modal: true,
		title:'<?php echo $this->lang->line("Alert_Box"); ?>',
		open : function(){
			setTimeout('$("#alert_dialog").dialog("close")',2000);
		}
	});
	$("#profile_dialog").dialog({
		autoOpen: false,
		modal: true,
		title:'<?php echo $this->lang->line("Alert_Box"); ?>',
		open : function(){
			setTimeout('$("#profile_dialog").dialog("close")',5000);
		}
	});
	
	$("#confirm_alert_dialog").dialog({
		autoOpen: false,
		modal: true,
		title:'Confirm Box',
	});
	
	$("#distance_box_dialog").dialog({
		modal: false,
		bgiframe: true,
        width: 400,
        height: 350,
		title : '<?php echo $this->lang->line("Distance Calculation");  ?>',
		autoOpen: false,
	  	draggable: true,
		resizable: true,
		open : function(){
			//$("#distance_box_dialog").html(mapDivHidden);
			loadVehicleDistanceData();
			
		}
	});
	
	//<img src='<?php echo base_url(); ?>assets/assets_photo/"+data.points[i].image+"' /> 
	//<img src='<?php echo base_url(); ?>assets/assets_photo/"+data.points[j].image+"' />
	//var html="<span><?php echo $this->lang->line("Distance Between"); ?> </span><img src='<?php echo base_url(); ?>assets/assets_photo/"+data.points[0].image+"' /> <span>"+data.points[0].truck+"</span><span>And</span> <img src='<?php echo base_url(); ?>assets/assets_photo/"+data.points[1].image+"' /> <span> "+data.points[1].truck+"</span> <span>is</span> '"+dist+"'";
	
	$("#distance_box_dialog").html(mapDivHidden);
	$('img[rel="external"]').click( function() {
					var index = $("#selected_tab").html();
					window.open('<?php echo site_url($this->uri->uri_string()); ?>','','width=800,height=600');
					return false;
				});
	$('img[rel="print"]').click( function() {
				var index = $("#selected_tab").html();
				if(index=="tabs-1")
					{
						$("#main").printt();
					}
				else
					{
						if($("#imgmaxmin").attr("alt")=='max')
							$("#imgmaxmin").trigger("click");
						setTimeout(function(){
						window.print();
						},2000);
					}
				});
	$("#testAddons").addClass("maxMenuFixed");
	$("#profile").click(function(event){
		event.stopPropagation();
		});
	$("#logout").click(function(event){
		event.stopPropagation();
		});
	$("html").click(function(event){
		$('#profile').slideUp();
		});
	$("#time_in_seconds").keypress(function(e){
		var key = e.charCode || e.keyCode || 0;
		if( key == 13)
		{
			$("#checkboxToggle").focus();
		}
	});
	$("#detailed_pan a").each(function(){
		var cnt="<span style='line-height:1px'>"+$(this).attr("rel")+"</span>";
		$(this).qtip({
		   content: cnt,
		   show: {
			delay: '2000',
		   },
		   hide: {
			delay: '2000',
		   },
		   position: {
			corner: {
				target: 'topMiddle',
				tooltip: 'bottomMiddle'
			  },
			  adjust: { screen: true } 
			},
			style: {
				padding: 2,
				border: {
                     width: 1,
                     radius: 3,
					 color: $(".ui-widget").css("color")
                },
				textAlign: 'center',
				tip: true,
			}
        });
	});
	$("#serverLoad").qtip({
		   content: $("#serverLoadCnt").html(),
		   position: {
			  adjust: { screen: true } 
			},
			style: {
				padding: 2,
				border: {
                     width: 1,
                     radius: 3,
					 color: $(".ui-widget").css("color")
                },
				textAlign: 'center',
				tip: true,
			}
        });
	$("#bandwidthTester").dialog({
		autoOpen: false,
		modal: false	,
		height: 'auto',
		width:'auto',
		title:'Bandwidth Test',
		draggable: false,
		resizable: false		
	});
	$(document).keypress(function(e) {
		if (e.keyCode == 27) { 
			$("#top_loading_esc_id").addClass('ui-state-highlight');
			setTimeout(function() {
				$("#top_loading_esc_id").removeClass('ui-state-highlight', 1500).after(function(){setTimeout(function(){
				$("#loading_top").css("display","none");
				},1550)});
			}, 500);
		}    
	});	
	$("#from_date").datepicker({dateFormat:'<?php echo $js_date_format; ?>',changeMonth: true,changeYear: true});
	$("#to_date").datetimepicker({dateFormat:'<?php echo $js_date_format; ?>',changeMonth: true,changeYear: true});
	$("#from_date").datepicker('setDate', new Date(<?php echo date("Y").",".date("m").",".date("d").",".date("H").",".date("i").",".date("s");?>));
	$("#to_date").datepicker('setDate', new Date(<?php echo date("Y").",".date("m").",".date("d").",".date("H").",".date("i").",".date("s");?>));
	$("#mobile_number").Mobile_Comma_Only();

	myLayout.close( "west" );
	//loadEaxtraJs();
	setTimeout("alert_master()", 15000);
	<?php /*foreach($desplay_settings as $setting => $status){ ?>
		user_DisplayArray['<?php echo $setting; ?>']=<?php if($status==""){ if($this->session->userdata('usertype_id')==3){ echo 0; }else{ echo 1; }}else{ echo $status; } ?>;
	<?php } */ ?>
	
});
function resizeDMap(){
	if (dMap != undefined && dMap != null) {
		setTimeout(function () {
			var center = dMap.getCenter();
			google.maps.event.trigger(dMap, "resize");
			dMap.setCenter(center);
			$('.map-wrap').css({ height: 'auto', width: 'auto' });
		 }, 500);
	}
}
var dLoop = 0;
function loadVehicleDistanceData(){	
	$("#dispMap").html("<img src='<?php echo base_url(); ?>assets/images/loading.gif' style='padding-top:46px'>");
	fromVehicle = [];
	toVehicle = [];
	distanceVehicle = [];
	fromVehicleId = [];
	toVehicleId = [];
	vDStart = [];
	vDEnd = [];
	vDStartLatLong = [];
	vDEndLatLong = [];
	$.post("<?php echo base_url(); ?>index.php/home/getDist/id/"+selected_assets_ids,function(data){
		selectedAssetsIds = selected_assets_ids.split(",");
		
		for(i=0; i<(data.points.length-1); i++){
			var truck1 = data.points[i].truck;
			var start=new google.maps.LatLng(data.points[i].lat, data.points[i].lng);
			
			for(j=(i+1); j<(data.points.length); j++){
				
				var end = new google.maps.LatLng(data.points[j].lat, data.points[j].lng);
				var truck2 = data.points[j].truck;
				fromVehicle.push(truck1);
				toVehicle.push(truck2);
				fromVehicleId.push(data.points[i].assets_id);
				toVehicleId.push(data.points[j].assets_id);
				vDStart.push(start);
				vDEnd.push(end);
				vDStartLatLong.push(data.points[j].lat+':'+data.points[j].lng);
				vDEndLatLong.push(data.points[i].lat+':'+data.points[i].lng);
			}
		}
		loadVehicleDistance();
		
	},'json');
}			
function loadVehicleDistance(){
	if(dLoop < vDStart.length){
		start = vDStart[dLoop];
		end = vDEnd[dLoop];
		//alert(start+":"+end);
		var service = new google.maps.DistanceMatrixService();
		service.getDistanceMatrix(
		{
			origins: [start],
			destinations: [end],
			travelMode: google.maps.TravelMode.DRIVING,
			unitSystem: google.maps.UnitSystem.METRIC,
			avoidHighways: false,
			avoidTolls: false
		}, function(response, status){
		 if (status != google.maps.DistanceMatrixStatus.OK) {
			alert('Error was: ' + status);
		} else {
			
			var dist=(response.rows[0].elements[0].distance.value/1000).toFixed(2);
			distanceVehicle.push(dist);
			dLoop++;
			loadVehicleDistance();
			}
		});
		
	}else{
		dLoop = 0;
		loadVehicleDistanceValue();
		
	}
}
function loadVehicleDistanceValue(){
		$("#dispMap").html("<h3><?php echo $this->lang->line("Distance Between"); ?></h3>");
		var htm = "<table width='100%'>";
		for(k=0;k<fromVehicle.length; k++){
			//alert(fromVehicleId[k])
			//alert(fromVehicle[k]+" : "+toVehicle[k]+" : "+distanceVehicle[k]);
			htm += " <tr style='border-bottom:1px solid #999'><td><h6>"+fromVehicle[k]+"</h6></td><td><img src='<?php echo base_url(); ?>assets/assets_photo/arrows.png' width='30px'/></td><td><h6>"+toVehicle[k]+"</h6></td><td align='center'><h3 style='color:red'>"+distanceVehicle[k]+" KM</h3></td><td><input type='button' value='Map' onclick='getAssetsDistance_New("+fromVehicleId[k]+","+toVehicleId[k]+")'></td></tr>";
		}
		htm += "</table><br><a style='cursor:pointer' onclick='loadVehicleDistanceData()'>Refresh</a>&nbsp;&nbsp;<a style='cursor:pointer' onclick='saveVehicleDistanceData()'>Save</a>";
		$("#dispMap").append(htm);
		jQuery("input:button, input:submit, input:reset").button();
	}
function saveVehicleDistanceData(){
	$("#loading_top").css("display","block");
	$.post("<?php echo base_url(); ?>/index.php/home/saveDist",
	{fromVehicleId:fromVehicleId, toVehicleId:toVehicleId, vDStartLatLong:vDStartLatLong, vDEndLatLong:vDEndLatLong, distanceVehicle:distanceVehicle},
	function(data)
	{
		$("#loading_top").css("display","none");
		$("#alert_dialog").html("<?php echo $this->lang->line("Data Saved Successfully"); ?>");
		$("#alert_dialog").dialog('open');
	});
}	
function loadAssets_second(){
	$("#loading_top").css("display","block");
	$('#distanceBtn_div').hide();
	var txt = $('#srcTxt').val();
	var txtlast = $('#srcTxt').val().lastIndexOf("(");
	if(txtlast!=-1)
	{
		txt = txt.substring(0,txtlast);
	}
	if(txt == "Search Assets..."){
		txt = "";
	}
	var report = Array();
	<?php if($this->session->userdata('show_dash_assets_combo')==1){ ?>
		$(".optdetail").each(function(index, ele) {
			report[index] = $(this).val();
		});	
	<?php } ?>
	if($("#main #lastpoint_list_div").html())
	{
		jQuery("#lastpoint_grid").jqGrid('setGridParam',{postData:{ txt: txt, report:report, limit:limit, page:1 }}).trigger("reloadGrid");
	}
	else
	{
		if($req_obj_xhr!=null)
		{
			if(typeof $req_obj_xhr==='object'){$req_obj_xhr.abort()};
		}
		$req_obj_xhr = $.post(url, { txt: txt, report:report, limit:limit, page:page },
		function(data) {
			
			$("#main").html(data);
			toggleSelection();
			
			//$("#loading_dialog").dialog("close");
			$("#loading_top").css("display","none");
			var x;
			setInterval(function() {

					if(x == 0) {
						$('.blinking').css('color', 'white');  
						x = 1;
					} else  {
						if(x = 1) {
							$('.blinking').css('color', 'red'); 
							x = 0;
						}
					} 			
			}, 500);
		});
	}
}

function loadJGrid()
{
	if(!$.isFunction($(".x").jqGrid))
	{
		// var varJgrid="<link rel='stylesheet' type='text/css' href='<?php echo base_url(); ?>assets/jqgrid/css/ui.jqgrid.css' /><script type='text/javascript' src='<?php echo base_url(); ?>assets/jqgrid/js/i18n/grid.locale-en_min.js'></sc"+"ript><script type='text/javascript' src='<?php echo base_url(); ?>assets/jqgrid/js/jquery.jqGrid.src_min.js'></scr"+"ipt>";
		// $("head").append(varJgrid);
	}
}
function alert_master()
{
	$.post("<?php echo base_url(); ?>/index.php/home/alert_master",
	function(data)
	{
		if(data.result==true)
		{
			for(i=0;i<data.count;i++)
			{
				open_pop_up(data.alert[i].header,data.alert[i].msg,data.alert[i].link,data.alert[i].type);
			}
		}
		setTimeout('alert_master()',50000);
	},'json');	
}
loadJGrid();
function logout_browser_close() {
	$.post("<?php echo site_url('sessions/logout'); ?>",function(data){	
	});
}
function show_hide_tree_map() {
	var map_resize = false;
	
	if(! $("#map_div").is(":visible")) {
		map_resize = true;		
	}
	$("#map_div").toggle( "slow" );
	
	if(map_resize == true) {
		resizeDMap();
	}
}
</script>
</head>
<?php
	if($this->session->userdata('language') ){
		$language = $this->session->userdata('language');
	}else{
		$language = 'english';
	}
?>
<body onLoad="myLayout.allowOverflow('1');myLayout.resizeAll();" style="line-height:20px;" onbeforeunload="logout_browser_close()">
<?php
	$this->load->view("upload_image");
?>
	<!-- Primary Page Layout
	================================================== -->
	<!-- Delete everything in this .container and get started on your own site! -->
	<!--<div id="chatbox_main" class="chatbox" style="z-index:9999;bottom: 0px; right: 20px; display: block;"><a onclick="javascript:toggleChatBoxGrowth('main')" href="javascript:void(0)"><div class="chatboxhead ui-widget-header"><div class="chatboxtitle" >Who's Online</div><br clear="all"></div></a><div class="chatboxcontent" style='height:265px'></div></div>-->
	<div id="profile" style='background:none repeat scroll 0 0 #F5F5F5;box-shadow:0 2px 4px rgba(0, 0, 0, 0.2);border:1px solid #BEBEBE;z-index:198 !important;'>
				<div style='height : 150px;background:none repeat scroll 0 0 #FFFFFF;border-bottom:1px solid #BEBEBE;box-shadow:0 2px 4px rgba(0, 0, 0, 0.12);padding-bottom:2px'>
				<a style="color: red; padding-top: 5px; border: 0px none; float: right;"><b><?php echo $this->lang->line('ID'); ?> : <?php echo $this->session->userdata('user_id'); ?></b>&nbsp;&nbsp;&nbsp;</a>
		<!--		<a href="#" id="close" style="float:right; padding:5px"><img src="<?php echo base_url(); ?>assets/dashboard/images/close.png"/></a>-->
				<div style="float: left; padding: 14px; margin-top: 11px;height: 104px; width: 104px;">
					<?php if($this->session->userdata('photo')==""){ ?>
					<img src="<?php echo base_url(); ?>assets/driver-photo/not_available.jpg"  style='height: 104px; width: 104px;' id="chage_profile_photo" alt="Image Not Found"/>
					<?php }else{ ?>
					<img style='height: 104px; width: 104px;' id="chage_profile_photo"/>
					<?php } ?>
				</div>
				
				<div class="profile_change" onClick="profile_div();"> <?php echo $this->lang->line('change_photo'); ?> </div> 
				<div style="padding:26px">
					<span style='font-weight: bold;font-size:17px'><?php echo $this->session->userdata('first_name'); ?> <?php echo $this->session->userdata('last_name'); ?></span> 
					<span style='font-weight: bold;font-size:17px'><?php echo $this->session->userdata('company_name'); ?> </span> 
					<span style="overflow: hidden; word-wrap: break-word;"><?php echo $this->session->userdata('email_address'); ?></span> 
					<span style="overflow: hidden; word-wrap: break-word;"><?php echo $this->session->userdata('mobile_number'); ?></span>
					<span style="overflow: hidden; word-wrap: break-word;"><u><?php echo $this->lang->line('last_login'); ?> : </u><?php echo date("$date_format $time_format", strtotime($this->session->userdata('last_login_time'))); ?>  
					</span> 
					<span style='font-weight: bold; font-size: 17px; position: absolute; top: 130px; left: 325px;'>
                   <?php if($ans_hist == '1') { ?> <a onClick="$('#profile').hide();subMenu('login_list')" style='font-weight: bold; float: right; cursor: pointer; font-size: 14px; border: 0px none;'> <?php echo $this->lang->line('more'); ?></a><?php } ?></span>
					<br/>
				</div>
			</div>
				<div style="clear:both;  padding:23px" id='logout_link'>
					<table width="100%">
					<tr>
					<td align='left'>
					<a href="JavaScript:void(0);" style='padding: 10px 24px 10px 24px;  text-decoration: none;' onClick=" hideSubMenu('<?php echo base_url(); ?>/index.php/profile','<?php echo $this->lang->line('Profile'); ?>','<?php echo $this->lang->line('Profile'); ?>');$('#profile').hide(); "><?php echo $this->lang->line('Profile'); ?></a>
					</td>
					<td align='center'>
					<?php if($data == "Allow") { ?>
					<a  href="#" onClick="hideSubMenu('<?php echo base_url(); ?>/index.php/changepassword','<?php echo $this->lang->line('Changepassword'); ?>','<?php echo $this->lang->line('Changepassword'); ?>');$('#profile').hide(); " style='padding: 10px 24px 10px 24px; text-decoration: none;'><?php echo $this->lang->line('Changepassword'); ?></a><?php } ?>
					</td>
					<td align='right'>
					<a  href="<?php echo site_url('sessions/logout'); ?>" style='padding: 10px 24px 10px 24px; text-decoration: none;'><?php echo $this->lang->line('log_out'); ?></a>
					</td>
					</tr>
					</table>
				</div>
			</div>
    <div class="ui-layout-north" style="height:120px;">
		<div style="top: 0px; font-weight:bold;float:right;">
	
			<div style="height:30px;display:block;float:right; margin-bottom:5px">
			
				<?php
				$per=0;
				if($r = @file_get_contents('/proc/meminfo')) {
					$dat = array(); 
					foreach(explode("\n", $r) as $line) { 
						if(preg_match('/^([^:]+):[ ]+([0-9]+.*)$/', $line, $matches))
							$dat[$matches[1]] = $matches[2];
					}
					$free = (int)((@$dat['MemFree'] ));
					$total = (int)(@$dat['MemTotal']);
					$use = $total - $free;
					$per = sprintf('%.2f',($use / $total) * 100);
				}
				$imgDot="green.png";
				if($per <= 50){
					$imgDot="green.png";
				}else if($per > 50 && $per <= 80){
					$imgDot="blue.png";
				}else if($per > 80){
					$imgDot="red.png";
				}
				?>
				<div style="display:none" id="serverLoadCnt">
				<span style='display:block;text-align:left;'><img src="<?php echo base_url(); ?>assets/images/green.png"/> Server Load is below 50%</span>
				<span style='display:blocktext-align:left;'><img src="<?php echo base_url(); ?>assets/images/blue.png"/> Server Load is between 51% to 80%</span>
				<span style='display:block;text-align:left;'><img src="<?php echo base_url(); ?>assets/images/red.png"/> Server Load is above 81%</span></div>
				<!--a href="#" style="float:left;padding:2px" id='bandwidthTest'><img src="<?php //echo base_url(); ?>assets/images/bandwidth.png" /></a-->
				<a href="#" style="float:left;padding:2px" id='serverLoad'><img src="<?php echo base_url(); ?>assets/images/<?php echo $imgDot; ?>" /></a>		
				<a href="#" style="float:left;padding:2px" ><img src="<?php echo base_url(); ?>assets/dashboard/images/speaker.png" /></a>
				<a href="#" style="float:left;padding:2px"><img src="<?php echo base_url(); ?>assets/dashboard/images/help.png" /></a>
				
				<a href="#" class="smallbtn" id="logout" ><img src="<?php echo base_url(); ?>assets/driver-photo/not_available.jpg"  style='float: left; width: 21px; padding-right: 5px; height: 23px;' id="img_upload_setid"/><?php echo $this->lang->line('my_account'); ?></a>
			</div>
			<div class="four columns omega" style="padding-top:5px;z-index:155;">
				
				<div style="clear:both"></div>
				<div style="float:right ; margin-top:5px">
					
					<?php /*$lang_array = explode(";",$this->session->userdata('disp_language_list'));
					
					for($i=0;$i<count($lang_array);$i++)
					{
						echo "<a href='#' class='link' onclick='setLanguage(\"".$lang_array[$i]."\")'>".$this->lang->line($lang_array[$i])."</a>";
					}*/
					?>
					
				</div><br/>
 			</div>
		</div>
		<div class="sixteen columns" id="header" style="float:left;width:80%;">
			
			<div class="twelve columns alpha" style="float:left; width:100%">
				<!-- h1 class="remove-bottom" style="padding-left:0; float:left;">
					<img src="<?php echo base_url(); ?>assets/dashboard/images/<?php echo $this->session->userdata('user_logo'); ?>_logo.png" alt="logo" onClick="window.location.reload()" style="cursor:pointer"/>
				</h1 -->
                
				<div id="header_msg" style="float:right;display:none;">
                <?php echo $this->lang->line("For Best Performance view the site in Chrome Browser"); ?>
                </div>
			</div>
            <?php if($msg != ""){ ?>
            <div style="width:65%;margin: 30px 0px 0px 200px; position: absolute;color:red;font-size:16px;font-weight:bold;"><center>
			<MARQUEE WIDTH=100% BEHAVIOR=SCROLL>
			<?php echo $msg; ?>
			</MARQUEE>
			</center>
			</div>
			<?php } ?>
			
		</div>
		
		<!--<hr class="half-bottom" />-->
		<div class="sixteen columns " style="float:left;">
			<?php 
				if($this->session->userdata('usertype_id')==1){
					$SQL = "select  mm.where_to_show,mm.menu_name,mm.menu_link,mm.tab_title,mm.id as menu_id,mm.menu_image,mm.parent_menu_id from main_menu_master mm  where mm.del_date is null and mm.status = 1 and mm.status=1  order by mm.priority";
				}else{
					$data= array("30,31");
					if($create_user_datea==1){
						$data[]=12;
						$data[]=24;
						$data[]=15;
					}
					$SQL = "select concat(group_concat(mus.menu_id),',',mm.parent_menu_id) as data from  mst_user_profile_setting mus left join main_menu_master mm on mm.id=mus.menu_id where mus.profile_id=".$this->session->userdata('profile_id')." and mus.setting_name='main' ";
					if($this->session->userdata('menu_view')!=1){
						$SQL .= " and mm.type!=0  ";
					}
					if($this->session->userdata('report_view')!=1){
						$SQL .= " and mm.type!=1  ";
					}

					$SQL .= " group by mm.parent_menu_id";
					$query = $this->db->query($SQL);
					
					foreach($query->result_Array() as $row ){
						if($row['data']!=""){
							$data[]=$row['data'];
						}
					}
					$SQL = "select  mm.where_to_show,mm.menu_name,mm.menu_link,mm.tab_title,mm.id as menu_id,mm.menu_image,mm.parent_menu_id from main_menu_master mm  where mm.del_date is null and mm.status = 1 and mm.status=1 and mm.id in (".implode(",",$data).")  order by mm.priority";
				}
				$query = $this->db->query($SQL);
				$i= 0;
				$parent_menu_id = array();
				$where_to_show = array();
				$menu_name = array();
				$menu_link = array();
				$menu_id = array();
				$menu_image = array();
				$tab_title = array();
				
				foreach($query->result() as $row )
				{
					$row->menu_link = str_replace("_base_url_",base_url(),$row->menu_link);
					$where_to_show[$i] = $row->where_to_show;
					$menu_name[$i] = $row->menu_name;
					$menu_link[$i] = $row->menu_link;
					$menu_id[$i] = $row->menu_id;
					$menu_image[$i] = $row->menu_image;
					$parent_menu_id[$i] = $row->parent_menu_id;
					$tab_title[$i] = $row->tab_title;
					$i++;
				}
				function menu_print($tab_title,$menu_image,$menu_id,$menu_link,$menu_link,$menu_name,$where_to_show,$parent_menu_id,$parent_id,$val)
				{
					
					if (!in_array($parent_id, $parent_menu_id)) {
						return "";
					}
					echo "<ul";
					if($parent_id == "")			
						echo ' id="menu1" class="menu" ';
					echo ">";
					for($i=0;$i<count($menu_id);$i++)
					{
						if($parent_menu_id[$i]==$parent_id)
						{
							echo "<li ";
							
							if($where_to_show[$i] == 'link'  and $parent_id == ""){
								if($tab_title[$i]!="About Us" && $tab_title[$i] != 'Home'){
								echo " onclick='hideSubMenu(\"".$menu_link[$i]."\",\"".$menu_name[$i]."\",\"".$menu_name[$i]."\")' ";
								}else if($tab_title[$i] == 'Home'){
									echo " onclick='topMenuToTab(\"".$menu_link[$i]."\",\"".$menu_name[$i]."\",\"".$menu_name[$i]."\")' ";
								}else if('vehicle.worldwidetrackingservices.com' == $_SERVER['HTTP_HOST']){
									echo " onclick='window.open(\"http://worldwidetrackingservices.com/\");' ";
								}else if('test.trackeron.com' == $_SERVER['HTTP_HOST'] || 'vts.trackeron.com' == $_SERVER['HTTP_HOST']){
									echo " onclick='window.open(\"http://chateglobalservices.com/\");' ";
								} else {
									echo " onclick='window.open(\"http://www.nkonnect.com/technology-solutions/contact/\");' ";
								}
							}else if($where_to_show[$i] == 'link'  and $parent_id != "") {
								echo " onclick='topMenuToTab(\"".$menu_link[$i]."\",\"".$val->line($tab_title[$i])."\",\"".$val->line($tab_title[$i])."\")' ";
							} else if($where_to_show[$i] == 'sidebar'){
								echo " onclick='hideSubMenu_all();subMenu(\"".$menu_name[$i]."\")' ";
							}
							echo " ><a href='Javascript:void(0);' ";
							if($where_to_show[$i] != 'sidebar' && $tab_title[$i] != 'Home')
							{
								echo "onclick='myLayout.close( \"west\" );myLayout.resizeAll()'";
							}
							echo ">";
							if($menu_image[$i] != "")
								echo "<img  src='".base_url()."assets/menu_image/".$menu_image[$i]."' class='".$where_to_show[$i]."'  />";
							echo "&nbsp;";
							echo $val->line($menu_name[$i]);
							echo "&nbsp;&nbsp;&nbsp;";
							
							if($where_to_show[$i] == 'link')
							{
								echo "</a>";
							}
							if($where_to_show[$i] == 'menu')
							{
								echo "<img src='".base_url()."/assets/menu-ui/images/down.png' class='menu' /></a>";
								menu_print($tab_title,$menu_image,$menu_id,$menu_link,$menu_link,$menu_name,$where_to_show,$parent_menu_id,$menu_id[$i],$val);
							}
							
							if($where_to_show[$i] == 'sidebar')
							{
								echo "<img src='".base_url()."/assets/menu-ui/images/down.png' class='menu' /></a>";
								echo "<div id='".$menu_name[$i]."' style='display:none;'>";
								for($j=0;$j<count($menu_id);$j++)
								{
									if($parent_menu_id[$j]==$menu_id[$i])
									{
									echo "<a class='ui-button ui-widget ui-state-default ui-button-text-only' style='padding:5px;width:93%; text-align:left;' href='Javascript:void(0)' onclick='topMenuToTab(\"".$menu_link[$j]."\",\"".$val->line($tab_title[$j])."\",\"".$val->line($tab_title[$j])."\")' class='link".$where_to_show[$j]."'><img class='menu' />".$val->line($menu_name[$j])."</a>";
									}
								}
								echo "</div>";

							}
							echo "</li>";
						}
					}
					/*echo "<li onclick='topMenuToTab(\"home/multi_map\",\"Multi Screen\",\"Multi Screen\")' ";
					echo " ><a href='Javascript:void(0);' ";
					echo "onclick='myLayout.close( \"west\" );myLayout.resizeAll()'";
					echo "</a>";
					echo "<img src='".base_url()."/assets/menu-ui/images/location.gif' width='16' height='16' class='menu' />&nbsp;";
					echo "<span style='color:red !important;' >Multi Screen</span>";
					echo "&nbsp;&nbsp;&nbsp;";
					echo "<img  src='".base_url()."assets/menu_image/star.png'/></a>";
					echo "</li>";
					*/
					echo "</ul>";
				}
				menu_print($tab_title,$menu_image,$menu_id,$menu_link,$menu_link,$menu_name,$where_to_show,$parent_menu_id,"",$this->lang);
			?>
		</div>	
			
	</div>
	<div class="ui-layout-center" id="tabs" style="float:left;position:relative;">
			<ul>
				<li id="tabs-dash"><a href="#tabs-1"><?php echo $this->lang->line('dashboard'); ?></a></li>
				<li class="addons" id="testAddons">
					<img src="<?php echo base_url(); ?>assets/images/map.png" alt="Show/Hide Map" title="Show/Hide Map" onClick="show_hide_tree_map()" style="cursor:pointer;float:left;" height="32" width="32" />
					<img id="imgmaxmin" src="<?php echo base_url(); ?>assets/style/img/icons/window_full_screen.png" style="cursor:pointer;float:left" alt="max" title="Maximize" onClick="maximize(this)" />
					<?php echo "<img src=\"http://nkonnect.com/track/assets/style/img/icons/new_window.png\" title=\"".$this->lang->line('new_window')."\" style='cursor:pointer;padding-left:3px' rel='external' />"; ?> 
				
					<?php  echo "<img src=\"http://nkonnect.com/track/assets/style/img/icons/printer.png  \" title=\"".$this->lang->line('print')."\" style='cursor:pointer;' rel='print' />"; ?>
				</li>
			</ul>	
			<div id="tabs-1">
				
				<div class="sixteen columns" style="width:100%" >
					<div style="float:left;">
						<img src="<?php echo base_url(); ?>assets/dashboard/images/selectgridview.png" alt="<?php echo $this->lang->line('list_view'); ?>" title="<?php echo $this->lang->line('list_view'); ?>" onClick="loadAssetsList()" style="cursor:pointer; margin-top: 20px;" id="select_assest_list_view" />
						<img src="<?php echo base_url(); ?>assets/dashboard/images/listview.png" alt="<?php echo $this->lang->line('grid_view'); ?>" title="<?php echo $this->lang->line('grid_view'); ?>" onClick="loadAssetsGrid()" style="cursor:pointer; margin-top: 20px;" id="select_assest_grid_view"/>
						<img src="<?php echo base_url(); ?>assets/dashboard/images/thumbnail_view.png" alt="<?php echo $this->lang->line('thambnail_view'); ?>" title="<?php echo $this->lang->line('thambnail_view'); ?>" onClick="loadAssetsThumb()" style="cursor:pointer; margin-top: 20px;" id="select_assest_thumb_view"/>
						</div>
                        <?php if($this->session->userdata('show_dash_assets_combo') != 0){ ?>
                        <div style="width:70%;float:left; font-size: 1em; font-family: Lucida Grande,Lucida Sans,Arial,sans-serif;">
							<table width="100%" cellspacing="3" cellpadding="5" style="margin: 0px 5px;">
								<tr>
								<td width="25%">Users<br><select class="optdetail" id="opt_users" style="border: 1px solid #CCCCCC; border-radius: 2px; color:#444444; padding:6px 4px;" onChange="changeReport(); setcombo('opt_users', this.value);"><option value=''>All Users</option><?php echo $subUserOpt; ?></select></td>
								<td width="15%">Groups<br><select class="optdetail" id="opt_groups" style="border: 1px solid #CCCCCC; border-radius: 2px; color:#444444; padding:6px 4px;" onChange="changeReport(); setcombo('opt_groups', this.value);"><option value=''>All Group</option><?php echo $groupOpt; ?></select></td>
								<td width="15%">Areas<br/><select class="optdetail" id="opt_areas" style="border: 1px solid #CCCCCC; border-radius: 2px; color:#444444; padding:6px 4px;" onChange="changeReport(); setcombo('opt_areas', this.value);"><option value=''>All Areas</option><?php echo $areasOpt; ?></select></td>
								<td width="15%">Landmarks<br/><select class="optdetail" id="opt_landmarks" style="border: 1px solid #CCCCCC; border-radius: 2px; color:#444444; padding:6px 4px;" onChange="changeReport(); setcombo('opt_landmarks', this.value);"><option value=''>All Landmarks</option><?php echo $landOpt; ?></select></td>
				                <td width="15%">Owners<br/><select class="optdetail" id="opt_owners" style="border: 1px solid #CCCCCC; border-radius: 2px; color:#444444; padding:6px 4px;" onChange="changeReport(); setcombo('opt_owners', this.value);"><option value=''>All Owner</option><?php echo $ownerOpt; ?></select></td>
				                <td width="15%">Divsions<br/><select class="optdetail" id="opt_divisions" style="border: 1px solid #CCCCCC; border-radius: 2px; color:#444444; padding:6px 4px;" onChange="changeReport(); setcombo('opt_divisions', this.value);"><option value=''>All Division</option><?php echo $divisionOpt; ?></select></td>
								</tr>
							</table>
                        </div>
		                <?php } ?>

					<?php if($this->session->userdata('show_dash_search_box')==1){ ?>
						<form onSubmit="return searchAssets()" style="float:right; margin-top: 20px;">
								<?php /* if($this->session->userdata('show_dash_add_user_button')==1 && ($this->session->userdata('usertype_id')!=1)){ ?>					
								<a onClick="group_dialog_opn(0);" title="<?php echo $this->lang->line('create_user'); ?>" ><img src="<?php echo base_url(); ?>assets/images/login.png" style="padding: 0px 5px 15px 5px;" height="32" width="32" /></a>
				                <?php } ?>
								<?php if($this->session->userdata('show_dash_add_group_button')==1 ){ ?>
                                <a onClick="group_dialog_opn(1);" title="<?php echo $this->lang->line('add_to_group'); ?>"><img src="<?php echo base_url(); ?>assets/images/truck-group.jpg" style="padding: 0px 5px 15px 5px;" height="32" width="32" /></a>
                                <?php } ?>
                                <a onClick="group_dialog_opn(2);" title="<?php echo $this->lang->line('map_view'); ?>"><img src="<?php echo base_url(); ?>assets/images/map.png" style="padding: 0px 5px 15px 5px;" height="32" width="32" /></a>
                                <a onClick="group_dialog_opn(4);" title="Multi Screen"><img src="<?php echo base_url(); ?>assets/images/multi_screen.png" style="padding: 0px 5px 15px 5px;" height="32" width="32" /></a>
                                <?php if($this->session->userdata('show_dash_dashboard_button')==1){ ?>
                                <a onClick="group_dialog_opn(3);" title="<?php echo $this->lang->line('assets_dashboard'); ?>"><img src="<?php echo base_url(); ?>assets/images/dashboard.png" style="padding: 0px 5px 15px 5px;" height="32" width="32" /></a>
                                <?php } */ ?>                            
							<input id="srcTxt" style="display: inline !important;" type="text" placeholder="<?php echo $this->lang->line('search'); ?>" />
							</form>
					<?php }else{ ?>
					<input id="srcTxt" type="hidden"/>
					<?php } ?>
				</div>
				<div class="sixteen columns half-bottom" style="width:100% !important; float:left;">
                		<div id="map_div" style="width:100%; height: 450px;"></div>
						<div class="sixteen columns half-bottom" style="width:95%;float:left;padding-left:40px;">
						
						<div id="detailed_pan" style="float: left; font-size: 13px; padding-top: 5px; padding-left: 12px;">
						<?php if($this->session->userdata('show_dash_assets_combo')==1){ ?>
						<span class="ui-state-default" style="border-radius:7px;padding:2px 5px;border:1px solid">
						<input type='checkbox' onClick="select_all_ast();" style="padding: 0px; margin: 0px 0px 3px 5px;" id="all_ast"/>
						<a onClick='detail_list_a("")' style='text-decoration:underline' id='assets_total' rel="<?php echo $this->lang->line('Number of Total Vehicles'); ?>"><?php echo $this->lang->line("Total Assets"); ?> : <strong><span id="assets_total_1"><?php echo $total_1; ?></span></strong></a></span>&nbsp;
                        
						<?php } ?>
			<a onClick="group_dialog_opn(1);" title="<?php echo $this->lang->line('add_to_group'); ?>"><img src="<?php echo base_url(); ?>assets/images/truck-group.jpg" height="32" width="32" /></a>
                        <a onClick="group_dialog_opn(2);" title="<?php echo $this->lang->line('map_view'); ?>"><img src="<?php echo base_url(); ?>assets/images/map.png" height="32" width="32" /></a>
                        <a onClick="group_dialog_opn(4);" title="Multi Screen"><img src="<?php echo base_url(); ?>assets/images/multi_screen.png" height="32" width="32" /></a>
                        <?php if($this->session->userdata('show_dash_dashboard_button')==1){ ?>
                        <a onClick="group_dialog_opn(3);" title="<?php echo $this->lang->line('assets_dashboard'); ?>"><img src="<?php echo base_url(); ?>assets/images/dashboard.png" height="32" width="32" /></a>
                        <?php } ?>     
                        
						<input type='checkbox' onclick='stop_resume_toggle()' <?php if($auto_refresh_setting == 1) echo 'checked="checked"'; ?> id='checkboxToggle'> <?php echo $this->lang->line('data_refresh_after'); ?> <input type='text' size='2' onblur='counter_change()' value='15' id='time_in_seconds'> <?php echo $this->lang->line('seconds'); ?> (<?php echo $this->lang->line('refresh_after'); ?> <span id='seconds'>15</span> <?php echo $this->lang->line('second'); ?>) &nbsp;&nbsp;<span onClick="reloadDashboard_Assets_Timer()" style="font-weight:bold;text-decoration:underline;cursor:pointer"><?php echo $this->lang->line('refresh'); ?></span> <!--&nbsp;&nbsp;<span onClick="toggleSelection()" style="font-weight:bold;text-decoration:underline;cursor:pointer"><?php echo $this->lang->line('Select/Unselect All'); ?></span --></div>
						</div>                        
						<div id="main" style="height: 100%;" align="center"></div>
				</div>

				<div style="height:10px"></div>
			</div>
			
		</div>
	<?php if($this->session->userdata('user_logo')=="nKonnect"){ ?>		
	<div class="ui-layout-south">
		<center>&copy; 2013 NKonnect Sensing Future v3.2.0 latest updated on 19.08.2013 06.15 AM IST<br></center>
	</div>
	<?php } else if($this->session->userdata('user_logo')=="wts"){ ?>		
	<div class="ui-layout-south">
	<center>&copy; 2013 <?php echo $this->lang->line('wts'); ?> v3.0.0 latest updated on 28.08.2013 06.15 AM IST<br></center>
	</div>
	<?php } ?>
	<div class="ui-layout-west" id="west_sub_menu"></div>

	<!-- JS
	================================================== -->
<div id="profile_dialog" style="display:none;"></div>
<div id="alert_dialog" style="display:none;"></div>
<div id="distance_box_dialog" style="display:none;"></div>
<div id="group_dialog" style="display:none;">
<div class="create_user_div" align="center" id="create_g_div"><a href="#" onClick="create_group_func()" style="text-decoration:none"><?php echo $this->lang->line("Create Group"); ?></a>&nbsp;&nbsp;<a href="#" onClick="edit_group_func()" style="text-decoration:none"><?php echo $this->lang->line("Edit Group"); ?></a>&nbsp;&nbsp;<a href="#" onClick="delete_group_func()" style="text-decoration:none"><?php echo $this->lang->line("Delete Group"); ?></a></div>
<p id="tips_grp" class="addTips"></p>
	<table width="100%" align="center" class="formtable">
	<tbody>
	<tbody>
		<tr id="group_list_combo">
			<td>
			<?php echo $this->lang->line('add_to_group'); ?> : 
			<select id="group_combo" class="select ui-widget-content ui-corner-all" onChange="changeGroupCombo(this.value)">
			</select>
			</td>
		</tr>
		<tr id="assets_combo_grp">
			<td width="100%" colspan='2'><label><?php echo $this->lang->line("Device_Name"); ?></label><select name="cmb_assets_grp" id="cmb_assets_grp" class="select ui-widget-content ui-corner-all" style="height:130px" multiple='multiple' ></select> </td>
		</tr>
		
		<tr id="new_group_tr">
			<input type="hidden" id="edit_group_id" value="">
			<td><label><?php echo $this->lang->line('group_name'); ?>*</label> :
			<input type="text" id="new_group" class="text ui-widget-content ui-corner-all" /></td>
		</tr>
		<tr>
			<td width="50%" colspan="2" align="center">
			<input type="button" value="<?php echo $this->lang->line('submit'); ?>" onClick="addToGroup()">
			<input type="button" value="<?php echo $this->lang->line('cancel'); ?>" onClick="cancel_ds_grp()">
			</td>
		</tr>
		<tr>
			<td align="center" colspan="2">
			&nbsp;&nbsp;
			<span style="float:right">* <?php echo $this->lang->line('fields_are_mendatory'); ?></span>
			<div style="clear:both"></div>
			</td>
		</tr>
	</tbody>
	</table>
</div>
<div id='popup' style='position: fixed; bottom: 0px; z-index: 10000; width: 35%; right: 6px;' >
	<audio id="sound_start" src="<?php echo base_url(); ?>beep.wav" controls preload="auto" autobuffer style='display:none'></audio>
</div>
<div id="confirm_alert_dialog" style="display:none;"><?php echo $this->lang->line("Are You Sure To Delete"); ?>?</div>
<!--<div id="loading_dialog" style="display:none;" class="removeTitle" ><img src='<?php echo base_url(); ?>assets/images/loading.gif' style="padding-right:7px">Loading...<a href="#" onclick="cancelLoading()">Cancel</a><input type="button" id="loading_dialog_btn_line" value="Close" style="float:right"><div style="clear:both"></div></div> -->
<div id="right_click_disabled" style="display:none;text-align:center" class="removeTitle" ><?php echo $this->lang->line("Function_Disabled"); ?>.!<img src="<?php echo base_url(); ?>assets/upload_image/close.png" alt="close" style='background-color:white;height:12px;cursor:pointer;float:right;margin-top:-5px;border:1px solid lightblue;border-radius:3px;margin-right:-10px' onClick="$('#right_click_disabled').dialog('close');"/> </div>
<div class="vX UC" style="display: none" id="loading_top"><div class="J-J5-Ji"><div class="UD"></div><div class="vh"><div class="J-J5-Ji"><div class="vZ L4XNt"><span class="v1"><?php echo $this->lang->line("Loading"); ?>... <!--&nbsp;&nbsp;<a href="#" onclick="cancelLoading()">Cancel</a>&nbsp;&nbsp;--></span></div></div><div class="J-J5-Ji"></div></div><div class="UB"></div><span id="top_loading_esc_id" style="font-size:10px;font-weight:bold"><?php echo $this->lang->line("Press_ESC_to_cancel_process"); ?></span></div></div>
<span id='Qtip_one'></span>
<div id="user_dialog" style="display:none;"><br/>
	<div class="create_user_div" align="center" id="create_u_div"><a href="#" onClick="create_usr_func()" style="text-decoration:none"><?php echo $this->lang->line("create_user"); ?></a>&nbsp;&nbsp;<a href="#" onClick="edit_usr_func()" style="text-decoration:none"><?php echo $this->lang->line("Edit User"); ?></a>&nbsp;&nbsp;<a href="#" onClick="delete_usr_func()" style="text-decoration:none"><?php echo $this->lang->line("Delete User"); ?></a></div>
	<form id="frm_users" method="post" action="" onSubmit="return false">
			<input type="hidden" id="assets_ids" name="assets_ids" value="">
			<p id="user_error" class="addTips user_add_table"></p>
			<table width="100%" align="center" class="formtable">
				<tbody>
					<tr id="usr_combo">
						<td width="100%" colspan='2'><label><?php echo $this->lang->line('Add_to_User'); ?> </label><span style="float: right; padding-right: 43px; font-size: 10px;"><?php echo $this->lang->line("UName_FName_LName"); ?></span><select name="add_to_user" id="add_to_user" class="select ui-widget-content ui-corner-all" onchange='user_change(this.value,"user_add_table")'></select> </td>
					</tr>
					<tr id="usr_asset_combo">
						<td width="100%" colspan='2'><label><?php echo $this->lang->line("Device_Name"); ?></label><select name="cmb_assets" id="cmb_assets" class="select ui-widget-content ui-corner-all" style="height:130px" multiple='multiple'></select> </td>
					</tr>
					<tr class='user_add_table'>
						<td width="50%"><input type="hidden" id="u_id" name="u_id" value=""><label><?php echo $this->lang->line('username'); ?> *</label><input type="text" name="username" id="username" class="text ui-widget-content ui-corner-all" value="" /></td> 
						<td width="50%"><label><?php echo $this->lang->line('password'); ?><p id='pass_u_id' style="display:inline"> *</p></label><input type="password" name="password" id="password" class="text ui-widget-content ui-corner-all" value="" />
						</td>
					</tr>
					<tr class='user_add_table'>
						<td width="50%"><label><?php echo $this->lang->line('user_valid'); echo " ".$this->lang->line('from_date'); ?> </label><input type="text" name="from_date" id="from_date" class="date text ui-widget-content ui-corner-all" readonly='readonly'/></td>
						<td width="50%"><label><?php echo $this->lang->line('user_valid'); echo " ".$this->lang->line('to_date'); ?> </label><input type="text" name="to_date" id="to_date" class="date text ui-widget-content ui-corner-all" readonly='readonly'/>
						</td>
					</tr>
					<tr class='user_add_table'>
						<td width="50%"><label><?php echo $this->lang->line('first_name'); ?>* </label><input type="text" name="first_name" id="first_name" class="text ui-widget-content ui-corner-all" value="" /></td>
						<td width="50%"><label><?php echo $this->lang->line('last_name'); ?>* </label><input type="text" name="last_name" id="last_name" class="text ui-widget-content ui-corner-all" value="" />
						</td>
					</tr>
					<tr class='user_add_table'>
						<td width="50%"><label><?php echo $this->lang->line('mobile'); ?> </label><input type="text" name="mobile_number" id="mobile_number" class="text ui-widget-content ui-corner-all" value="" />
						</td>
						<td width="50%"><label><?php echo $this->lang->line('email_address'); ?> </label><input type="text" name="email_address" id="email_address" class="text ui-widget-content ui-corner-all" value="" />
						</td>
					</tr>
						<tr class='user_add_table'>
						<td width="50%"><input type="checkbox" name="sms_alert" id="sms_alert" class="text ui-widget-content ui-corner-all" style="width:11%" value="1" /><label><?php echo $this->lang->line('send_sms'); ?></label>
						</td>
						<td width="50%"><input type="checkbox" name="email_alert" id="email_alert" class="text ui-widget-content ui-corner-all" style="width:11%" value="1" /><label><?php echo $this->lang->line('send_email'); ?></label>
						</td>
					</tr>
					<tr>
						<td align="center" colspan="2">
						<input type="button" id="btn_submit" onClick="submitFormUsers_dash()" value="<?php echo $this->lang->line('submit'); ?>" name="btn_submit"/>
						&nbsp;&nbsp;
						<input type="button" id="btn_cancel" onClick="cancel_ds_usr()" name="btn_cancel" value="<?php echo $this->lang->line("cancel"); ?>" />
						</td>
					</tr>
					<tr>
						<td align="center" colspan="2">
						&nbsp;&nbsp;
						<span style="float:right">* <?php echo $this->lang->line('fields_are_mendatory'); ?></span>
						<div style="clear:both"></div>
						</td>
					</tr>
					
				</tbody>
			</table>
	</form>
</div>

<span id='selected_tab' style='display:none'>tabs-1</span>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-42558408-10']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<script type="text/javascript">
function loadEaxtraJs(){
	$.post("<?php echo base_url(); ?>index.php/home/extra_js",
			 function(data) {	
				$("body").append(data);
			});
}
</script>

</body>
	<!--	<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/themeswitcher.js"></script> -->
	
	
</html>