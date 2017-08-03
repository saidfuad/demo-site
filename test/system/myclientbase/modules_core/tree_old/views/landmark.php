<?php
	$uid = $this->session->userdata('usertype_id');
	$profile_id = $this->session->userdata('profile_id');
	if($uid==1)
		$data = array("Create Landmark","Search");
	else
	{
		$data = array();
		$va1l = $this->db;
		$va1l->select("setting_name");
		$va1l->where("profile_id",$profile_id);
		$va1l->where("setting_name !=",'main');
		$va1l->where("menu_id",'37');
		$va1l ->where("del_date",NULL);
		$res_val = $va1l->get("mst_user_profile_setting");
		foreach($res_val ->result_array() as $row)
		{
			$data[] = $row['setting_name'];
			
		}
	
	}
	

?>
<script type="text/javascript">
loadDropdown()
</script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-34256255-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<?php
$dCombo = "<select id='cmbDevice_".time()."' style='height:65px;' name='cmbDevice' multiple='multiple'>";
$dCombo .= $deviceOpt;
$dCombo .= "</select>";
$dCombo_land_nm="land_device_".time();
$dCombo_land = "<select class='select ui-widget-content ui-corner-all' id='land_device_".time()."' style='height:85px;' name='land_device' multiple='multiple'>";

$dCombo_land .= $deviceOpt;

$dCombo_land .= "</select>";


	/*$user_id = $this->session->userdata('user_id');
	$SQL="select id,landmark_group_name from landmark_group where user_id = $user_id";
	$result=mysql_query($SQL);
		$option = "<select class=\"select ui-widget-content ui-corner-all\" id=\"landmark_group_nm_".time()."\"><option value=\"\">Select Group Name</option>";
	while($row=mysql_fetch_array($result))
	{
		$option .="<option value=\"".$row['id']."\">".$row['landmark_group_name']."</option>";
	}

	$option .= "</select>";
*/

//path to directory to scan
$directory = "assets/landmark_images/";
 
//get all image files with a .jpg extension.
//$images = glob($directory . "{*.jpg,*.gif,*.png}", GLOB_BRACE);
 
//print each file name
/*
$iconOpt = '';
foreach($images as $image)
{
	$iconOpt .= '<option title="'.base_url().$image.'" value="'.$image.'"></option>';
}*/
$icon_combo = '<select id="land_icon_'.time().'" >';

$icon_combo .= $images;

$icon_combo .= '</select>';
?>
<style>
	#land_main<?php echo time(); ?> td{
		padding:2px;
	}
	.error_sel {
		border:1px solid red;
	}
	.not_err {
		border:1px solid black;
	}
	#land_icon_<?php echo time(); ?>_msdd{
		margin-top:5px;
		width;80px;
	}	
</style>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function(){
		//$("#loading_dialog").dialog("close");
		$("#loading_top").css("display","none");
	});

var options_lnd = [];
var markersLandmark  = [];

var sidebar_htmlmap  = '';
var marker_htmlmap  = [];

var to_htmlsmap  = [];
var from_htmlsmap  = [];

var map_landmark = null;
var mapOptionsmap;

var lbounds;

var newLandmark = false;

var clickPoint = [];
var landLat = "";
var landLng = "";	
var draw_circle = null;	
var landMarkerArray = [];
var circleArray = [];
var landmark_e_point;

