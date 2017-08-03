<?php $date_format = $this->session->userdata('date_format');  $time_format = $this->session->userdata('time_format'); ?>
<!DOCTYPE html>
	<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
	<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
	<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
	<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]--> 
<head>
<!-- Basic Page Needs  ================================================== -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title>NKonnect - Vehicle Tracking System</title>
<meta name="description" content="">
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
<!--<link href="<?php echo base_url(); ?>assets/style/css/jquery-ui-timepicker.css" rel="stylesheet" type="text/css" />-->
<!-- <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&v=3&libraries=geometry"></script> -->
<link rel="stylesheet" type="text/css"  href="<?php echo base_url(); ?>assets/all_css.php">
<link type="text/css" href="<?php echo base_url(); ?>assets/jquery/ui-themes/redmond/jquery-ui-1.8.5.custom.css" rel="stylesheet" />
<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/dashboard/images/nk.png">

<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<!--[if IE]><script type="text/javascript" src="<?php echo base_url(); ?>assets/jqplot/excanvas.js"></script><![endif]-->

<script src="<?php echo base_url(); ?>assets/all_js.php"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery-ui-timepicker-addon.js"></script>
<?php if (isset($header_insert)) { $this->load->view($header_insert); } ?>
<?php echo $headerjs;	// Loading the Google Map javascript api file ?>
<script type="text/javascript">
var loadCancel=1;
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
var assetDeviceArray = new Array();
var tab_id;
var myLayout;
var grid_paging=10;
var home_template_obj;
var mapDivHidden="<div align='center' id='dispMap' style='display:block;text-align:center;width:290px;height:130px;'></div>";
$(document).ready(function () {
$( "#pbar" ).progressbar({value: 0});
$(".imageHolder").html("<img src='<?php echo base_url(); ?>assets/upload_image/Images_upload/<?php echo $this->session->userdata('photo'); ?>' class='user_img_set' alt='image'></img>");
$("#img_upload_setid").attr("src","<?php echo base_url(); ?>assets/upload_image/Images_upload/<?php echo $this->session->userdata('photo'); ?>");
$("#chage_profile_photo").attr("src","<?php echo base_url(); ?>assets/upload_image/Images_upload/<?php echo $this->session->userdata('photo'); ?>");
$("#user_img_form a").html("<img src='<?php echo base_url(); ?>assets/upload_image/Images_upload/<?php echo $this->session->userdata('photo'); ?>' class='user_img_set' alt='image' width='148'></img>");
	if(! $.browser.msie) {
		$("#header_msg").hide();
	}
	//$('#switcher').themeswitcher();
	$('#menu1').buttonset();
	$('#menu1').ptMenu();
	//setTimeout("$('.box').css('background-color', $('.ui-widget-header:first').css('background-color'))", 2000);
	//$('.box').css('background-color', '#');			
	//$('.box').css('-moz-border-radius', '2px');
	$('body').layout({ applyDefaultStyles: true });
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
	myLayout.close( "west" );

	$("#menu1").click(function(){
		setTimeout("myLayout.resizeAll();", 500);
	})
	$('#tabs').tabs({
		closable: true, 
		cache:true,
		height:'auto',
		remove: function(e, ui){
			$(".ui-dialog-content").dialog("close");
		},
		select: function (e,ui) {
			if(ui.panel.id=="tabs-1")
			{
				if(selected_assets_ids.length>0)
				{
				$("#float-icons").show();
				myLayout.close( "west" );
				}
			}
			else
			{
				$("#float-icons").hide();
			}
			$("#selected_tab").html(ui.panel.id);
		},
		add: function(e, ui){			
			loadJGrid();
			//$("#progress_bar_show").show();
			// $("#loading_top").css("display","none");
			//alert("ui.panel.id="+ui.panel.id+"id="+tab_id);
			$('#tabs').tabs('select', '#'+ui.panel.id);
			/*tabArrKey.push(tab_id)
			tabArrValue.push(ui.panel.id);
			*/
		},
		ajaxOptions: {
			error: function( xhr, status, index, anchor ) {
				//$( anchor.hash ).html("error occured while ajax loading.");
				$( anchor.hash ).html("Loading...");
				//$("#progress_bar_show").hide();
			},
			success: function( xhr, status ) {
				$("ul.tabs a").css('height', $("ul.tabs").height());
				$("ul.tabs a").css('overflow', 'hidden');
				//$("#progress_bar_show").hide();
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
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	
	var availableOpt = [
			'<?php echo implode("','", $option); ?>'
		];
	$( "#srcTxt" ).autocomplete({
		source: availableOpt
	},{minLength: 0});
	
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
	/*$("#loading_dialog").dialog({
		autoOpen: false,
		modal: true,
		title:'Please Wait',
		height: 'auto',
		width:'30%',
	});*/
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
			setTimeout('$("#alert_dialog").dialog("close")',5000);
		}
	});
	
	$("#confirm_alert_dialog").dialog({
		autoOpen: false,
		modal: true,
		title:'Confirm Box',
	});

	$("#distance_box_dialog").dialog({
		modal: true,
		bgiframe: true,
        width: 'auto',
        height: 'auto',
		title : '<?php echo $this->lang->line("Distance Calculation");  ?>',
		autoOpen: false,
	  	draggable: true,
		resizable: false,
		open : function(){
			//$("#distance_box_dialog").html(mapDivHidden);
			$("#dispMap").html("<img src='<?php echo base_url(); ?>assets/images/loading.gif' style='padding-top:46px'>");
			$.post("<?php echo base_url(); ?>index.php/home/getDist/id/"+selected_assets_ids,function(data){
			var start=new google.maps.LatLng(data.points[0].lat,
			data.points[0].lng);
			var end=new google.maps.LatLng(data.points[1].lat, data.points[1].lng);
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
				//alert(response.rows[0].elements[0].distance.text);
				var dist=(response.rows[0].elements[0].distance.value/1000).toFixed(2);
				var htm="<h3><?php echo $this->lang->line("Distance Between"); ?></h3> <div><div style='float:left'><img src='<?php echo base_url(); ?>assets/assets_photo/"+data.points[0].image+"' /> <h6>"+data.points[0].truck+"</h6></div><div style='float:left;margin:25px 10px 0px 10px'><img src='<?php echo base_url(); ?>assets/assets_photo/arrows.png'  /></div><div style='float:left'><img src='<?php echo base_url(); ?>assets/assets_photo/"+data.points[1].image+"' /><h6>"+data.points[1].truck+"</h6></div><div style='clear:both'> </div></div><h3>'"+dist+"'</h3>";
				var html="<span><?php echo $this->lang->line("Distance Between"); ?> </span><img src='<?php echo base_url(); ?>assets/assets_photo/"+data.points[0].image+"' /> <span>"+data.points[0].truck+"</span><span>And</span> <img src='<?php echo base_url(); ?>assets/assets_photo/"+data.points[1].image+"' /> <span> "+data.points[1].truck+"</span> <span>is</span> '"+dist+"'";
				$("#dispMap").html(htm);
				setTimeout(function(){
				$("#distance_box_dialog").dialog( "option", "position", 'center' );
				},280);
			 	}
			});
			
			},'json');
			
			
		}
	});
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
						$("#imgmaxmin").trigger("click");
						setTimeout(function(){
						window.print();
						},2000);
					}
					return false;
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
	<?php /* if(isset($openTab)){ ?>
		topMenuToTab('<?php echo $openTab['url']; ?>', '<?php echo $openTab['title']; ?>', 10);
		//$("#loading_dialog").dialog("close");
		$("#float-icons1").hide();
		$("#float-icons").hide();
		<?php if($openTab['cmd']=='Print'){ ?>
		setTimeout(function(){
			window.print();
		},5000);
		<?php } ?>
	<?php } */ ?>
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
	
});
function hideQTip()
{
	$(".deviceMain").qtip('hide');
}
function detail_list_a(val)
{
	$("#optdetail option").removeAttr('selected');
	$("#optdetail option[value='"+val+"']").attr('selected','selected');
	$("#optdetail").trigger("change");
}
function loadAssets(){
//$("#loading_dialog").dialog("open");
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
	var report = $('#optdetail').val();
	
	if($("#main #lastpoint_list_div").html())
	{
		//alert("txt:"+txt+", report:"+report+", limit:"+limit+", page:1" );
		jQuery("#lastpoint_grid").jqGrid('setGridParam',{postData:{ txt: txt, report:report, limit:limit, page:1 }}).trigger("reloadGrid");
		//$("#loading_dialog").dialog("close");
		$("#loading_top").css("display","none");
	}
	else
	{
		$("#main").html('<div id="load" style="padding-top:30%;height: 50%;" align="center"><img src="<?php echo base_url(); ?>assets/style/css/images/ajax.gif" alt="Loading"></div>'+$("#main").html());
		$.post(url, { txt: txt, report:report, limit:limit, page:page },
			function(data) {
			$("#main").html(data);
			//$("#loading_dialog").dialog("close");
			$("#loading_top").css("display","none");
		});
	}
}
function close_pop_up(val)
{
	$("#open_pop_up_"+val).remove();
}
var total_pop_up =0;
function open_pop_up(header,data,link,type)
{
	$.post('<?php echo base_url(); ?>index.php/home/popup_request', { header: header, data:data, link:link, type:type },
		function(data) {});
	var thissound=document.getElementById("sound_start");
	thissound.play();
	var html ='';
	html = "<div class='shadow popup_window ' id='open_pop_up_"+total_pop_up+"'><table width='100%'><tr style='border-bottom: 1px dotted;'><td>"+header+"<img src='<?php echo base_url(); ?>assets/upload_image/close.png' alt='close' style='height: 12px;cursor:pointer;float:right;padding:4px' onclick='close_pop_up("+total_pop_up+")'/></td></tr><tR><td><img src='<?php echo base_url(); ?>/assets/";
	
	if(type=='error')
		html = html+"Error-8.png"; 
	else
		html = html + "alert-8.png";
	
	html = html+ "'style='float: left;padding-right:4px' >"+data+" <span style='float:right'>";
	if(link!=null && link != "")
		html = html+ "<a  onclick='topMenuToTab(\""+link+"\",\"From Alert\",\"from_login\")' href='JavaScript:void(0); ' style='color:#ffffff'>&nbsp;<?php echo $this->lang->line("more"); ?></a>";
//	topMenuToTab(url, name, id);
	html = html+ "</span></td></tr></table></div>";
	$("#popup").append(html);

	total_pop_up = total_pop_up+1
	return "open_pop_up_"+(total_pop_up-1);
}
function loadAssetsList(){
	//$("#loading_dialog").dialog("open");
	$("#loading_top").css("display","block");
	$("#select_assest_list_view").attr('src','<?php echo base_url(); ?>assets/dashboard/images/selectgridview.png');
	$("#select_assest_thumb_view").attr('src','<?php echo base_url(); ?>assets/dashboard/images/thumbnail_view.png');
		$("#select_assest_grid_view").attr('src','<?php echo base_url(); ?>assets/dashboard/images/listview.png');
	var txt = $('#srcTxt').val();
	if(txt == "Search Assets..."){
		txt = "";
	}
	var report = $('#optdetail').val();
	limit = 40;
	url = "<?php echo base_url(); ?>index.php/home/assets_list";
	
		$("#main").html('<div id="load" style="padding-top:30%;height: 50%;" align="center"><img src="<?php echo base_url(); ?>assets/style/css/images/ajax.gif" alt="Loading"></div>'+$("#main").html());
		$.post(url, { txt: txt, report:report, limit:limit, page:1 },
			function(data) {
			if(loadCancel==1)
			{
				$("#main").html(data);
				getSelectChkd();
				//$("#loading_dialog").dialog("close");
				$("#loading_top").css("display","none");
			}
			loadCancel=1;
		});
	
	
	//loadAssets();
}
function loadAssetsThumb(){
//$("#loading_dialog").dialog("open");
$("#loading_top").css("display","block");
$("#select_assest_list_view").attr('src','<?php echo base_url(); ?>assets/dashboard/images/gridview.png');
$("#select_assest_thumb_view").attr('src','<?php echo base_url(); ?>assets/dashboard/images/selectthumbnail_view.png');
	$("#select_assest_grid_view").attr('src','<?php echo base_url(); ?>assets/dashboard/images/listview.png');
	limit = 8;
	url = "<?php echo base_url(); ?>index.php/home/assets";
	
	//loadAssets();
	var txt = $('#srcTxt').val();
	if(txt == "Search Assets..."){
		txt = "";
	}
	var report = $('#optdetail').val();
	//limit = 40;
//	url = "<?php echo base_url(); ?>index.php/home/assets_list";
	
		$("#main").html('<div id="load" style="padding-top:30%;height: 50%;" align="center"><img src="<?php echo base_url(); ?>assets/style/css/images/ajax.gif" alt="Loading"></div>'+$("#main").html());
		$.post(url, { txt: txt, report:report, limit:limit, page:1 },
			function(data) {
			$("#main").html(data);
			getSelectChkd();
			//$("#loading_dialog").dialog("close");
			$("#loading_top").css("display","none");
		});
}
function loadAssetsGrid(){
loadJGrid();
//$("#loading_dialog").dialog("open");
$("#loading_top").css("display","block");
	$("#select_assest_list_view").attr('src','<?php echo base_url(); ?>assets/dashboard/images/gridview.png');
	$("#select_assest_thumb_view").attr('src','<?php echo base_url(); ?>assets/dashboard/images/thumbnail_view.png');
	$("#select_assest_grid_view").attr('src','<?php echo base_url(); ?>assets/dashboard/images/selectlistview.png');
	var txt = $('#srcTxt').val();
	if(txt == "Search Assets..."){
		txt = "";
	}
	var report = $('#optdetail').val();
	
	url = "<?php echo base_url(); ?>index.php/reports/lastpoint";
	$("#main").html('<div id="load" style="padding-top:30%;height: 50%;" align="center"><img src="<?php echo base_url(); ?>assets/style/css/images/ajax.gif" alt="Loading"></div>'+$("#main").html());
	var assetss=selected_assets_ids.split(",");
	$.post(url, { txt: txt, report:report, paging:grid_paging},
	 function(data) {
		$("#main").html(data);
		//$("#loading_dialog").dialog("close");
		$("#loading_top").css("display","none");
	});
	
}
function changeLimit(lmt){
	page = 1;
	limit = lmt;
	loadAssets();
}
function changeReport(){
	page = 1;
	//alert($("#optdetail").val());
	$("#assets_running").css("text-decoration","none");
	$("#assets_out").css("text-decoration","none");
	$("#assets_fault").css("text-decoration","none");
	$("#assets_total").css("text-decoration","none");
	$("#assets_parked").css("text-decoration","none");
	if($("#optdetail").val()=='running')
		$("#assets_running").css("text-decoration","underline");
	else if($("#optdetail").val()=='out_of_network')
		$("#assets_out").css("text-decoration","underline");
	else if($("#optdetail").val()=='device_fault')
		$("#assets_fault").css("text-decoration","underline");
	else if($("#optdetail").val()=='')
		$("#assets_total").css("text-decoration","underline");
	else if($("#optdetail").val()=='parked')
		$("#assets_parked").css("text-decoration","underline");
		
	loadAssets();
}
function searchAssets(){
	page = 1;
	loadAssets();
	return false;
}
function changePage(pg){
	page = pg;
	loadAssets();
}
function reloadAssets(){
	//$("#loading_dialog").dialog("open");
	$("#loading_top").css("display","block");
	var txt = $('#srcTxt').val();
	if(txt == "<?php echo $this->lang->line("Search Assets"); ?>"){
		txt = "";
	}
	var report = $('#optdetail').val();
	$.post(url, { txt: txt, report:report, limit:limit, page:page },
	 function(data) {
		//$("#loading_dialog").dialog("close");
		$("#loading_top").css("display","none");
		$("#main").html(data);
		if(timer_on==1)
		{
			$("#seconds").html($("#time_in_seconds").val());
			counter();
		}
	});
}
function submitFormUsers_dash(){
		//$("#loading_dialog").dialog("open");
		$("#loading_top").css("display","block");
		var selectAssets_cmb =  $("#cmb_assets option:selected").map(
	function () {return this.value;}).get().join(",");
		var bValid = true;
		var str="/cmd/";
		$("#assets_ids").val(selectAssets_cmb);
		AllFieldsUser.removeClass("ui-state-error");
			if(add_to_user.children("option").length!=0)
		{
		bValid = bValid && checkNull(add_to_user,"<?php echo $this->lang->line("Select User"); ?>");
		}
		if($("#create_u_div").css("display") =="none")
		{
			bValid = bValid && checkNull(username,"<?php echo $this->lang->line("username"); ?>");
			bValid = bValid && checkNull(first_name,"<?php echo $this->lang->line("First_Name"); ?>");
			bValid = bValid && checkNull(last_name,"<?php echo $this->lang->line("Last_Name"); ?>");
			if($("#u_id").val()=="")
				bValid = bValid && checkNull(password,"<?php echo $this->lang->line("password"); ?>");
			str+="add";
		}
		else
		{
			str+="update";
		}
		if (bValid) {
			$.post("<?php echo base_url(); ?>index.php/home/save_user"+str, $("#frm_users").serialize(),
			 function(data) {
			 //$("#loading_dialog").dialog("close");
			 $("#loading_top").css("display","none");
				if(data.result == "true"){
					$("#u_id").val("");
					$("#pass_u_id").css("display","inline");
					if($("#create_u_div").css("display") =="none"){
						$('#user_dialog').dialog('close');
							//$('#user_dialog').dialog('open');
						user_dialog_opn();
					}
					else
					{
						$('#user_dialog').dialog('close');
					}
					$("#alert_dialog").html(data.msg);
					$("#alert_dialog").dialog("open");	
					$("user_dialog").dialog({ modal: true });
				}
				else{
					updateTips(data.msg);
					AllFieldsUser.removeClass('ui-state-error');
				}
			}, "json");
		}
	return false
}
function selectedAssets() {         
	
	selected_assets_ids =  $("#main input:checked").map(
	function () {return this.value;}).get().join(",");
	//$("#assets_ids").val(selected_assets_ids);
	//$("#float-icons a[title='Add To Group']").css("opacity0.2");
	var totalSelected = selected_assets_ids.split(",");

	if(selected_assets_ids.length>0)
	{
		//$("#float-icons a").removeClass("float-icon-disabled");
		//$("#float-icons a").addClass("float-icon-enabled");
		$("#float-icons").show();
		
	}
	else
	{
		//$("#float-icons a").removeClass("float-icon-enabled");
		//$("#float-icons a").addClass("float-icon-disabled");
		$("#float-icons").hide();
	}
	
	if(totalSelected.length == 2){
		var top =$('#main input:checked[value="'+totalSelected[totalSelected.length-1]+'"]').position().top + 15 + $("#tabs").scrollTop();
		var left= $('#main input:checked[value="'+totalSelected[totalSelected.length-1]+'"]').position().left + 15 + $("#tabs").scrollLeft();

		$("#distanceBtn_div").css({top: top ,left: left, position: 'absolute', height:"50px", border:"1px solid lightblue",borderRadius:"4px"});
		$('#distanceBtn_div').fadeIn();
	}else{
		$('#distanceBtn_div').fadeOut();
	}
  }