var landmark_selected_id<?php echo time(); ?> = '';
//new window button
function TrackControl(controlDiv, mapmap) {
<?php
	if(in_array('Create Landmark',$data)){
	?>
  controlDiv.style.padding = '5px';
	 
  var controlUI = document.createElement('DIV');
  controlUI.style.backgroundColor = 'white';
  controlUI.style.borderStyle = 'solid';
  controlUI.style.borderWidth = '1px';
  controlUI.style.cursor = 'pointer';
  controlUI.style.textAlign = 'center';
  controlUI.title = '<?php echo $this->lang->line("lick to Create Landmark"); ?>';
  controlDiv.appendChild(controlUI);

  // Set CSS for the control interior
  var controlText = document.createElement('DIV');
  controlText.style.fontFamily = 'Arial,sans-serif';
  controlText.style.fontSize = '12px';
  controlText.style.height = '20px';
  controlText.style.paddingTop = '3px';
  controlText.style.paddingLeft = '4px';
  controlText.style.paddingRight = '4px';
  controlText.innerHTML = '<?php echo $this->lang->line("Landmark"); ?>';
  controlUI.appendChild(controlText);
  
  google.maps.event.addDomListener(controlUI, 'click', function() {
	addLandmark();			
  });
  <?php } ?>
}
function TrackControl_s(controlDiv, mapmap) {
<?php
	if(in_array('Search',$data)){
	?>

  // Set CSS styles for the DIV containing the control
  // Setting padding to 5 px will offset the control
  // from the edge of the map
  controlDiv.style.padding = '5px';
  
  // Set CSS for the control border
  var control_UI = document.createElement('DIV');
  control_UI.style.backgroundColor = 'white';
  control_UI.style.borderStyle = 'solid';
  control_UI.style.borderWidth = '1px';
  control_UI.style.cursor = 'pointer';
  control_UI.style.textAlign = 'center';
  control_UI.title = '<?php echo $this->lang->line("Click to Search"); ?>';
  controlDiv.appendChild(control_UI);
  // Set CSS for the control interior
  var seachText = document.createElement('DIV');
  seachText.style.fontFamily = 'Arial,sans-serif';
  seachText.style.fontSize = '12px';
  seachText.style.height = '20px';
  seachText.style.paddingTop = '3px';
  seachText.style.paddingLeft = '4px';
  seachText.style.paddingRight = '4px';
  seachText.innerHTML = 'Search';
  control_UI.appendChild(seachText);
  
  google.maps.event.addDomListener(control_UI, 'click', function() {
	if($("#search_landmark").css('display') == 'block'){
		$("#search_landmark").css('display', 'none');
	}else{
		$("#search_landmark").css('display', 'block');
	}	
  });
  <?php } ?>
}
var deviceCombo = "<?php echo $dCombo; ?>";
var deviceCombo_land = "<?php echo $dCombo_land; ?>";
<?php /*
var htmltext_landmark = '<table class="formtable" id="land_main<?php echo time(); ?>" style="display:none;">';
	htmltext_landmark +=	'<input type="hidden" id="land_id_<?php echo time(); ?>">';
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	'<td><?php echo $this->lang->line("Name"); ?></td>';
	htmltext_landmark +=	'<td><input type="text" class="text ui-widget-content ui-corner-all" id="land_name_<?php echo time(); ?>" onmouseover="this.focus();"></td>';
	htmltext_landmark +=	'</tr>';
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	'<td style="vertical-align: middle !important;"><?php echo $this->lang->line("Address"); ?></td>';
	htmltext_landmark +=	'<td><textarea class="textarea ui-widget-content ui-corner-all" id="land_address_<?php echo time(); ?>"></textarea></td>';
	htmltext_landmark +=	'</tr>';
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	'<td><?php echo $this->lang->line("Radius"); ?></td>';
	htmltext_landmark +=	'<td><div style="width:50%;float:left;"><input type="text" class="text ui-widget-content ui-corner-all" id="land_radius_<?php echo time(); ?>" value="2" style="width:70px;" /></div><div style="width:50%;float:right;"><select class="select ui-widget-content ui-corner-all" id="distance_unit_<?php echo time(); ?>"><option>KM</option><option>Mile</option><option>Meter</option></select></div></td>';
	htmltext_landmark +=	'</tr>';
	
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	'<td><?php echo $this->lang->line("Icon"); ?></td>';
	htmltext_landmark +=	'<td><?php echo $icon_combo; ?></td>';
	htmltext_landmark +=	'</tr>';
	
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	'<td><?php echo $this->lang->line("Group"); ?></td>';
	htmltext_landmark +=	'<td><?php echo $LandmarkGroupOpt; ?></td>';
	htmltext_landmark +=	'</tr>';		
	
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	'<td><?php echo $this->lang->line("Search"); ?></td>';
	htmltext_landmark +=	'<td><input type="text" class="text ui-widget-content ui-corner-all" style="width:100%" class="not_err" name="search_c_1_lnd" id="search_c_1_lnd<?php echo time(); ?>" onKeyUp="searchComb_lnd()" /></td>';
	htmltext_landmark +=	'</tr>';
	
	htmltext_landmark +=	'<tr style="line-height:10px">';
	htmltext_landmark +=	'<td>&nbsp;</td>';
	htmltext_landmark +=	'<td><span><a href="#" style="float:right;margin-right:15px;color:blue;font-size:10px" onclick="selectAlllandmark<?php echo time(); ?>(\'<?php echo "#land_device_".time(); ?>\')"><?php echo $this->lang->line("Select/Unselect All"); ?></a></span>';
	htmltext_landmark += '</td>';
	htmltext_landmark +=	'</tr>';	
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	'<td style="vertical-align: middle !important;"><span><?php echo $this->lang->line("Assets"); ?>: </span></td>';
	htmltext_landmark +=	'<td>';
	deviceCombo_land = deviceCombo_land.replace(/ selected/g,'');
	htmltext_landmark += deviceCombo_land;
	htmltext_landmark += '</td>';
	htmltext_landmark +=	'</tr>';
	
	htmltext_landmark +=	'<tr><td colspan="2"><a href="#" style="text-decoration:underline;" onclick="hideShowAddressbookLandmark()">Addressbook</a></td></tr>';
	
	htmltext_landmark +=	'<tr class="addressbook_tr_l" style="display:none;">';
	htmltext_landmark +=	'<td style="vertical-align: middle !important;">Group<?php //echo $this->lang->line("Device_Name"); ?></td>';
	htmltext_landmark +=	'<td><select id="l_addressbook_group<?php echo time(); ?>" class="select ui-widget-content ui-corner-all" name="addressbook_group" onchange="filterAddressbookLandmark(this.value)"><option value="">Select Group</option><?php echo $addressbookGroupOpt; ?></select></td>';
	htmltext_landmark +=	'</tr>';
	
	htmltext_landmark +=	'<tr class="addressbook_tr_l" style="display:none;">';
	htmltext_landmark +=	'<td style="vertical-align: middle !important;">Addressbook<?php //echo $this->lang->line("Device_Name"); ?></td>';
	htmltext_landmark +=	'<td><select id="l_addressbook<?php echo time(); ?>" class="select ui-widget-content ui-corner-all" style="height:65px;" name="addressbook" multiple="multiple"><?php echo $addressbookOpt; ?></select></td>';
	htmltext_landmark +=	'</tr>';
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	'<td style="vertical-align: middle !important;">';
	htmltext_landmark +=	'<?php echo $this->lang->line("Dealer Code"); ?>';
	htmltext_landmark +=	'</td>';
	htmltext_landmark +=	'<td>';
	htmltext_landmark +=	'<textarea id="lm_comments<?php echo time(); ?>" name="comments" class="textarea ui-widget-content ui-corner-all"></textarea>';
	htmltext_landmark +=	'</td>';
	htmltext_landmark +=	'</tr>';
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	'<td colspan="2">';
	htmltext_landmark +=	'<?php echo $this->lang->line("Alert Before Landmark"); ?>&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="alert_before_landmark" id="alert_before_landmark<?php echo time(); ?>" class="text ui-widget-content ui-corner-all" style="width:50px;" value="0">';
	htmltext_landmark +=	'</td>';
	htmltext_landmark +=	'</tr>';
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	'<td colspan="2"><?php echo $this->lang->line("Sms Alert"); ?> <input type="checkbox" name="landmark_sms_alert" id="landmark_sms_alert<?php echo time(); ?>" checked="true">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line("Email Alert"); ?>  <input type="checkbox" name="landmark_email_alert" id="landmark_email_alert<?php echo time(); ?>" checked="true">';
	htmltext_landmark +=	'</td>';
	htmltext_landmark +=	'</tr>';
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	'<td colspan="2" align="center"><input type="button" id="btnLandCreate<?php echo time(); ?>" value="<?php echo $this->lang->line("Save"); ?>" onClick="createLanbmark<?php echo time(); ?>()">&nbsp;&nbsp;<input type="button" id="btnCancelLand<?php echo time(); ?>" value="<?php echo $this->lang->line("Clear"); ?>" onClick="clearLandSelection<?php echo time(); ?>()">&nbsp;&nbsp;<input type="button" id="btnBackLnd<?php echo time(); ?>" value="<?php echo $this->lang->line("Back"); ?>" onClick="goBackLand()"><input type="button" id="btnDeleteLand<?php echo time(); ?>" value="<?php echo $this->lang->line("Delete Landmark"); ?>" style="display:none;">';
	htmltext_landmark +=	'</td>';
	htmltext_landmark +=	'</tr>';
	
	
	htmltext_landmark +=	'</table>';
	
	htmltext_landmark += '<table id="land_help<?php echo time(); ?>">';
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	'<td style="font-size:14px;"><?php echo $this->lang->line("Click_on_Map_For_Location"); ?></td>';		
	htmltext_landmark +=	'</tr>';
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	'<td style="font-size:14px;padding-left:50px"><h4>OR</h4></td>';
	htmltext_landmark +=	'</tr>';
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	"<td style='font-size:14px;'><select id='live_assets_cmb<?php echo time(); ?>' style='margin-top: 5px;padding: 0.4em;width: 94%;' class='select ui-widget-content ui-corner-all'><?php echo $live_combo; ?></select></td>";		
	htmltext_landmark +=	'</tr>';
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	'<td style="font-size:14px;"><input type="button" onclick="getSelectedCoords()" value="Set Selected" style="margin-top:3px"></td>';		
	htmltext_landmark +=	'</tr>';
	htmltext_landmark +=	'</table>';*/ ?>
	
	var htmltext_landmark = '<table class="formtable" width="330px" id="land_main<?php echo time(); ?>" style="display:none;">';
	htmltext_landmark +=	'<input type="hidden" id="land_id_<?php echo time(); ?>">';
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	'<td><?php echo $this->lang->line("Name"); ?><br/>';
	htmltext_landmark +=	'<input type="text" class="text ui-widget-content ui-corner-all" id="land_name_<?php echo time(); ?>" onmouseover="this.focus();"><br/>';
	htmltext_landmark +=	'<?php echo $this->lang->line("Search"); ?>';
	htmltext_landmark +=	'<br/><input type="text" class="text ui-widget-content ui-corner-all" style="width:92%" class="not_err" name="search_c_1_lnd" id="search_c_1_lnd<?php echo time(); ?>" onKeyUp="searchComb_lnd()" /></td>';
	htmltext_landmark +=	'<td><?php echo $this->lang->line("Address"); ?>';
	htmltext_landmark +=	'<br/><textarea class="textarea ui-widget-content ui-corner-all" id="land_address_<?php echo time(); ?>" style="height:70px;"></textarea></td>';
	
	htmltext_landmark +=	'</tr>';
	
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	'<td><?php echo $this->lang->line("Radius"); ?>';
	htmltext_landmark +=	'<br/><div style="width:50%;float:left;"><input type="text" class="text ui-widget-content ui-corner-all" id="land_radius_<?php echo time(); ?>" value="2" style="width:65px;" /></div><div style="width:50%;float:right;"><select class="select ui-widget-content ui-corner-all" id="distance_unit_<?php echo time(); ?>"><option>KM</option><option>Mile</option><option>Meter</option></select></div><br/><?php echo $this->lang->line("Icon"); ?><br/><?php echo $icon_combo; ?></td>';
	htmltext_landmark +=	'<td style="vertical-align: middle !important;"><span><?php echo $this->lang->line("Assets"); ?>:<span><a href="#" style="float:right;margin-right:15px;color:blue;font-size:10px" onclick="selectAlllandmark<?php echo time(); ?>(\'<?php echo "#land_device_".time(); ?>\')"><?php echo $this->lang->line("Select/Unselect All"); ?></a></span> </span>';
	htmltext_landmark +=	'<br/>';
	deviceCombo_land = deviceCombo_land.replace(/ selected/g,'');
	htmltext_landmark += deviceCombo_land;
	htmltext_landmark += '</td>';
	htmltext_landmark +=	'</tr>';	

	
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	'<td colspan="2"><a href="#" style="text-decoration:underline;" onclick="hideShowAddressbookLandmark()">Addressbook</a></td></tr>';
	
	htmltext_landmark +=	'<tr class="addressbook_tr_l" style="display:none;">';
	htmltext_landmark +=	'<td style="vertical-align: middle !important;">Group<?php //echo $this->lang->line("Device_Name"); ?>';
	htmltext_landmark +=	'<br/><select id="l_addressbook_group<?php echo time(); ?>" class="select ui-widget-content ui-corner-all" name="addressbook_group" onchange="filterAddressbookLandmark(this.value)"><option value="">Select Group</option><?php echo $addressbookGroupOpt; ?></select></td>';
	htmltext_landmark +=	'<td style="vertical-align: middle !important;">Addressbook<?php //echo $this->lang->line("Device_Name"); ?><br/>';
	htmltext_landmark +=	'<select id="l_addressbook<?php echo time(); ?>" class="select ui-widget-content ui-corner-all" style="height:65px;" name="addressbook" multiple="multiple"><?php echo $addressbookOpt; ?></select></td>';
	htmltext_landmark +=	'</tr>';
	
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	'<td><?php echo $this->lang->line("Group"); ?><br/>';
	htmltext_landmark +=	'<?php echo $LandmarkGroupOpt; ?></td>';
	
	htmltext_landmark +=	'<td>';
	htmltext_landmark +=	'<?php echo $this->lang->line("Dealer Code"); ?>';
	htmltext_landmark +=	'<br/>';
	htmltext_landmark +=	'<textarea id="lm_comments<?php echo time(); ?>" name="comments" class="textarea ui-widget-content ui-corner-all" style="height:15px"></textarea>';
	htmltext_landmark +=	'</td>';
	htmltext_landmark +=	'</tr>';
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	'<td><?php echo $this->lang->line("Alert Before Landmark"); ?><br/><input type="text" name="alert_before_landmark" id="alert_before_landmark<?php echo time(); ?>" class="text ui-widget-content ui-corner-all" value="0" style="width:100px;">&nbsp;KM</td>';
	htmltext_landmark +=	'<td style="padding-left:5px;"><input type="checkbox" name="landmark_sms_alert" id="landmark_sms_alert<?php echo time(); ?>" checked="true"> <?php echo $this->lang->line("Sms Alert"); ?><br/><span style="height:5px;display:block;">&nbsp;</span><input type="checkbox" name="landmark_email_alert" id="landmark_email_alert<?php echo time(); ?>" checked="true"> <?php echo $this->lang->line("Email Alert"); ?>';
	htmltext_landmark +=	'</td>';
	htmltext_landmark +=	'</tr>';
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	'<td colspan="2" align="center"><input type="button" id="btnLandCreate<?php echo time(); ?>" value="<?php echo $this->lang->line("Save"); ?>" onClick="createLanbmark<?php echo time(); ?>()">&nbsp;&nbsp;<input type="button" id="btnCancelLand<?php echo time(); ?>" value="<?php echo $this->lang->line("Clear"); ?>" onClick="clearLandSelection<?php echo time(); ?>()">&nbsp;&nbsp;<input type="button" id="btnBackLnd<?php echo time(); ?>" value="<?php echo $this->lang->line("Back"); ?>" onClick="goBackLand()"><input type="button" id="btnDeleteLand<?php echo time(); ?>" value="<?php echo $this->lang->line("Delete Landmark"); ?>" style="display:none;">';
	htmltext_landmark +=	'</td>';
	htmltext_landmark +=	'</tr>';
	htmltext_landmark +=	'</table>';
	
	htmltext_landmark += '<table class="formtable" id="land_help<?php echo time(); ?>" style="width:100%;text-align:center">';
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	'<td style="font-size:14px;"><?php echo $this->lang->line("Click_on_Map_For_Location"); ?></td>';		
	htmltext_landmark +=	'</tr>';
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	'<td style="font-size:14px;"><h4>OR</h4></td>';
	htmltext_landmark +=	'</tr>';
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	"<td style='font-size:14px;'>Vehicle Current Position : <br><select id='live_assets_cmb<?php echo time(); ?>' style='margin-top: 5px;padding: 0.4em;width: 94%;' class='select ui-widget-content ui-corner-all'><?php echo $live_combo; ?></select></td>";		
	htmltext_landmark +=	'</tr>';
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	'<td style="font-size:14px;"><input type="button" onclick="getSelectedCoords()" value="Set Landmark" style="margin-top:3px"></td>';		
	htmltext_landmark +=	'</tr>';
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	'<td style="font-size:18px;">OR</td>';		
	htmltext_landmark +=	'</tr>';
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	'<td style="font-size:14px;">Latitude : <br><input class="text ui-widget-content ui-corner-all" type="text" id="lat_2<?php echo time(); ?>"><br>Longitude : <br><input class="text ui-widget-content ui-corner-all" type="text" id="lng_2<?php echo time(); ?>"></td>';		
	htmltext_landmark +=	'</tr>';
	htmltext_landmark +=	'<tr>';
	htmltext_landmark +=	'<td style="font-size:14px;"><input type="button" onclick="manuallyCreateLandmark()" value="Set Landmark" style="margin-top:3px"></td>';		
	htmltext_landmark +=	'</tr>';
	htmltext_landmark +=	'</table>';

function hideShowAddressbookLandmark(){
	if($(".addressbook_tr_l").css("display") == "none"){
		$(".addressbook_tr_l").show();
	}else{
		$(".addressbook_tr_l").hide();
	}
}
function filterAddressbookLandmark(id){
	$.post("<?php echo base_url(); ?>index.php/home/filterAddressbook",	
		{id : id},
		function(data){
		
		$("#l_addressbook<?php echo time(); ?>").html(data.opt);
		
	}, 'json');	
}
function addLandmark(){
		
//		var context = new draggablePopup(htmltext_landmark);
		
		//document.getElementById("divPopup").innerHTML = htmltext_landmark;
		//showpopup();
		//var comboHtml="<table width='100%'><tr><td style='padding-left:50px'><h4>OR</h4></td></tr><td><select id='live_assets_cmb<?php echo time(); ?>' style='margin-top: 5px;padding: 0.4em;width: 94%;' class='select ui-widget-content ui-corner-all'><?php echo $live_combo; ?></select></td></tr><tr><td><input type='button' onclick='getSelectedCoords()' value='Set Selected' style='margin-top:3px'></td>";
		$("#dialog_landmark<?php echo time(); ?>").html(htmltext_landmark);
		
		options_lnd	= [];
		 $("#<?php echo $dCombo_land_nm; ?>").find('option').each(function() {
                options_lnd.push({value: $(this).val(), text: $(this).text()});
            });	
			
		$("#dialog_landmark<?php echo time(); ?>").dialog("open");
		jQuery("input:button, input:submit, input:reset").button();
		newLandmark = true;
		$("#land_icon_<?php echo time(); ?>").msDropDown();	
		$(".ddChild").width(150);
		$(".ddTitle").height(22);
		$(".ddTitle").width(150);
		$(".ddTitle img").height(22);	
}
function createLanbmark<?php echo time(); ?>(){
	var land_id = $("#land_id_<?php echo time(); ?>").val();
	var land_name = $("#land_name_<?php echo time(); ?>").val();
	var land_address = $("#land_address_<?php echo time(); ?>").val();
	var land_radius = $("#land_radius_<?php echo time(); ?>").val();
	var distance_unit = $("#distance_unit_<?php echo time(); ?>").val();
	
	var landmark_group_naget = $("#landmark_group_nm_<?php echo time(); ?>").val();
	var sms_alert = $("#landmark_sms_alert<?php echo time(); ?>").is(':checked');
	var email_alert = $("#landmark_email_alert<?php echo time(); ?>").is(':checked');
	var alert_before_landmark = $("#alert_before_landmark<?php echo time(); ?>").val();
	//landLng, landLng	
	if(land_name == "" || land_name == null){
		$("#alert_dialog").html('<?php echo $this->lang->line("Please_Enter_Landmark_Name"); ?>');
		$("#alert_dialog").dialog('open');
		$("#land_name_<?php echo time(); ?>").focus();
		return false;
	}
	if(landLng == ""){
		$("#alert_dialog").html('<?php echo $this->lang->line("Please click on map for location"); ?>');
		$("#alert_dialog").dialog('open');
		$("#land_name_<?php echo time(); ?>").focus();
		return false;
	}
	devId = $("#land_device_<?php echo time(); ?>").val();
	if(devId){
			devId = devId.join(",");
	}else{
		devId = '';
	}
	var addressbook_ids = $("#l_addressbook<?php echo time(); ?>").val();	
	var comments = $("#lm_comments<?php echo time(); ?>").val();	
	var sUrl = "<?php echo base_url(); ?>index.php/home/addLandmark";
	var icon = $("#land_icon_<?php echo time(); ?>").val();
	$.post(sUrl,
		{id:land_id, icon:icon, device:devId, name:land_name, address:land_address, radius:land_radius, distance_unit:distance_unit, lat:landLat, lng:landLng,group_nm :landmark_group_naget, sms_alert:sms_alert, email_alert:email_alert, addressbook_ids:addressbook_ids,comments:comments,alert_before_landmark:alert_before_landmark},
		function(data){
			$("#dialog_landmark<?php echo time(); ?>").dialog('close');
			$("#alert_dialog").html("<?php echo $this->lang->line("Landmark Saved Successfully"); ?>");
			$("#alert_dialog").dialog('open');
			refreshLandmark();			
	});		
}
function refreshLandmark(){
	$.post("<?php echo base_url(); ?>index.php/home/refreshLandmark",		
		function(data){
		clearLandmarkOverlays();
		clearLandSelection<?php echo time(); ?>();
		for(i=0; i<data.coords.length; i++) {
			var text = "Name : "+data.coords[i].name+"<br>";
			text += "Address : "+data.coords[i].address+"<br>";
			text += "Assets : "+data.coords[i].assets+'<br>';
			text += "<a style='cursor:pointer;text-decoration:underline;' onclick='removeLandmark("+data.coords[i].id+")'><?php echo $this->lang->line("Remove"); ?></a>";			
			var point = new google.maps.LatLng(data.coords[i].lat, data.coords[i].lng);
			landMarkerArray.push(createMarkerLandmark(data.coords[i].id, i, map_landmark, point, data.coords[i].name, text, data.coords[i].icon_path, '', "sidebar_map", '' ));
			DrawCircle(point, data.coords[i].radius, data.coords[i].distance_unit, map_landmark);
			newLandmark = false;
		}
	}, 'json');	
}
function clearLandSelection<?php echo time(); ?>(){
	for (i in clickPoint) {
	  clickPoint[i].setMap(null);
	}
	clickPoint = [];
	landLat = "";
	landLng = "";
}