function directTab(id, ast_id){
	$(".deviceMain").qtip('hide');
	//$("#loading_dialog").dialog("open");
	$("#loading_top").css("display","block");
	var nameToCheck = assetNameArray[ast_id];
	var tabNameExists = false;
	
	$('#tabs ul.ui-tabs-nav li a').each(function(i) {
		if (this.text == nameToCheck) {
			tabNameExists = true;
			$('#tabs').tabs('select', $(this).attr("href"));
			return false;
		}
	});
	if (!tabNameExists){
		$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/live/device/window/current/id/"+id, assetNameArray[ast_id]);	
	} 
/*
	var inarray = false;
	var selectTab;
	for(i=0; i<tabArrKey.length; i++){		
		  if(tabArrKey[i] == assetDeviceArray[ast_id]){
			inarray = true;
			selectTab = tabArrValue[i];
		  }
	}
	tab_id = assetDeviceArray[ast_id];
	if(inarray == true){
		$('#tabs').tabs('select', '#' + selectTab);
	}else{
		$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/live/device/window/current/id/"+id, assetNameArray[ast_id]);		
		return false;
	}
	return false;
	*/
}
function historyTab(url, name, device_id){
	
	/*var inarray = false;
	var selectTab;
	for(i=0; i<tabArrKey.length; i++){		
		  if(tabArrKey[i] == device_id){
			inarray = true;
			selectTab = tabArrValue[i];
		  }
	}
	tab_id = device_id;
	alert(tab_id)
	if(inarray == true){
		$('#tabs').tabs('select', '#' + selectTab);
	}else{
		$('#tabs').tabs('add', url, name);		
		return false;
	}
	return false;
	*/
	var nameToCheck = name;
	var tabNameExists = false;
	
	$('#tabs ul.ui-tabs-nav li a').each(function(i) {
		
		if (this.text == nameToCheck) {
			
			tabNameExists = true;
			$('#tabs').tabs('select', $(this).attr("href"));
			return false;
		}
	});
	
	if (!tabNameExists){
		$('#tabs').tabs('add', url, name);
	} 
}
function newTab(){
/*	var gsr = jQuery("#lastpoint_grid").jqGrid("getGridParam","selarrrow");
	if(gsr != null && gsr != "" && gsr != undefined)
	{
		var allpoint = "";
		for(i=0;i<gsr.length;i++)
		{
				var gsrval = jQuery("#lastpoint_grid").jqGrid('getCell', gsr[i], 'assets_id');		
				if(i == gsr.length-1)
				{
					allpoint +=gsrval;
				}else{
					allpoint +=gsrval+",";
				}
		}
		$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/home/map/id/"+allpoint, 'Map');
	} else*/
	if(selected_assets_ids != undefined && selected_assets_ids != '') 
	{
		
		var selectedAst = selected_assets_ids.split(",");
		$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/home/map/id/"+selected_assets_ids, 'Map');
		
	}   
	
	return false; 
}
function getAssetsDistance(){
	//$('#distanceBtn_div').hide();
//	$("#distance_box_dialog").html("Distance Will be displayed Soon");
	$("#distance_box_dialog").dialog('option', 'buttons', {
			"View On Map" : function() {
			var selectedAst = selected_assets_ids.split(",");
			$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/home/map/id/"+selected_assets_ids+"/d/1/cmd/dist", 'Map');
			$(this).dialog("close");
				},
			"Cancel" : function() {
			$(this).dialog("close");
				}
			});
	$("#distance_box_dialog").dialog("open");
	return false;
}
function loadAssetsDash_tt(id,name){
	$(".deviceMain").qtip('hide');
	//$("#loading_dialog").dialog("open");
	$("#loading_top").css("display","block");
	$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/home/assets_dash/id/"+id, name+" Details");
}
function loadAssetsDash(){
	if(selected_assets_ids != undefined)
	{	
	var selectedAst = selected_assets_ids.split(",");
	
	if(selectedAst.length > 1){
		$("#alert_dialog").html("<?php echo $this->lang->line("Please_Select_Only_One_Asset"); ?>");
		$("#alert_dialog").dialog("open");
		return false;
	}else{	
		var nameToCheck = assetNameArray[selected_assets_ids]+" Details";
		
		var tabNameExists = false;
		
		$('#tabs ul.ui-tabs-nav li a').each(function(i) {
		
			if (this.text == nameToCheck) {
				tabNameExists = true;
				$('#tabs').tabs('select', $(this).attr("href"));
				return false;
			}
		});
		if (!tabNameExists){
			if(assetDeviceArray[selected_assets_ids] != undefined)
			{
				$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/home/assets_dash/id/"+assetDeviceArray[selected_assets_ids], assetNameArray[selected_assets_ids]+" Details");
			}
			
		} 
		/*insertedTabCheck = true;
		var inarray = false;
		var selectTab;
		for(i=0; i<tabArrKey.length; i++){		
			  if(tabArrKey[i] == selected_assets_ids){
				inarray = true;
				selectTab = tabArrValue[i];
			  }
		}
		tab_id = selected_assets_ids;
		if(inarray == true){
			$('#tabs').tabs('select', '#' + selectTab);
		}else{
			$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/home/assets_dash/id/"+assetDeviceArray[selected_assets_ids], assetNameArray[selected_assets_ids]);
			//$('#tabs').tabs('add', "<?php echo base_url(); ?>index.php/home/dash", assetNameArray[selected_assets_ids], 1);
			
			return false;
		}
		*/
		}
	}
	else
	{
		$("#alert_dialog").html("<?php echo $this->lang->line("No_Assets_Selected"); ?>");
		$("#alert_dialog").dialog("open");
	}
}
function hideSubMenu_all(){
	$('.potato-menu-item').each(function(index){
		$(this).css('padding-bottom', '0px');
	});
	$('.potato-menu-item ul').css('display', 'none');
	if($("#float-icons").css('display') != 'none'){
		$("#float-icons1").hide();
		$("#float-icons").hide();
	}
	myLayout.resizeAll();
}
//menu function
function hideSubMenu(url, name, id){
	$('.potato-menu-item').each(function(index){
		$(this).css('padding-bottom', '0px');
	});
	$('.potato-menu-item ul').css('display', 'none');
	topMenuToTab(url, name, id);
}