function searchComb_lnd()
{	
	var search_lnd = $.trim($("#search_c_1_lnd<?php echo time(); ?>").val());
    var regex = new RegExp(search_lnd,"gi");
	for(i=0;i<options_lnd.length;i++)
	{
		var option = options_lnd[i];
		if(option.text.match(regex) !== null) {
			if ($('#<?php echo $dCombo_land_nm; ?> option:contains('+option.text+')').attr('selected')) {
				$('#<?php echo $dCombo_land_nm; ?> option:contains('+option.text+')').attr('selected', false);
				$('#<?php echo $dCombo_land_nm; ?> option:contains('+option.text+')').attr('selected', 'selected');
			} else {
				$('#<?php echo $dCombo_land_nm; ?> option:contains('+option.text+')').attr('selected', 'selected');
				$('#<?php echo $dCombo_land_nm; ?> option:contains('+option.text+')').attr('selected', false);
			}
			$("#search_c_1_lnd<?php echo time(); ?>").removeClass("error_sel");
			$("#search_c_1_lnd<?php echo time(); ?>").addClass("not_err");
			break;
		}
		else
		{
			$("#search_c_1_lnd<?php echo time(); ?>").removeClass("not_err");
			$("#search_c_1_lnd<?php echo time(); ?>").addClass("error_sel");
		}
	}
}

function showpopup()
{
	document.getElementById("styled_popup").style.display="block"
}
function onLoadmap() {
	var mapObjmap = document.getElementById("map_landmark");
	if (mapObjmap != 'undefined' && mapObjmap != null) {

	mapOptionsmap = {
		zoom: 8,
		mapTypeId: google.maps.MapTypeId.HYBRID,
		mapTypeControl: true,
		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DEFAULT}
	};

	mapOptionsmap.center = new google.maps.LatLng(
		22.297744,
		70.792444
	);
	
	map_landmark = new google.maps.Map(mapObjmap,mapOptionsmap);
	map_landmark.enableKeyDragZoom();
	lbounds = new google.maps.LatLngBounds();
	geocoder = new google.maps.Geocoder();
	google.maps.event.addListener(map_landmark, "click", function(event) {
        if(newLandmark == true){
			$("#land_main<?php echo time(); ?>").show();
			$("#land_help<?php echo time(); ?>").hide();
			$("#dialog_landmark<?php echo time(); ?>").dialog("open");
			for (i in clickPoint) {
			  clickPoint[i].setMap(null);
			}
			
			marker = new google.maps.Marker({
			  position: event.latLng,
			  map: map_landmark
			});
			landLat = event.latLng.lat();
			landLng = event.latLng.lng();
			clickPoint.push(marker);
		}
    });
	// Create the DIV to hold the control and call the TrackControl() constructor
  	// passing in this DIV.
		
	var trackControlDiv = document.createElement('DIV');
	var trackControl = new TrackControl(trackControlDiv, map_landmark);
	trackControlDiv.index = 1;
	map_landmark.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackControlDiv);
	
	var trackControlDiv = document.createElement('DIV');
	var trackControl = new TrackControl_s(trackControlDiv, map_landmark);
	trackControlDiv.index = 1;
	map_landmark.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackControlDiv);
	<?php
	$i = 0;
	if(count($coords) > 0) {
		foreach ($coords as $coord) {
			$distance_unit = $coord->distance_unit;
			$text = "Name : ".$coord->name."<br/>";
			$text .= "Address : ".$coord->address."<br/>";
			$text .= "Assets : ".$coord->assets."<br/>";
			$text .= "<a style=\'cursor:pointer;text-decoration:underline;\' onclick=\'removeLandmark(".$coord->id.")\'>Remove</a>";			 				
	?>				
			var point = new google.maps.LatLng(<?php echo floatval($coord->lat); ?>, <?php echo floatval($coord->lng); ?>);
			<?php if(isset($landmark_id) && $landmark_id!="" && $coord->id==$landmark_id){ ?>
			landmark_e_point=point;
			<?php } ?>
			landMarkerArray.push(createMarkerLandmark(<?php echo $coord->id; ?>, <?php echo $i; ?>, map_landmark, point, "<?php echo $coord->name; ?>", "<?php echo $text; ?>", '<?php echo $coord->icon_path; ?>', '', "sidebar_map", '' ));
			DrawCircle(point, '<?php echo $coord->radius; ?>', '<?php echo $distance_unit; ?>', map_landmark);
			lbounds.extend(point);
	<?php
		$i++;
		} // End For Loop
	}
	?>
	<?php if(count($coords) > 1) { ?>
	map_landmark.fitBounds(lbounds);
	<?php } ?>
  }
}
function goBackLand(){
	$("#land_main<?php echo time(); ?>").hide();
	$("#land_help<?php echo time(); ?>").show();
}
function getSelectedCoords(){
			
			for (i in clickPoint){
			  clickPoint[i].setMap(null);
			}
			var latLng_1=$("#live_assets_cmb<?php echo time(); ?>").val().split(",");
			if(latLng_1[0]!=0 && latLng_1[1]!=0){
				$("#land_main<?php echo time(); ?>").show();
				$("#land_help<?php echo time(); ?>").hide();
				$("#dialog_landmark<?php echo time(); ?>").dialog("open");
				var lt1=latLng_1[0];
				var lng1=latLng_1[1];
				marker = new google.maps.Marker({
				  position: new google.maps.LatLng(lt1, lng1),
				  map: map_landmark
				});
				map_landmark.setCenter(new google.maps.LatLng(lt1, lng1));
				landLat = lt1;
				landLng = lng1;
				clickPoint.push(marker);
			}
			else{
				alert("<?php echo $this->lang->line("Location_not_Found"); ?>");
			}
			
}
function manuallyCreateLandmark(){
			var lat_2 = $("#lat_2<?php echo time(); ?>").val();
			var lng_2 = $("#lng_2<?php echo time(); ?>").val();
			
			if(lat_2 != "" && lat_2 != 0 && lng_2 != "" && lng_2 != 0){
				for (i in clickPoint){
				  clickPoint[i].setMap(null);
				}
				$("#land_main<?php echo time(); ?>").show();
				$("#land_help<?php echo time(); ?>").hide();
				$("#dialog_landmark<?php echo time(); ?>").dialog("open");
				var lt1=lat_2;
				var lng1=lng_2;
				marker = new google.maps.Marker({
				  position: new google.maps.LatLng(lt1, lng1),
				  map: map_landmark
				});
				map_landmark.setCenter(new google.maps.LatLng(lt1, lng1));
				landLat = lt1;
				landLng = lng1;
				clickPoint.push(marker);
			}
			else{
				alert("<?php echo "Please Insert Latitude and Longitude";//$this->lang->line("Location_not_Found"); ?>");
			}
			
}
function editLandmark(id,point){
	
	//$("#loading_dialog").dialog('open');
	map_landmark.setCenter(point);
	map_landmark.setZoom(12);
	$("#loading_top").css("display","block");
	$.post("<?php echo base_url(); ?>index.php/home/edit_landmark", { id: id },
	 function(result) {
		$("#dialog_landmark<?php echo time(); ?>").html(htmltext_landmark);
		$("#land_main<?php echo time(); ?>").show();
		$("#land_help<?php echo time(); ?>").hide();
		options_lnd	= [];
		 $("#<?php echo $dCombo_land_nm; ?>").find('option').each(function() {
                options_lnd.push({value: $(this).val(), text: $(this).text()});
            });	
			
		$("#dialog_landmark<?php echo time(); ?>").dialog("open");
		jQuery("input:button, input:submit, input:reset").button();
		
		$("#land_id_<?php echo time(); ?>").val(result.data.id);
		$("#land_name_<?php echo time(); ?>").val(result.data.name);
		$("#land_address_<?php echo time(); ?>").val(result.data.address);
		$("#land_radius_<?php echo time(); ?>").val(result.data.radius);
		$("#distance_unit_<?php echo time(); ?>").val(result.data.distance_unit);
		$("#land_icon_<?php echo time(); ?>").val(result.data.icon_path);
		$("#landmark_group_nm_<?php echo time(); ?>").val(result.data.group_id);
		var devc = result.data.device_ids;
		devc = devc.split(",");
		$("#land_device_<?php echo time(); ?>").val(devc);
		$("#lm_comments<?php echo time(); ?>").val(result.data.comments);
		if(result.data.sms_alert == 0)
			$("#landmark_sms_alert<?php echo time(); ?>").attr("checked", false);
		if(result.data.email_alert == 0)
			$("#landmark_email_alert<?php echo time(); ?>").attr("checked", false);
		
		$("#alert_before_landmark<?php echo time(); ?>").val(result.data.alert_before_landmark);
		
		var addressbook_ids = result.data.addressbook_ids;
		if(addressbook_ids != "" && addressbook_ids != null){
			addressbook_ids = addressbook_ids.split(",");
			$("#l_addressbook<?php echo time(); ?>").val(addressbook_ids);
		}else{
			$("#l_addressbook<?php echo time(); ?>").val('');
		}
		landLng = result.data.lng;
		landLat = result.data.lat;
		$("#btnBackLnd<?php echo time(); ?>").hide();	
		$("#btnCancelLand<?php echo time(); ?>").hide();	
		$("#btnDeleteLand<?php echo time(); ?>").show();	
		$("#btnDeleteLand<?php echo time(); ?>").click(function(){
			removeLandmark(result.data.id);
		})
		$("#land_icon_<?php echo time(); ?>").msDropDown();	
		$(".ddChild").width(150);
		$(".ddTitle").height(22);
		$(".ddTitle").width(150);
		$(".ddTitle img").height(22);
	//$("#loading_dialog").dialog('close');
	$("#loading_top").css("display","none");
	}, 'json');
}
function DrawCircle(center, rad, dUnit, map){
	
	if(dUnit == "KM")
		rad *= 1000; // convert to meters if in km
	if(dUnit == "Mile")
		rad *= (1000 * 1.609344); // convert to meters if in km
	if(dUnit == "Meter")
		rad = parseInt(rad); // convert to meters if in km
    /*if (draw_circle != null) {
        draw_circle.setMap(null);
    }*/
    draw_circle = new google.maps.Circle({
        center: center,
        radius: rad,
        strokeColor: "#FF0000",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#FF0000",
        fillOpacity: 0.35,
        map: map
    });
	circleArray.push(draw_circle);
}