function topMenuToTab(url, name, id){
	if(id == "Home"){
		if(selected_assets_ids.length>0)
		{
			$("#float-icons").fadeIn(500);
		}
		$('#tabs').tabs('select', '#tabs-1');
		myLayout.close( "west" )
		return false;
	}
	else{
	lastUrl=url;
	lastUrlName=name;
	}

	var nameToCheck = name;
	var tabNameExists = false;
	if (tabNameExists == true){
		//$("#loading_dialog").dialog("close");
		$("#loading_top").css("display","none");
	} 
	$('#tabs ul.ui-tabs-nav li a').each(function(i) {
	
		if (this.text == nameToCheck) {
			tabNameExists = true;
			$('#tabs').tabs('select', $(this).attr("href"));
			return false;
		}
	});
	
	if (!tabNameExists){
		//$("#loading_dialog").dialog("open");
		$("#loading_top").css("display","block");
		$('#tabs').tabs('add', url, name);
	}

}

/////////////////
/*
function hideShowFixedIcon(){
	
	if($("#float-icons").css('display') == 'none'){
		$("#float-icons").fadeIn(500);
		$("#float-icons1 span").attr('class','float-open');
	}else{
		$("#float-icons").fadeOut(500);
		$("#float-icons1 span").attr('class','float-close');
	}
	
}*/





</script>

<script type="text/javascript" charset="utf-8">
var markersmap  = [];
var sidebar_htmlmap  = '';
var marker_htmlmap  = [];
var to_htmlsmap  = [];
var from_htmlsmap  = [];
var polylinesmap = [];
var polylineCoordsmap = [];
var mapmap = null;
var mapOptionsmap;

var polyVarr = [];
var labelArr = [];
var mbounds;

var dArr = [];
var directionsDisplay;
var selectedGroup = '';

var rendererOptions = {
					preserveViewport: true,
					draggable: false,
					suppressMarkers: true,
					polylineOptions: {
					   map: mapmap,
					   strokeColor:'#FF0000',
					   //strokeWidth: 3,
					   strokeOpacity: 0.7}
			};
			


function clearSelection(){
	if (markers) {
		for (i in markers) {
		  markers[i].setMap(null);
		  path.removeAt(i);
		  markers.splice(i, 1);
		}
	  }
}

function calcRoute(s1, e1, mapmap){		
		directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
		directionsDisplay.setMap(mapmap);
		var request = {
			origin:s1, 
			destination:e1,
			travelMode: google.maps.DirectionsTravelMode.DRIVING
		};
		directionsService.route(request, function(response, status) 
		{
			if (status == google.maps.DirectionsStatus.OK) 
			{
				directionsDisplay.setDirections(response);
				distance = (response.routes[0].legs[0].distance.value)/1000;
				distance = distance.toFixed(2);
				var myTextDiv = document.createElement('div');
				//myTextDiv.id = 'my_text_div';
				myTextDiv.innerHTML = '<h2>'+distance + ' KM</h2>';
				myTextDiv.style.color = 'white';
				mapmap.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(myTextDiv);
			}
		});	
  }
function createMarker(map, point, title, html, icon, icon_shadow, sidebar_id, openers, openInfo){
	
	var marker_options = {
		position: point,
		map: map,
		title: title};  
	if(icon!=''){marker_options.icon = "<?php echo base_url(); ?>assets/marker-images/" + icon;}
	if(icon_shadow!=''){marker_options.icon_shadow = "<?php echo base_url(); ?>assets/marker-images/" + icon_shadow;}
	//create marker
	var new_marker = new google.maps.Marker(marker_options);
	if(html!=''){
		
		/*
		// Commented By Kunal.
		
		var infowindow = new google.maps.InfoWindow();
		infowindow.setContent(html);
		*/
		
		var infoBubble = new InfoBubble({
          map: map,
		  content:html,
          shadowStyle: 1,
          arrowSize: 10,
          disableAutoPan: true,
          arrowPosition: 30,
          arrowStyle: 2,
		  minWidth : 200
        });
		//infoBubble.open(map, new_marker);
		google.maps.event.addListener(new_marker, 'click', function() {
			update_timeout = setTimeout(function(){
				if (!infoBubble.isOpen()) {
					//infoBubble.setContent(html);
					infoBubble.open(map, new_marker);
				}
			}, 200);
		/*	
		// Commented By Kunal
		  update_timeout = setTimeout(function(){
				infowindow.open(map,new_marker);
			}, 200); 
		*/			
		});
		google.maps.event.addListener(new_marker, 'dblclick', function() {
			dArr.push(point);
			  if(dArr.length == 2){
					calcRoute(dArr[0], dArr[1], map);
					dArr = [];
					
			  }
			  if(dArr.length == 1 && directionsDisplay != undefined){
				clearDirection();
				}
			 clearTimeout(update_timeout);
		});
		
		if(openInfo == true) {
			//setTimeout(function(){
				
				//infoBubble.setContent(html);
				setTimeout(function(){
				infoBubble.open(map, new_marker);
				},1000);
			//}, 500);
		}
		
		if(openers != ''&&!isEmpty(openers)){
		   for(var i in openers){
			 var opener = document.getElementById(openers[i]);
			 opener.onclick = function(){infoBubble.open(map,new_marker); return false};
		   }
		}
		
		
		if(sidebar_id != ''){
			var sidebar = document.getElementById(sidebar_id);
			if(sidebar!=null && sidebar!=undefined && title!=null && title!=''){
				var newlink = document.createElement('a');
				
				newlink.onclick=function(){infoBubble.open(map,new_marker); return false};
				
				newlink.innerHTML = title;
				sidebar.appendChild(newlink);
			}
		}
	}
	return new_marker;  
}