function removeLandmark(id){
	$("#landmark_confirm_dialog<?php echo time(); ?>").dialog('open');
	landmark_selected_id<?php echo time(); ?> = id;
}
function confirmDeleteLandmark(){
	$.post("<?php echo base_url(); ?>index.php/home/removeLandmark", { id: landmark_selected_id<?php echo time(); ?> },
	 function(data) {
		$("#alert_dialog").html("<?php echo $this->lang->line("Landmark Deleted Successfully"); ?>");
		$("#alert_dialog").dialog("open");
		$("#dialog_landmark<?php echo time(); ?>").dialog('close');
		refreshLandmark();
	});

}


function createMarkerLandmark(lid, ic, map, point, title, html, icon, icon_shadow, sidebar_id, openers, openInfo){
	
	var marker_options = {
		position: point,
		map: map,
		title: title};  
	if(icon!=''){marker_options.icon = "<?php echo base_url(); ?>" + icon;}
	if(icon_shadow!=''){marker_options.icon_shadow = "<?php echo base_url(); ?>assets/marker-images/" + icon_shadow;}
	//create marker
	var new_marker = new google.maps.Marker(marker_options);
	if(html!=''){
		html = "<div id='eLand_<?php echo time(); ?>_"+ic+"' class='eland'>"+html+"</div>";
		
		google.maps.event.addListener(new_marker, 'click', function() {			
			editLandmark(lid,point);
		});
		
	}
	return new_marker;  
}