function viewLocation(lat, lng, html){
	clearOverlaysMain();
	var point = new google.maps.LatLng(lat, lng);
	var text = "<div style='font-size:12px;height:130px;width:200px;'> " + html[i] + "</div>";
	markersmap.push(createMarker(mapmap, point,"Marker Description",text, '', '', "sidebar_map", '' ));
	
	mapmap.setCenter(point);
	$('#tabs').tabs('select', 0);

}
function viewTrack(lat, lng, html){
	clearOverlaysMain();
	for(i=0; i<lat.length; i++){
		var point = new google.maps.LatLng(lat[i], lng[i]);
		var image = '';
		var shadow = new google.maps.MarkerImage("<?php echo base_url(); ?>assets/marker-images/shadow50.png", new google.maps.Size(37, 34));
		if(i == 0){	
			var img = '<?php echo base_url(); ?>assets/marker-images/BLUE-START.png';
			image = new google.maps.MarkerImage(img, new google.maps.Size(20, 34), new google.maps.Point(0,0), new google.maps.Point(0, 34));
		}
		else if(i == (lat.length-1)){
			var img = '<?php echo base_url(); ?>assets/marker-images/BLUE-END.png';
			image = new google.maps.MarkerImage(img, new google.maps.Size(20, 34), new google.maps.Point(0,0), new google.maps.Point(0, 34));
		}
		else{
			var p1 = new google.maps.LatLng(lat[i-1], lng[i-1]);
			var p2 = new google.maps.LatLng(lat[i], lng[i]);
			var dir = bearing(p2, p1 );
			var dir = Math.round(dir/3) * 3;
			while (dir >= 120) {dir -= 120;}
			
			var img = "http://www.google.com/intl/en_ALL/mapfiles/dir_"+dir+".png";
			shadow = new google.maps.MarkerImage("<?php echo base_url(); ?>assets/marker-images/shadow50.png", new google.maps.Size(1, 1));
			
			image = new google.maps.MarkerImage(img, new google.maps.Size(24,24), new google.maps.Point(0,0), new google.maps.Point(10,10));
		}
		markersmap.push(createMarker(mapmap, point,"Marker Description",html[i], img, shadow, "sidebar_map", '' ));
				
		if(i > 0){
			polylineCoordsmap[i-1] = [
				new google.maps.LatLng(lat[i-1], lng[i-1])
			,
				new google.maps.LatLng(lat[i], lng[i])
			];    	
			polylinesmap[i-1] = new google.maps.Polyline({
			  path: polylineCoordsmap[i-1]
			  , strokeColor: '#FF0000'
			  , strokeOpacity: 1.0
			  , strokeWeight: 2
			});
			polylinesmap[i-1].setMap(mapmap);
		}
  	}
	mapmap.setCenter(point);
	$('#tabs').tabs('select', 0);

}
function clearDirection(){
	for(i=0; i< (mapmap.controls[google.maps.ControlPosition.BOTTOM_CENTER].length); i++){
		mapmap.controls[google.maps.ControlPosition.BOTTOM_CENTER].removeAt(i);
	}
	directionsDisplay.setMap(null);
}
function clearOverlaysMain() {
  if(directionsDisplay != undefined){
	clearDirection();
  }
  if (markersmap) {
    for (i in markersmap) {
      markersmap[i].setMap(null);
    }
  }
  if (polylinesmap) {
    for (i in polylinesmap) {
      polylinesmap[i].setMap(null);
    }
  }
  markersmap = [];
  polylinesmap = [];
}
var degreesPerRadian = 180.0 / Math.PI;
function bearing( from, to ) {
	// Convert to radians.
	var lat1 = from.lat();
	var lon1 = from.lng();
	var lat2 = to.lat();
	var lon2 = to.lng();
   
   var angle = - Math.atan2( Math.sin( lon1 - lon2 ) * Math.cos( lat2 ), Math.cos( lat1 ) * Math.sin( lat2 ) - Math.sin( lat1 ) * Math.cos( lat2 ) * Math.cos( lon1 - lon2 ) );
	if ( angle < 0.0 )
		angle  += Math.PI * 2.0;
	// And convert result to degrees.
	angle = angle * degreesPerRadian;
	angle = angle.toFixed(1);
	return angle;
}
function profile_div_close(){
	$("#Model_main").hide();
	$("#model").hide();	
}

function profile_div(){
	$("#Model_main").show();
	$("#model").show();
	$('#profile').hide();	
}	
function user_change(val,id){
	//$("#loading_dialog").dialog("open");
	$("#loading_top").css("display","block");
	if(val!="")
	{
		$.post("<?php echo base_url(); ?>/index.php/home/get_assets_selecteds/usr_id/"+val+"/assets_ids/"+selected_assets_ids,function(result)
		{
			//$("#loading_dialog").dialog("close");	
			$("#loading_top").css("display","none");
			if(result!=""){
				$("#cmb_assets").html(result);
			}
			else
			{
				alert(result);
			}
		});
	}

}
function setLanguage(lang){
	$.post(
		"<?php echo base_url(); ?>/index.php/home/setLanguage",{'lang':lang},
		function(result)
		{
			window.location.reload();
		}
	);
}
function changeGroupCombo(val){
	//$("#loading_dialog").dialog("open");
	$("#loading_top").css("display","block");
	if(val!="")
	{
		$.post("<?php echo base_url(); ?>/index.php/home/get_assets_selecteds_grp/grp_id/"+val+"/assets_ids/"+selected_assets_ids,function(result)
		{
		//$("#loading_dialog").dialog("close");
		$("#loading_top").css("display","none");
			if(result!=""){
				$("#cmb_assets_grp").html(result);
				//$("#add_to_user").html(result.user_combo);
			}
		});
	}
}
function subMenu(id){
	/*$(".submenu").hide();
	$("#"+id).show();
	*/
	$(".ui-layout-west").html($('#'+id).html())
	myLayout.open( "west" );
	
	$("#layout1").trigger("click");
}
function print_window()
{
	window.print();
} 
function maximize(obj){
	if(obj.alt=="max"){
		$.each('north,west,south'.split(','), function(){myLayout.close(this);});
		$(obj).attr("alt", 'min');
		$(obj).attr("title", '<?php echo $this->lang->line("Minimize"); ?>');
		$(obj).attr("src", '<?php echo base_url(); ?>assets/style/img/icons/window_no_full_screen.png');
	}
	else{
		$.each('north,south'.split(','), function(){myLayout.open(this);});
		$(obj).attr("alt", 'max');
		$(obj).attr("title", '<?php echo $this->lang->line("Maximize"); ?>');
		$(obj).attr("src", '<?php echo base_url(); ?>assets/style/img/icons/window_full_screen.png');
	}
}
function show_more_div(val) {
	$('#more_div').css('left',$(val).position().left).slideDown();
}
function closeDist()
{
	$('#distanceBtn_div').fadeOut();
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
function user_dialog_opn()
{
	//$("#loading_dialog").dialog("close");
	$("#loading_top").css("display","none");
	$('#user_dialog').dialog('open');
	$(".user_add_table").hide();
	$("#create_u_div").show();
	$("#usr_combo").show();
	$("#usr_asset_combo").show();
	fillUsrs();
}
function fillUsrs()
{
	//$("#loading_dialog").dialog("open");
	$("#loading_top").css("display","block");
	$.post("<?php echo base_url(); ?>/index.php/home/get_usrs/assets_ids/"+selected_assets_ids,function(result)
	{
		//$("#loading_dialog").dialog("close");
		$("#loading_top").css("display","none");
		if(result.result == "done"){
			if(result.user_combo!="")
			{
				$("#add_to_user").html(result.user_combo);
				$("#add_to_user").trigger("change");
			}
			else
			{
				//$('#user_dialog').dialog('open');
				$("#add_to_user").html('');
				$("#user_error").html('There is No Users Exist');
				$("#user_error").addClass('ui-state-highlight');
				setTimeout(function() {
					tips.removeClass('ui-state-highlight', 1500);
				}, 500);
				$(".user_add_table").show();
				$("#create_u_div").hide();
				$("#usr_combo").hide();
				$("#usr_asset_combo").hide();
			}
		}
	},'json');	
}
function selectedAssets_Combo() {         
	
	selected_assets_ids =  $("#cmb_assets option:selected").map(
	function () {return this.value;}).get().join(",");
	getSelectChkd();
	//$("#assets_ids").val(selected_assets_ids);
	//$("#float-icons a[title='Add To Group']").css("opacity0.2");
	//var totalSelected = selected_assets_ids.split(",");

	if(selected_assets_ids.length>0)
	{
		//$("#float-icons a").removeClass("float-icon-disabled");
		//$("#float-icons a").addClass("float-icon-enabled");
		$("#float-icons").show();	
	}
	else
	{
		//$("#float-icons a").removeClass("float-icon-enabled");
		//$("#float-icons a").addClass("float-icon-disabled");
		$("#float-icons").hide();
	}
	
	/*if(totalSelected.length == 2){
		var top =$('#main input:checked[value="'+totalSelected[totalSelected.length-1]+'"]').position().top + 15 + $("#tabs").scrollTop();
		var left= $('#main input:checked[value="'+totalSelected[totalSelected.length-1]+'"]').position().left + 15 + $("#tabs").scrollLeft();

		$("#distanceBtn_div").css({top: top ,left: left, position: 'absolute', height:"50px", border:"1px solid lightblue",borderRadius:"4px"});
		$('#distanceBtn_div').fadeIn();
	}else{
		$('#distanceBtn_div').fadeOut();
	}*/
  }
 function selectedAssets_Combo_group() {         
	
	selected_assets_ids =  $("#cmb_assets_grp option:selected").map(
	function () {return this.value;}).get().join(",");
	getSelectChkd();
	//$("#assets_ids").val(selected_assets_ids);
	//$("#float-icons a[title='Add To Group']").css("opacity0.2");
	//var totalSelected = selected_assets_ids.split(",");

	if(selected_assets_ids.length>0)
	{
		//$("#float-icons a").removeClass("float-icon-disabled");
		//$("#float-icons a").addClass("float-icon-enabled");
		$("#float-icons").show();
	}
	else
	{
		//$("#float-icons a").removeClass("float-icon-enabled");
		//$("#float-icons a").addClass("float-icon-disabled");
		$("#float-icons").hide();
	}
	
	/*if(totalSelected.length == 2){
		var top =$('#main input:checked[value="'+totalSelected[totalSelected.length-1]+'"]').position().top + 15 + $("#tabs").scrollTop();
		var left= $('#main input:checked[value="'+totalSelected[totalSelected.length-1]+'"]').position().left + 15 + $("#tabs").scrollLeft();

		$("#distanceBtn_div").css({top: top ,left: left, position: 'absolute', height:"50px", border:"1px solid lightblue",borderRadius:"4px"});
		$('#distanceBtn_div').fadeIn();
	}else{
		$('#distanceBtn_div').fadeOut();
	}*/
  }

function getSelectChkd()
{
	//alert(selected_assets_ids.toSource());
	$("#main input:checkbox").attr("checked",false);
	if(selected_assets_ids!="" || selected_assets_ids!= undefined)
	{
		var assetss=selected_assets_ids.split(",");
		for(i=0;i<assetss.length;i++)
		{
			$("#main input:checkbox[value='"+assetss[i]+"']").attr("checked",true);
		}
	}
	
}

function getSelectJGridChkd()
{
	//alert(selected_assets_ids.toSource());
	$("#lastpoint_grid tr td input:checkbox").attr("checked",false);
	if(selected_assets_ids!="" || selected_assets_ids!= undefined)
	{
		var assetss=selected_assets_ids.split(",");
		for(i=0;i<assetss.length;i++)
		{
			$("#lastpoint_grid tr td input:checkbox[value='"+assetss[i]+"']").attr("checked",true);
		}
	}
}
function open_group_dialog()
{
	//$("#loading_dialog").dialog("close");
	$("#loading_top").css("display","none");
	$("#new_group").val("");
	$("#edit_group_id").val("");
	$("#tips_grp").text("");
	$("#new_group").removeClass('ui-state-error');
	$("#create_g_div").show();
	$("#new_group_tr").hide();
	$("#group_list_combo").show();
	$("#assets_combo_grp").show();
	$('#group_dialog').dialog('open');
	fillGroups();
}

function addToGroup(){
	//$("#loading_dialog").dialog("open");
	$("#loading_top").css("display","block");
	str="/cmd/";
	gp="";
		
		var selectAssets_cmb_grp =  $("#cmb_assets_grp option:selected").map(
	function () {return this.value;}).get().join(",");
	
		if(selectAssets_cmb_grp=="")
			selectAssets_cmb_grp=0;
		if(($("#group_list_combo").css("display") =="none") && ($("#edit_group_id").val()=="" )){
			var newgp = $("#new_group").val();
			if(newgp == ""){
				//$("#loading_dialog").dialog("close");
				$("#loading_top").css("display","none");
				$("#alert_dialog").html("<?php echo $this->lang->line("Group Name Blank Not Allowed"); ?>");
				$("#alert_dialog").dialog("open");
			return false;
			}
			str+="new";
			gp="new";
		}else if(($("#group_list_combo").css("display") =="none") && ($("#edit_group_id").val()!="")){
			var newgp = $("#new_group").val();
			if(newgp == ""){
				//$("#loading_dialog").dialog("close");
				$("#loading_top").css("display","none");
				//$("#alert_dialog").html("<?php echo $this->lang->line("Group Name Blank Not Allowed"); ?>");
				$("#tips_grp").html("<?php echo $this->lang->line("Group Name Blank Not Allowed"); ?>");
				$("#tips_grp").addClass('ui-state-highlight');
				setTimeout(function() {
					tips.removeClass('ui-state-highlight', 1500);
				}, 500);
				$("#alert_dialog").dialog("open");
			return false;
			}
			gp=$("#group_combo").val();
			str+="edit";
		}
		else
		{
			if(selectAssets_cmb_grp == '' || selectAssets_cmb_grp == 0){
				//$("#loading_dialog").dialog("close");
				$("#loading_top").css("display","none");
			return false;
			}
			gp=$("#group_combo").val();
			str+="update";
		}
		$.post("<?php echo base_url(); ?>index.php/home/add_to_group/assets/"+selectAssets_cmb_grp+str+"/group/"+gp+"/newgp/"+newgp,
			 function(data) {	
			 //$("#loading_dialog").dialog("close");
			 $("#loading_top").css("display","none");
				if(data.result == "true"){
					$("#new_group").val("");
					$("#edit_group_id").val("");
					if($("#group_list_combo").css("display") =="none"){
						
						$('#group_dialog').dialog('close');
						//$('#group_dialog').dialog('open');
						open_group_dialog();
					}
					else
					{
						$('#group_dialog').dialog('close');
					}
					$("#alert_dialog").html(data.msg);
					$("#alert_dialog").dialog("open");	
					$("#tips_grp").text("");
					$("#new_group").removeClass('ui-state-error');
					$("group_dialog" ).dialog({ modal: true });
				}	
				else
				{	
					updateTipsErr(data.msg,$("#tips_grp"));
					//AllFieldsUser.removeClass('ui-state-error');
					$("#new_group").addClass('ui-state-error');
					//$("#alert_dialog").html(data.msg);
					//$("#alert_dialog").dialog("open");	
				}
				
			}, "json");
	
}
function fillGroups()
{
	//$("#loading_dialog").dialog("open");
	$("#loading_top").css("display","block");
	$.post("<?php echo base_url(); ?>/index.php/home/get_grps/assets_ids/"+selected_assets_ids,function(result)
	{
		//$("#loading_dialog").dialog("close");
		$("#loading_top").css("display","none");
		if(result.result == "done"){
			if(result.user_combo!="")
			{
				$("#group_combo").html(result.user_combo);
				$("#group_combo").trigger("change");
			}
			else
			{
				$("#group_combo").html('');
				$("#create_g_div").hide();
				$("#new_group_tr").show();
				$("#group_list_combo").hide();
				$("#assets_combo_grp").hide();
			}
		}
	},'json');	
}
function group_dialog_opn(val)
{
	//$("#loading_dialog").dialog("open");
	$("#loading_top").css("display","block");
	if(selected_assets_ids.length>0 && val==0)
	{
		user_dialog_opn();
	}
	else if(selected_assets_ids.length>0 && val==1)
	{
		open_group_dialog();
	}
	else if(selected_assets_ids.length>0 && val==2)
	{
		newTab();
	}
	else if(selected_assets_ids.length>0 && val==3)
	{
		loadAssetsDash();
	}
	
	
}
function create_usr_func()
{
	$("#user_dialog").dialog('option', 'title', '<?php echo $this->lang->line('Create_Users'); ?>');
	//$("#loading_dialog").dialog("open");
	$("#loading_top").css("display","block");
	$("#user_error").html("");
	$("#u_id").val("");
	$("#pass_u_id").css("display","inline");
	$("#username").val("");
	$("#password").val("");
	$("#from_date").val("<?php echo date('d.m.Y H:i'); ?>");
	$("#to_date").val("<?php echo date('d.m.Y H:i',strtotime(date('d.m.Y H:i').'+2 days')); ?>");
	$("#first_name").val("");
	$("#last_name").val("");
	$("#mobile_number").val("");
	$("#email_address").val("");
	$("#email_alert").attr("checked","checked");
	$("#sms_alert").attr("checked","checked");
	$(".user_add_table").show();
	$("#create_u_div").hide();
	$("#usr_combo").hide();
	$("#usr_asset_combo").hide();
	//$("#loading_dialog").dialog("close");
	$("#loading_top").css("display","none");
}
function edit_usr_func()
{
	$("#user_dialog").dialog('option', 'title', '<?php echo $this->lang->line('Update_Userss'); ?>');
	var val=$("#add_to_user").val();
	//$("#loading_dialog").dialog("open");
	$("#loading_top").css("display","block");
	$.post("<?php echo base_url(); ?>/index.php/home/get_usrs_details/uid/"+val,function(data){
		//$("#loading_dialog").dialog("close");
		$("#loading_top").css("display","none");
		$("#u_id").val(data.row.user_id);
		$("#pass_u_id").css("display","none");
		$("#username").val(data.row.username);
		$("#from_date").val(data.row.from_date);
		$("#to_date").val(data.row.to_date);
		$("#first_name").val(data.row.first_name);
		$("#last_name").val(data.row.last_name);
		$("#mobile_number").val(data.row.mobile_number);
		$("#email_address").val(data.row.email_address);
		if(data.row.email_alert==1){$("#email_alert").attr("checked","checked");}else{$("#email_alert").removeAttr("checked");}
		if(data.row.sms_alert==1){$("#sms_alert").attr("checked","checked");}else{$("#email_alert").removeAttr("checked");}
	},'json');
	$(".user_add_table").show();
	$("#create_u_div").hide();
	$("#usr_combo").hide();
	$("#usr_asset_combo").hide();
	
}
function cancel_ds_usr()
{
	$("#user_dialog").dialog('option', 'title', '<?php echo $this->lang->line('Create_Users'); ?>');
	$("#u_id").val("");
	$("#pass_u_id").css("display","inline");
	fillUsrs();
	if($("#usr_combo").css("display") =="none" && $("#add_to_user option").length>0){
		$(".user_add_table").hide();
		$("#create_u_div").show();
		$("#usr_combo").show();
		$("#usr_asset_combo").show();
	}
	else
	{
		$("#user_dialog").dialog("close");
	}
}
function delete_usr_func()
{
	$("#confirm_alert_dialog").dialog('option', 'buttons',{
		'Yes' : function(){
			$(this).dialog("close");
			var val=$("#add_to_user").val();
			val=val.replace("u-","");
			//$("#loading_dialog").dialog("open");
			$("#loading_top").css("display","block");
			$.post("<?php echo base_url(); ?>/index.php/users/deleteData",{id:val},function(data){
				//$("#loading_dialog").dialog("close");
				$("#loading_top").css("display","none");
				$("user_dialog").dialog({ modal: true });
				if(data=="1")
				{
					fillUsrs();
					//$("#user_dialog").dialog("close");
					$("#alert_dialog").html("User Deleted Successfully");
					$("#alert_dialog").dialog("open");
				}
			});
		},
		'No' : function(){
			$(this).dialog("close");
			$("user_dialog").dialog({ modal: true });
		}
	});
	$("confirm_alert_dialog").dialog({ modal: true });
	$("#confirm_alert_dialog").dialog("open");
}

function cancel_ds_grp()
{
	$("#group_dialog").dialog("option","title","<?php echo $this->lang->line('Create_Group'); ?>");
	$("#new_group").val("");
	$("#edit_group_id").val("");
	/*fillGroups();*/
	if($("#group_list_combo").css("display") =="none" && $("#group_combo option").length > 0){
		$("#create_g_div").show();
		$("#new_group_tr").hide();
		$("#group_list_combo").show();
		$("#assets_combo_grp").show();	
	}
	else if($("#group_list_combo").css("display") =="block" && $("#group_combo option").length<1){
		$('#group_dialog').dialog('close');
	}else
	{
		$('#group_dialog').dialog('close');
	}
}
function create_group_func()
{
	$("#group_dialog").dialog("option","title","<?php echo $this->lang->line('Create_Group'); ?>");
	$("#edit_group_id").val("");
	$("#create_g_div").hide();
	$("#new_group_tr").show();
	$("#new_group").val("");
	$("#group_list_combo").hide();
	$("#assets_combo_grp").hide();	
}
function edit_group_func()
{
	$("#group_dialog").dialog("option","title","<?php echo $this->lang->line('Update_Group'); ?>");
	var val=$("#group_combo").val();
	$.post("<?php echo base_url(); ?>/index.php/home/get_group_detail/uid/"+val,function(data){
		$("#edit_group_id").val(data.row.id);
		$("#new_group").val(data.row.group_name);
	},'json');
	$("#create_g_div").hide();
	$("#new_group_tr").show();
	$("#group_list_combo").hide();
	$("#assets_combo_grp").hide();	
}

function delete_group_func()
{
	$("#confirm_alert_dialog").dialog('option', 'buttons',{
		'Yes' : function(){
			$(this).dialog("close");
			var val=$("#group_combo").val();
			val=val.replace("g-","");
			//$("#loading_dialog").dialog("open");
			$("#loading_top").css("display","block");
			$.post("<?php echo base_url(); ?>/index.php/group/deleteData",{id:val},function(data){
				////$("#loading_dialog").dialog("close");
				$("group_dialog").dialog({ modal: true });	
				if(data=="1")
				{
					$('#group_dialog').dialog('close');
						//$('#group_dialog').dialog('open');
					open_group_dialog();
					$("#alert_dialog").html("Group Deleted Successfully");
					$("#alert_dialog").dialog("open");
				}
			});
		},
		'No' : function(){
			//$('#group_dialog').dialog('close');
						//$('#group_dialog').dialog('open');
			//		open_group_dialog();
			$(this).dialog("close");
			$("group_dialog").dialog({ modal: true });
		}
	});
	$("confirm_alert_dialog").dialog({ modal: true });
	$("#confirm_alert_dialog").dialog("open");
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
<body onLoad="myLayout.allowOverflow('1');myLayout.resizeAll();loadAssets();alert_master();" style="line-height:20px">
<?php
	$this->load->view("upload_image");
?>
	<!-- Primary Page Layout
	================================================== -->
	<!-- Delete everything in this .container and get started on your own site! -->
	
	<div id="profile" style='background:none repeat scroll 0 0 #F5F5F5;box-shadow:0 2px 4px rgba(0, 0, 0, 0.2);border:1px solid #BEBEBE;z-index:198 !important;'>
				<div style='height : 150px;background:none repeat scroll 0 0 #FFFFFF;border-bottom:1px solid #BEBEBE;box-shadow:0 2px 4px rgba(0, 0, 0, 0.12);padding-bottom:2px'>
				<a style="color: red; padding-top: 5px; border: 0px none; float: right;"><b><?php echo $this->lang->line('ID'); ?> : <?php echo $this->session->userdata('user_id'); ?></b>&nbsp;&nbsp;&nbsp;</a>
		<!--		<a href="#" id="close" style="float:right; padding:5px"><img src="<?php echo base_url(); ?>assets/dashboard/images/close.png"/></a>-->
				<div style="float: left; padding: 14px; margin-top: 11px;height: 104px; width: 104px;">
					<img src="<?php echo base_url(); ?>assets/driver-photo/not_available.jpg"  style='height: 104px; width: 104px;' id="chage_profile_photo" alt="Image Not Found"/>
				</div>
				<div class="profile_change" onClick="profile_div();"> <?php echo $this->lang->line('change_photo'); ?> </div> 
				<div style="padding:26px">
					<span style='font-weight: bold;font-size:17px'><?php echo $this->session->userdata('first_name'); ?> <?php echo $this->session->userdata('last_name'); ?></span> 
					<span style='font-weight: bold;font-size:17px'><?php echo $this->session->userdata('company_name'); ?> <?php echo $this->session->userdata('last_name'); ?></span> 
					<span style="overflow: hidden; word-wrap: break-word;"><?php echo $this->session->userdata('email_address'); ?></span> 
					<span style="overflow: hidden; word-wrap: break-word;"><?php echo $this->session->userdata('mobile_number'); ?></span>
					<span style="overflow: hidden; word-wrap: break-word;"><u><?php echo $this->lang->line('last_login'); ?> : </u><?php echo date("$date_format $time_format", strtotime($this->session->userdata('last_login_time'))); ?>  
					</span> 
					<span style='font-weight: bold; font-size: 17px; position: absolute; top: 130px; left: 325px;'>
                    <a onClick="$('#profile').hide();subMenu('login_list')" style='font-weight: bold; float: right; cursor: pointer; font-size: 14px; border: 0px none;'> <?php echo $this->lang->line('more'); ?></a></span> 
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
					<a  href="JavaScript:void(0);" onClick=" hideSubMenu('<?php echo base_url(); ?>/index.php/changepassword','<?php echo $this->lang->line('Changepassword'); ?>','<?php echo $this->lang->line('Changepassword'); ?>');$('#profile').hide(); " style='padding: 10px 24px 10px 24px; text-decoration: none;'><?php echo $this->lang->line('Changepassword'); ?></a>
					</td>
					<td align='right'>
					<a  href="<?php echo site_url('sessions/logout'); ?>" style='padding: 10px 24px 10px 24px; text-decoration: none;'><?php echo $this->lang->line('log_out'); ?></a>
					</td>
					</tr>
					</table>
				</div>
			</div>
    <div class="ui-layout-north" style="height:120px;">
		<div style="top: 0px; width: 100%;text-align:center;background-color:brown;font-weight:bold"><?php 
					if(count($main_menu)>0)
					{
				?>
				<ul id='menu_bar' style='float:left;width:70%;padding:0;margin:0;list-style-type:none;'>
					<?php
						if((count($main_menu))>6)
							$count =6;
						else
							$count =count($main_menu);
						for($i=0;$i<$count;$i++)
							echo $main_menu[$i];
						if(count($main_menu)>6){?>
							<li><a href="#" onClick="show_more_div(this);"><?php  echo $this->lang->line("more"); ?> <img style="height:10px;width:10px" src="<?php echo base_url(); ?>assets/menu-ui/images/down_small_arrow.png"></a></li>
							<?php } ?>
						</ul>
					<?php	if(count($main_menu)>6)
						{
							echo "<ul id='more_div' style='float:left;padding:0;margin:0;list-style-type:none;display:none;position: absolute; top: 31px; left: 439px;  max-height: 251px;background-color:brown;' >";
							for($i=6;$i<count($main_menu);$i++)
								echo $main_menu[$i];
							echo "</ul>";
						}
					} ?>
		
	
			<div style="height:30px;display:block;float:right; margin-bottom:5px">
				
				<a href="#" style="float:left;padding:2px" ><img src="<?php echo base_url(); ?>assets/dashboard/images/speaker.png" /></a>
				<a href="#" style="float:left;padding:2px"><img src="<?php echo base_url(); ?>assets/dashboard/images/help.png" /></a>
				
				<a href="#" class="smallbtn" id="logout" ><img src="<?php echo base_url(); ?>assets/driver-photo/not_available.jpg"  style='float: left; width: 21px; padding-right: 5px; height: 23px;' id="img_upload_setid"/><?php echo $this->lang->line('my_account'); ?></a>
			</div>
		</div>
		<div class="sixteen columns" id="header" style="float:left;">
			<div class="twelve columns alpha">
				<h1 class="remove-bottom" style="padding-left:0; float:left;">
					<img src="<?php echo base_url(); ?>assets/dashboard/images/nKonnect_logo.png" alt="logo" onClick="window.location.reload()" style="cursor:pointer"/>
				</h1>
                <div id="header_msg">
                <?php echo $this->lang->line("For Best Performance view the site in Chrome Browser"); ?>
                </div>
			</div> 
			<div class="four columns omega" style="padding-top:5px;z-index:155;position:relative;width:249px;float:right;">
				
				<div style="clear:both"></div>
				<div style="float:right ; margin-top:5px">
					
					<?php $lang_array = explode(";",$this->session->userdata('disp_language_list'));
					/*
					for($i=0;$i<count($lang_array);$i++)
					{
						echo "<a href='#' class='link' onclick='setLanguage(\"".$lang_array[$i]."\")'>".$this->lang->line($lang_array[$i])."</a>";
					}*/
					?>
					
				</div><br/>
 			</div>
		</div>
		
		<!--<hr class="half-bottom" />-->
		<div class="sixteen columns ">
			<?php 
				$menu = array();
				$SQL = "select am.where_to_show,mm.menu_name,mm.menu_link,mm.tab_title,am.menu_id,mm.menu_image,mm.parent_menu_id from app_menu_master am left join main_menu_master mm on mm.id=am.menu_id  where am.del_date is null and am.status = 1 and mm.status=1  and  am.user_id =".$this->session->userdata('user_id')." order by am.priority";
				
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
								echo " onclick='hideSubMenu(\"".$menu_link[$i]."\",\"".$menu_name[$i]."\",\"".$menu_name[$i]."\")' ";
							}else if($where_to_show[$i] == 'link'  and $parent_id != "") {
								echo " onclick='topMenuToTab(\"".$menu_link[$i]."\",\"".$val->line($tab_title[$i])."\",\"".$val->line($tab_title[$i])."\")' ";
							} else if($where_to_show[$i] == 'sidebar'){
								echo " onclick='hideSubMenu_all();subMenu(\"".$menu_name[$i]."\")' ";
							}
							echo " ><a href='Javascript:void(0);' ";
							if($where_to_show[$i] != 'sidebar')
							{
								echo "onclick='myLayout.close( \"west\" );myLayout.resizeAll()'";
							}
							echo "'>";
							if($menu_image[$i] != "")
								echo "<img  src='". base_url()."assets/menu_image/".$menu_image[$i]."' class='".$where_to_show[$i]."'  />";
							echo "&nbsp;";
							echo $val->line($menu_name[$i]);
							echo "&nbsp;&nbsp;&nbsp;";
							
							if($where_to_show[$i] == 'link')
							{
								echo "</a>";
							}
							if($where_to_show[$i] == 'menu')
							{
								echo "<img src='".base_url()."assets/menu-ui/images/down.png' class='menu' /></a>";
								menu_print($tab_title,$menu_image,$menu_id,$menu_link,$menu_link,$menu_name,$where_to_show,$parent_menu_id,$menu_id[$i],$val);
							}
							
							if($where_to_show[$i] == 'sidebar')
							{
								echo "<img src='".base_url()."assets/menu-ui/images/down.png' class='menu' /></a>";
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
					echo "</ul>";
				}
				menu_print($tab_title,$menu_image,$menu_id,$menu_link,$menu_link,$menu_name,$where_to_show,$parent_menu_id,"",$this->lang);
			?>
		</div>	
			
	</div>
	<div class="ui-layout-center" id="tabs" style="overflow: hidden;float:left;position:relative;">
			<ul>
				<li id="tabs-dash"><a href="#tabs-1"><?php echo $this->lang->line('dashboard'); ?></a></li>
				<li class="addons" id="testAddons">
					<img id="imgmaxmin" src="<?php echo base_url(); ?>assets/style/img/icons/window_full_screen.png" style="cursor:pointer;float:left" alt="max" title="Maximize" onClick="maximize(this)" />
					<?php echo "<img src=\"".base_url()."assets/style/img/icons/new_window.png\" title=\"".$this->lang->line('new_window')."\" style='cursor:pointer;padding-left:3px' rel='external' />"; ?> 
				
					<?php  echo "<img src=\"".base_url()."assets/style/img/icons/printer.png  \" title=\"".$this->lang->line('print')."\" style='cursor:pointer;' rel='print' />"; ?>
				</li>
			</ul>	
			<div id="tabs-1">
				
				<div class="sixteen columns" style="width:100%" >
				
					<div class="twelve columns alpha">
						<select id="optdetail" style="width:50%" onChange="changeReport()">
							<option value=""><?php echo $this->lang->line('all_assets'); ?></option>
							<option value="running"><?php echo $this->lang->line('running'); ?></option>
							<option value="parked"><?php echo $this->lang->line('parked'); ?></option>
							<option value="out_of_network"><?php echo $this->lang->line('out_of_network'); ?></option>
							<option value="device_fault"><?php echo $this->lang->line('device_fault'); ?></option>
							<?php echo $groupOpt; ?>
							<?php echo $subUserOpt; ?>
						</select>
						<input type="hidden" name="txthidden"   />
					</div>
					<div class="four columns omega" >
						<div>
							<form onSubmit="return searchAssets()" style="float:right;">
							<input id="srcTxt" type="text" placeholder="<?php echo $this->lang->line('search'); ?>" />
							</form>
						</div>
					</div>
				</div>
				<div class="sixteen columns half-bottom" style="width:100%;float:left;">
						<div style="float:left">
						<img src="<?php echo base_url(); ?>assets/dashboard/images/selectgridview.png" alt="<?php echo $this->lang->line('list_view'); ?>" title="<?php echo $this->lang->line('list_view'); ?>" onClick="loadAssetsList()" style="cursor:pointer" id="select_assest_list_view"/>
						<img src="<?php echo base_url(); ?>assets/dashboard/images/listview.png" alt="<?php echo $this->lang->line('grid_view'); ?>" title="<?php echo $this->lang->line('grid_view'); ?>" onClick="loadAssetsGrid()" style="cursor:pointer" id="select_assest_grid_view"/>
						<img src="<?php echo base_url(); ?>assets/dashboard/images/thumbnail_view.png" alt="<?php echo $this->lang->line('thambnail_view'); ?>" title="<?php echo $this->lang->line('thambnail_view'); ?>" onClick="loadAssetsThumb()" style="cursor:pointer" id="select_assest_thumb_view"/>
						</div>
						<div id="detailed_pan" style="float: left; font-size: 14px; padding-top: 5px; padding-left: 12px;">
						<a onClick='detail_list_a("")' class="ui-state-default"  style='text-decoration:underline' id='assets_total' rel="<?php echo $this->lang->line('Number of Total Vehicles'); ?>"><?php echo $this->lang->line("Total Assets"); ?></a> : <strong><span id="assets_total_1"><?php echo $total_1; ?></span></strong>, 
						<a onClick="detail_list_a('running')" id='assets_running' class="ui-state-default"  rel="<?php echo $this->lang->line('Vehicles that has speed more than 0 (zero) and connected with Server since 20 minutes'); ?>"><?php echo $this->lang->line('running'); ?></a> : <strong><span id="assets_running_1"><?php echo $running_1; ?></span></strong>, 
						<a class="ui-state-default"  onClick="detail_list_a('parked')" id='assets_parked' rel="<?php echo $this->lang->line('Vehicles that has speed 0 (zero) and connected with Server since 20 minutes'); ?>"><?php echo $this->lang->line('parked'); ?></a> : <strong><span id="assets_parked_1"><?php echo $parked_1; ?></span></strong>, 
						<a class="ui-state-default" onClick="detail_list_a('out_of_network')" id='assets_out' rel="<?php echo $this->lang->line('Vehicles that are not connected with Server since 20 minutes'); ?>"><?php echo $this->lang->line('out_of_network'); ?></a> : <strong><span id="assets_out_1"><?php echo $out_of_network_1; ?></span></strong>, 
						<a class="ui-state-default" onClick="detail_list_a('device_fault')" id='assets_fault' rel="<?php echo $this->lang->line('Vehicles that are not Connected with Server since 24 hours'); ?>"><?php echo $this->lang->line('device_fault'); ?></a> : <strong><span id="assets_fault_1"><?php echo $device_fault_1; ?></span></strong></div>
						<div style="float:right"><div style="line-height: 16px;"><span style="height:12px;width:20px;margin-top:2px;background-color:green;display:block;float:left"></span><span style="float:left;font-size:12px">&nbsp;&nbsp;<?php echo $this->lang->line('running'); ?>, <?php echo $this->lang->line('parked'); ?></span></div><div style="clear:both"></div>
						<div style="line-height: 16px;"><span style="height:12px;width:20px;margin-top:2px;background-color:red;display:block;float:left"></span><span style="float:left;font-size:12px">&nbsp;&nbsp;<?php echo $this->lang->line('out_of_network'); ?>, <?php echo $this->lang->line('device_fault'); ?></span></div>
						</div>
						<div style="clear:both"></div>
					
				</div>
				<div class="sixteen columns half-bottom" style="width:100% !important; float:left;">
						<div id="main" style="height: 100%;" align="center">
						</div>
				</div>
				<div style='text-align: center;clear:both'>
				<div style="float:left;width:100%;height:2em;padding-top:0.2em"><input type='checkbox' onclick='stop_resume_toggle()' id='checkboxToggle'> <?php echo $this->lang->line('data_refresh_after'); ?> <input type='text' size='2' onblur='counter_change()' value='60' id='time_in_seconds'> <?php echo $this->lang->line('seconds'); ?> (<?php echo $this->lang->line('refresh_after'); ?> <span id='seconds'>60</span> <?php echo $this->lang->line('second'); ?>) &nbsp;&nbsp;<span onClick="reloadDashboard_Assets_Timer()" style="font-weight:bold;text-decoration:underline;cursor:pointer"><?php echo $this->lang->line('refresh'); ?></span></div>
				<div id="pbar"></div> 
				
				<!-- <a href='JavaScript:void(0);' onclick='stop_resume_toggle()' style='font-weight:bold' id='Timer_Event'>Stop Refresh</a>-->
				</div>
				<div style="height:10px"></div>
			</div>
		</div>
	<div class="ui-layout-south">
		<center>&copy; 2012 NKonnect Infoway v2.3 latest updated on 16.08.2012 06.30 PM IST<br></center>
	</div>

	<div class="ui-layout-west" id="west_sub_menu">
	</div>
		<div id='login_list' class="submenu" style="display:none"> 
			<a class="ui-button ui-widget ui-state-default ui-button-text-only" style="padding:5px;width:93%;text-align:left;" onClick="topMenuToTab('<?php echo base_url(); ?>/index.php/history', '<?php echo $this->lang->line("history"); ?>', 'history')">&nbsp;&nbsp;<img src="<?php echo base_url(); ?>assets/menu-ui/images/house.png" class="menu" />&nbsp;<?php echo $this->lang->line("history"); ?></a>
			
			<a class="ui-button ui-widget ui-state-default ui-button-text-only" style="padding:5px;width:93%;text-align:left;" onClick="topMenuToTab('<?php echo base_url(); ?>/index.php/tooltip', '<?php echo $this->lang->line("Popup Msgs"); ?>', 'tooltip')">&nbsp;&nbsp;<img src="<?php echo base_url(); ?>assets/menu-ui/images/house.png" class="menu" />&nbsp;<?php echo $this->lang->line("OLD Popup Msgs"); ?></a>
			
			<a class="ui-button ui-widget ui-state-default ui-button-text-only" style="padding:5px;width:93%;text-align:left;" onClick="topMenuToTab('<?php echo base_url(); ?>/index.php/failed_login', '<?php echo $this->lang->line("Falied Login"); ?>', 'Falied Login')">&nbsp;&nbsp;<img src="<?php echo base_url(); ?>assets/menu-ui/images/house.png" class="menu" />&nbsp;<?php echo $this->lang->line("Falied Login"); ?></a>
		</div>

<!--<div id="float-icons1">									
	<a href="#" onClick="hideShowFixedIcon()"><span class="float-open"></span></a>
</div>	-->
<div id="float-icons" style='display:none'>									
	<a class="float-icon-enabled" onClick="group_dialog_opn(0);" title="<?php echo $this->lang->line('create_user'); ?>" ><span class="float-temp_login"></span></a>	
	<a class="float-icon-enabled" onClick="group_dialog_opn(1);" title="<?php echo $this->lang->line('add_to_group'); ?>"><span class="float-group"></span></a>	
	<a class="float-icon-enabled" onClick="group_dialog_opn(2);"><span class="float-map" title="<?php echo $this->lang->line('map_view'); ?>"></span></a>
	<a class="float-icon-enabled tooltip" onClick="group_dialog_opn(3);" ><span class="float-dashboard" title="<?php echo $this->lang->line('assets_dashboard'); ?>"></span></a>
</div>	


	<!-- JS
	================================================== -->

<div id="alert_dialog" style="display:none;"></div>
<div id="distance_box_dialog" style="display:none;"></div>
<div id="group_dialog" style="display:none;">
<div class="create_user_div" align="center" id="create_g_div"><a href="#" onClick="create_group_func()" style="text-decoration:none"><?php echo $this->lang->line("Create Group"); ?></a>&nbsp;&nbsp;<a href="#" onClick="edit_group_func()" style="text-decoration:none"><?php echo $this->lang->line("Edit Group"); ?></a>&nbsp;&nbsp;<a href="#" onClick="delete_group_func()" style="text-decoration:none"><?php echo $this->lang->line("Delete Group"); ?></a></div>
<p id="tips_grp"></p>
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
			<td><?php echo $this->lang->line('group_name'); ?> :
			<input type="text" id="new_group" class="text ui-widget-content ui-corner-all" /></td>
		</tr>
		<tr>
			<td width="50%" colspan="2" align="center">
			<input type="button" value="<?php echo $this->lang->line('submit'); ?>" onClick="addToGroup()">
			<input type="button" value="<?php echo $this->lang->line('cancel'); ?>" onClick="cancel_ds_grp()">
			</td>
		</tr>
		
	</tbody>
	</table>
</div>
<div id='popup' style='position: fixed; bottom: 0px; z-index: 10000; width: 35%; right: 6px;' >
	<audio id="sound_start" src="<?php echo base_url(); ?>beep.wav" controls preload="auto" autobuffer style='display:none'></audio>
</div>
<div id="confirm_alert_dialog" style="display:none;"><?php echo $this>lang->line("Are You Sure To Delete"); ?>?</div>
<div id="loading_dialog" style="display:none;" class="removeTitle" ><img src='<?php echo base_url(); ?>assets/images/loading.gif' style="padding-right:7px"><?php echo $this->lang->line("Loading"); ?>...<input type="button" id="loading_dialog_btn_line" value="Close" style="float:right"><div style="clear:both"></div></div>
<div class="vX UC" style="display: none" id="loading_top"><div class="J-J5-Ji"><div class="UD"></div><div class="vh"><div class="J-J5-Ji"><div class="vZ L4XNt"><span class="v1"><?php echo $this->lang->line("Loading"); ?>... &nbsp;&nbsp;<a href="#" onClick="cancelloading();" style="color:blue;text-decoration:underline"><?php echo $this->lang->line("cancel"); ?></a></span></div></div><div class="J-J5-Ji"></div></div><div class="UB"></div></div></div>
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
						<td width="50%"><label><?php echo $this->lang->line('user_valid'); echo " ".$this->lang->line('from_date'); ?> </label><input type="text" name="from_date" id="from_date" class="date text ui-widget-content ui-corner-all" value="<?php echo date('d.m.Y H:i'); ?>" /></td>
						<td width="50%"><label><?php echo $this->lang->line('user_valid'); echo " ".$this->lang->line('to_date'); ?> </label><input type="text" name="to_date" id="to_date" class="date text ui-widget-content ui-corner-all" value="<?php echo date('d.m.Y H:i'); ?>" />
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
<script>
	var timer_on=0;
	var timer;
	var current ;
	var percentage;
	var time_in_s;
	function stop_resume_toggle()
	{
		time_in_s=Number($("#time_in_seconds").val());
		if(timer_on==1)
		{
			clearTimeout(timer);
			timer_on=0;
			$("#seconds").html($("#time_in_seconds").val());
		}	
		else
		{
			counter();
			timer_on=1;
		}
		
	}
	
	function counter()
	{
		if(Number($("#seconds").html()) == Number($("#time_in_seconds").val()))
		{
			getSelectChkd();
		}
		if($("#seconds").html() == 0){
			$("#pbar").progressbar("value" , 0);	
			clearTimeout(timer);
			reloadAssets();
		}
		else{
			/*
			current=Number($("#seconds").html());
		
			percentage = Number(current/(time_in_s)*100)-Number(0.99/(time_in_s)*100);
			val=100-percentage;
			
			$("#pbar").progressbar("value" , val);	
			*/
			$("#seconds").html(Number($("#seconds").html())-1);
			timer = setTimeout('counter()',1000);
		}
	}
	function counter_change()
	{
		if(Number($("#time_in_seconds").val())<1)
			$("#time_in_seconds").val(60);
			
		$("#seconds").html($("#time_in_seconds").val());
		time_in_s=Number($("#time_in_seconds").val());
	}
function loadJQPLOT()
{
	if($.jqplot == undefined)
	{
	/*$.get("<?php echo base_url(); ?>assets/jqplot/jquery.jqplot_min.js").pipe($.get("<?php echo base_url(); ?>assets/jqplot/jqplot.logAxisRenderer_min.js")).pipe($.get("<?php echo base_url(); ?>assets/jqplot/jqplot.canvasTextRenderer.js")).pipe($.get("<?php echo base_url(); ?>assets/jqplot/jqplot.barRenderer_min.js")).pipe($.get("<?php echo base_url(); ?>assets/jqplot/jqplot.categoryAxisRenderer_min.js")).pipe($.get("<?php echo base_url(); ?>assets/jqplot/jqplot.dateAxisRenderer_min.js")).pipe($.get("<?php echo base_url(); ?>assets/jqplot/jqplot.pointLabels_min.js")).pipe($.get("<?php echo base_url(); ?>assets/jqplot/jqplot.cursor_min.js")).pipe($.get("<?php echo base_url(); ?>assets/jqplot/jqplot.highlighter_min.js",{}, function(){
		alert("loadComplete");
	}));
	

	//var jqScript=['<?php echo base_url(); ?>assets/jqplot/jquery.jqplot_min.js', '<?php echo base_url(); ?>assets/jqplot/jqplot.logAxisRenderer_min.js', '<?php echo base_url(); ?>assets/jqplot/jqplot.canvasTextRenderer.js', '<?php echo base_url(); ?>assets/jqplot/jqplot.barRenderer_min.js', '<?php echo base_url(); ?>assets/jqplot/jqplot.categoryAxisRenderer_min.js', '<?php echo base_url(); ?>assets/jqplot/jqplot.dateAxisRenderer_min.js', '<?php echo base_url(); ?>assets/jqplot/jqplot.pointLabels_min.js', '<?php echo base_url(); ?>assets/jqplot/jqplot.cursor_min.js', '<?php echo base_url(); ?>assets/jqplot/jqplot.highlighter_min.js'];
	//var jqScript=['<?php echo base_url(); ?>assets/jqplot/jquery.jqplot_min.js', '<?php echo base_url(); ?>assets/jqplot/jqplot.logAxisRenderer_min.js', '<?php echo base_url(); ?>assets/jqplot/jqplot.canvasTextRenderer.js','<?php echo base_url(); ?>assets/jqplot/jqplot.barRenderer_min.js','<?php echo base_url(); ?>assets/jqplot/jqplot.categoryAxisRenderer_min.js','<?php echo base_url(); ?>assets/jqplot/jqplot.dateAxisRenderer_min.js','<?php echo base_url(); ?>assets/jqplot/jqplot.pointLabels_min.js','<?php echo base_url(); ?>assets/jqplot/jqplot.cursor_min.js','<?php echo base_url(); ?>assets/jqplot/jqplot.highlighter_min.js'];
/*	$.getScript(jqScript[0], function() {
		$.getScript(jqScript[1], function() {
			$.getScript(jqScript[2], function() {
				$.getScript(jqScript[3], function() {
					$.getScript(jqScript[4], function() {
				$.getScript(jqScript[5], function() {
	$.getScript(jqScript[6], function() {
		$.getScript(jqScript[7], function() {
		$.getScript(jqScript[8], function() {
		alert("load Complete");
	});
	});
	});	
	});
			});
				});
		});
		});
	});
*/
	
	var scriptss="<script type='text/javascript' src='<?php echo base_url(); ?>assets/jqplot/jquery.jqplot_min.js'></scr"+"ipt><script type='text/javascript' src='<?php echo base_url(); ?>assets/jqplot/jqplot.logAxisRenderer_min.js'></scr"+"ipt><script type='text/javascript' src='<?php echo base_url(); ?>assets/jqplot/jqplot.canvasTextRenderer.js'></scr"+"ipt><script type='text/javascript' src='<?php echo base_url(); ?>assets/jqplot/jqplot.barRenderer_min.js'></scr"+"ipt><script type='text/javascript' src='<?php echo base_url(); ?>assets/jqplot/jqplot.categoryAxisRenderer_min.js'></scr"+"ipt><script type='text/javascript' src='<?php echo base_url(); ?>assets/jqplot/jqplot.dateAxisRenderer_min.js'></scr"+"ipt><script type='text/javascript' src='<?php echo base_url(); ?>assets/jqplot/jqplot.pointLabels_min.js'></scr"+"ipt><script type='text/javascript' src='<?php echo base_url(); ?>assets/jqplot/jqplot.cursor_min.js'></scr"+"ipt><script type='text/javascript' src='<?php echo base_url(); ?>assets/jqplot/jqplot.highlighter_min.js'></scr"+"ipt><script type='text/javascript' src='<?php echo base_url(); ?>assets/jqplot/jqplot.canvasAxisLabelRenderer.js'></scr"+"ipt>";
	$("head").append(scriptss);
	}
}
function loadColorSelection()
{
	if(!$.isFunction($(".color-picker").miniColors))
	{
		var scrpt="<link href='<?php echo base_url(); ?>assets/style/css/jquery.miniColors.css' rel='stylesheet' type='text/css'><script src='<?php echo base_url(); ?>assets/jquery/jquery.miniColors_min.js' type='text/javascript'></scr"+"ipt><script type='text/javascript' src='<?php echo base_url(); ?>assets/jquery/jquery.dd.js'></scr"+"ipt><link href='<?php echo base_url(); ?>assets/style/css/dd.css' rel='stylesheet' type='text/css' />";
		$("head").append(scrpt);
	}
}
function loadDropdown()
{
	if(!$.isFunction($(".color-picker").msDropDown))
	{
		var scrpt="<script type='text/javascript' src='<?php echo base_url(); ?>assets/jquery/jquery.dd.js'></scr"+"ipt><link href='<?php echo base_url(); ?>assets/style/css/dd.css' rel='stylesheet' type='text/css' />";
		$("head").append(scrpt);
	}
}
function loadSWFupload()
{
	if(!$.isFunction($(".assetsupload-control").swfupload))
	{
		var swfScript="<script type='text/javascript' src='<?php echo base_url(); ?>assets/swfupload/swfupload_min.js'></scr"+"ipt><script type='text/javascript' src='<?php echo base_url(); ?>assets/swfupload/jquery.swfupload.js'></scr"+"ipt>";
		$("head").append(swfScript);
	}
}

function loadInfobox()
{
	if(!$.isFunction($(".x").rotate))
	{
		var varinfoBox="<script src='<?php echo base_url(); ?>assets/javascript/infobox_min.js' type='text/javascript'></scr"+"ipt><script src='<?php echo base_url(); ?>assets/javascript/jQueryRotate.2.2_min.js' type='text/javascript'></sc"+"ript>";
		$("head").append(varinfoBox);
	}
}
function loadJGrid()
{
	if(!$.isFunction($(".x").jqGrid))
	{
		var varJgrid="<link rel='stylesheet' type='text/css' href='<?php echo base_url(); ?>assets/jqgrid/css/ui.jqgrid.css' /><script type='text/javascript' src='<?php echo base_url(); ?>assets/jqgrid/js/i18n/grid.locale-en_min.js'></sc"+"ript><script type='text/javascript' src='<?php echo base_url(); ?>assets/jqgrid/js/jquery.jqGrid.src_min.js'></scr"+"ipt>";
		$("head").append(varJgrid);
	}
}
function loadInfoBubble()
{
	if(!window.InfoBubble)
	{
		var varInfob="<script type='text/javascript' src='<?php echo base_url(); ?>assets/javascript/infobubble-compiled.js'></sc"+"ript><script type='text/javascript' src='<?php echo base_url(); ?>assets/javascript/elabel_min.js'></sc"+"ript>";
		$("head").append(varInfob);
	}
}
function loadMarkerClusters()
{
	if(!window.MarkerClusterer)
	{
		var varMCluster="<script type='text/javascript' src='<?php echo base_url(); ?>assets/javascript/markerclusterer_min.js'></sc"+"ript>";
		$("head").append(varMCluster);
	}
}
function loadSpeedoMeter()
{
	if(!$.isFunction($(".x").speedometer))
	{
		var varSMetter="<script src='<?php echo base_url(); ?>assets/speedo-meter/jquery.speedometer.js'></sc"+"ript><script src='<?php echo base_url(); ?>assets/speedo-meter/jquery.jqcanvas-modified.js'></scr"+"ipt><script src='<?php echo base_url(); ?>assets/speedo-meter/excanvas-modified.js'></sc"+"ript>";
		$("head").append(varSMetter);
	}
}
function reloadDashboard_Assets_Timer()
{
	clearTimeout(timer);
	reloadAssets();
}
function cancelloading()
{
	$("#loading_top").css("display","none");
}
</script>
</body>
	<!--	<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/themeswitcher.js"></script>
	-->
</html>