onLoadmap();
function clearLandmarkOverlays() {
  
  if (landMarkerArray) {
    for (i in landMarkerArray) {
      landMarkerArray[i].setMap(null);
    }
  }
  if (circleArray) {
    for (i in circleArray) {
      circleArray[i].setMap(null);
    }
  }
  circleArray = [];
  landMarkerArray = [];
  
}
function selectAlllandmark<?php echo time(); ?>(id){
	if($(id+" option:selected").length == $(id+" option").length){
		$(id+" option").removeAttr('selected');
	}else{
		$(id+" option").attr('selected', 'selected');
	}
}

$(document).ready(function (){

	$("#dialog_landmark<?php echo time(); ?>").dialog({
		autoOpen: false,
		draggable: true,
		resizable: true,
		modal: false,
		width:'350',
		position:['right',0],
		title:'<?php echo $this->lang->line("Create_Landmark"); ?>'
	});
	$("#landmark_confirm_dialog<?php echo time(); ?>").dialog({
		autoOpen: false,
      buttons : {
        "Confirm" : function() {
          confirmDeleteLandmark();
		  $(this).dialog("close");
        },
        "Cancel" : function() {
          $(this).dialog("close");
		  landmark_selected_id<?php echo time(); ?> = '';
        }
      }
    });
	
	$(function() {
		$("#address_landmark").autocomplete({
		  //This bit uses the geocoder to fetch address values
		  source: function(request, response) {
			geocoder.geocode( {'address': request.term }, function(results, status) {
			  response($.map(results, function(item) {
				return {
				  label:  item.formatted_address,
				  value: item.formatted_address,
				  latitude: item.geometry.location.lat(),
				  longitude: item.geometry.location.lng()
				}
			  }));
			})
		  },
		  //This bit is executed upon selection of an address
		  select: function(event, ui) {
			var location = new google.maps.LatLng(ui.item.latitude, ui.item.longitude);       
			map_landmark.setCenter(location);
			map_landmark.setZoom(11);
		  }
		});
	  });
	<?php if(isset($landmark_id) && $landmark_id!=""){ ?>
	 	  editLandmark(<?php echo $landmark_id; ?>,landmark_e_point);
	<?php } ?>
});
</script>
<div id="dialog_landmark<?php echo time(); ?>" style="display:none">
</div>
<div id="landmark_confirm_dialog<?php echo time(); ?>" style="display:none"><?php echo $this->lang->line("Do you want to delete this record"); ?>?
</div>
<div id="search_landmark" class="formtable" style="padding-bottom:5px; display:none;">
<label><?php echo $this->lang->line("Address"); ?>: </label><input id="address_landmark" style="width:250px;" class="text ui-widget-content ui-corner-all" type="text"/>
</div>
<div id="map_landmark" style="width: 100%; height: 100%; position:relative;"></div>
<?php  echo $onload; ?>
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