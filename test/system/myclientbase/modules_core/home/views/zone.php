<?php
	$uid = $this->session->userdata('usertype_id');
	$profile_id = $this->session->userdata('profile_id');
	if($uid==1)
		$data = array("Search","Create Zone");
	else
	{
		$data = array();
		$va1l = $this->db;
		$va1l->select("setting_name");
		$va1l->where("profile_id",$profile_id);
		$va1l->where("setting_name !=",'main');
		$va1l->where("menu_id",'2');
		$va1l ->where("del_date",NULL);
		$res_val = $va1l->get("mst_user_profile_setting");
		foreach($res_val ->result_array() as $row)
		{
			$data[] = $row['setting_name'];
		}
	
	}

	$user = $this->session->userdata('user_id');
	$SQL = "SELECT country_lati,country_longi FROM tbl_users where user_id = '$user'";
	$query = $this->db->query($SQL);
	$row = $query->row();
	if(count($row)){
		$lati =  $row->country_lati;
		$longi =  $row->country_longi;
	} else {
		$lati =  22.297744;
		$longi =  70.792444;
	}
?>
<script type="text/javascript">
loadColorSelection();
loadInfoBubble();
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
<style>
	.ui-autocomplete {
		background-color: white;
		width: 300px;
		border: 1px solid #cfcfcf;
		list-style-type: none;
		padding-left: 0px;
	}
</style>
<?php
$dCombonm="cmbGroup_zone_".time();
$dCombo = "<select class='select ui-widget-content ui-corner-all' id='cmbGroup_zone_".time()."' style='height:100px;' name='cmbGroup' multiple='multiple'>";
$dCombo .= $deviceGrp;
$dCombo .= "</select>";
$dCombo_land = "<select id='land_device_".time()."' style='height:100px;' name='land_device' multiple='multiple'>";

$dCombo_land .= $deviceGrp;

$dCombo_land .= "</select>";


//path to directory to scan
$directory = "assets/zone_images/";
 
//get all image files with a .jpg extension.
$images = glob($directory . "{*.jpg,*.gif,*.png}", GLOB_BRACE);
 
//print each file name
$iconOpt = '';
foreach($images as $image)
{
	$iconOpt .= '<option title="'.base_url().$image.'" value="'.$image.'"></option>';
}
$icon_combo = '<select id="land_icon_'.time().'">';

$icon_combo .= $iconOpt;

$icon_combo .= '</select>';
?>
<style>
	
	#poly_mainZone<?php echo time(); ?> td{
		padding:2px;
	}
	.error_sel {
		border:1px solid red;
	}
	.not_err {
		border:1px solid black;
	}
</style>

<script type="text/javascript" charset="utf-8">

var sidebar_htmlmapZone  = '';
var marker_htmlmapZone  = [];
var marker_cobmo_arrZone = [];

var polylinesmapZone = [];
var polylineCoordsmapZone = [];
var map_geoZone = null;
var mapOptionsmapZone;

var polyVarrZone = [];
var labelArrZone = [];
var abounds;
function TrackControl_L(controlDiv, map_geoZone) {
<?php
	if(in_array('Create Area',$data)){
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
  control_UI.title = 'Click to Create Zone';
  controlDiv.appendChild(control_UI);
  // Set CSS for the control interior
  var zoneText = document.createElement('DIV');
  zoneText.style.fontFamily = 'Arial,sans-serif';
  zoneText.style.fontSize = '12px';
  zoneText.style.height = '20px';
  zoneText.style.paddingTop = '3px';
  zoneText.style.paddingLeft = '4px';
  zoneText.style.paddingRight = '4px';
  zoneText.innerHTML = 'Zone';
  control_UI.appendChild(zoneText);
  
  google.maps.event.addDomListener(control_UI, 'click', function() {
	addZoneNew();
  });
  <?php } ?>
}
function TrackControl_s(controlDiv, map_geoZone) {
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
	if($("#search_zone").css('display') == 'block'){
		$("#search_zone").css('display', 'none');
	}else{
		$("#search_zone").css('display', 'block');
	}	
  });
   <?php } ?>
}
var path = new google.maps.MVCArray;
var poly;
var glob_poly;
var polyMarkersZone = [];
var polyLattZone = [];
var polyLngZone = [];
var newZone = false;
var deviceCombo = "<?php echo $dCombo; ?>";
var options = [];
var htmltextZone = '<table class="formtable" id="poly_mainZone<?php echo time(); ?>" style="display:none">';
	htmltextZone +=	'<input type="hidden" id="poly_id_Zone<?php echo time(); ?>">';
	htmltextZone +=	'<tr>';
	htmltextZone +=	'<td><?php echo $this->lang->line("Zone_Name");  ?></td>';
	htmltextZone +=	'<td><input type="text" class="text ui-widget-content ui-corner-all" style="width:100%" id="txtPoly1<?php echo time(); ?>" name="txtPoly1" onmouseover="this.focus();"></td>';
	htmltextZone +=	'</tr>';
	htmltextZone +=	'<tr>';
	htmltextZone +=	'<td><?php echo $this->lang->line("Zone_Color"); ?></td>';
	htmltextZone +=	'<td><input type="text" name="color" id="color<?php echo time(); ?>" value="#ff0000" class="color-picker text ui-widget-content ui-corner-all" size="6" autocomplete="on" maxlength="10" /></td>';
	htmltextZone +=	'</tr>';
	htmltextZone +=	'<tr>';
	htmltextZone +=	'<td><?php echo $this->lang->line("Search"); ?></td>';
	htmltextZone +=	'<td><input type="text" class="text ui-widget-content ui-corner-all" style="width:100%" class="not_err" name="search_c" id="search_c_1Zone<?php echo time(); ?>" onKeyUp="searchCombZone()" /></td>';
	htmltextZone +=	'</tr>';
	htmltextZone +=	'<tr style="line-height:10px">';
	htmltextZone +=	'<td>&nbsp;</td>';
	htmltextZone +=	'<td><span><a href="#" style="float:right;margin-right:15px;color:blue;font-size:10px" onclick="selectAllZone<?php echo time(); ?>(\'<?php echo "#cmbGroup_zone_".time(); ?>\')"><?php echo $this->lang->line("Select/Unselect All"); ?></a></span>';
	htmltextZone += '</td>';
	htmltextZone +=	'</tr>';
	htmltextZone +=	'<tr>';
	htmltextZone +=	'<td style="vertical-align: middle !important;"><?php echo $this->lang->line("group_name"); ?></td>';
	htmltextZone +=	'<td>';
	deviceCombo = deviceCombo.replace(/ selected/g,'');
	htmltextZone += deviceCombo;
	
	htmltextZone += '</td>';
	htmltextZone +=	'</tr>';
	
/*	
	htmltextZone +=	'<tr><td colspan="2"><a href="#" style="text-decoration:underline;" onclick="hideShowAddressbookZone()">Addressbook</a></td></tr>';
	
	htmltextZone +=	'<tr class="addressbook_trZone" style="display:none;">';
	htmltextZone +=	'<td style="vertical-align: middle !important;">Group<?php //echo $this->lang->line("Device_Name"); ?></td>';
	htmltextZone +=	'<td><select id="addressbook_group<?php echo time(); ?>" class="select ui-widget-content ui-corner-all" name="addressbook_group" onchange="filterAddressbookZone(this.value)"><option value="">Select Group</option><?php echo $addressbookGroupOpt; ?></select></td>';
	htmltextZone +=	'</tr>';
	
	htmltextZone +=	'<tr class="addressbook_trZone" style="display:none;">';
	htmltextZone +=	'<td style="vertical-align: middle !important;">Addressbook<?php //echo $this->lang->line("Device_Name"); ?></td>';
	htmltextZone +=	'<td><select id="addressbookZone<?php echo time(); ?>" class="select ui-widget-content ui-corner-all" style="height:100px;" name="addressbook" multiple="multiple"><?php echo $addressbookOpt; ?></select></td>';
	htmltextZone +=	'</tr>';	
	
	htmltextZone +=	'<tr>';
	htmltextZone +=	'<td style="vertical-align: middle !important;">Zone Type</td>';
	htmltextZone +=	'<td><select id="in_zone_optZone<?php echo time(); ?>" class="select ui-widget-content ui-corner-all" name="area_type_opt"><option value="Dealer">Dealer</option><option value="Tall Tax">Tall Tax</option><option value="Others">Others</option><option value="All">All</option></select></td>';
	htmltextZone +=	'</tr>';
*/	
	htmltextZone +=	'<tr>';
	htmltextZone +=	'<td colspan="2"><?php echo $this->lang->line("In Alert"); ?>  &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="in_alert" id="in_alertZone<?php echo time(); ?>" checked="true">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line("Out Alert"); ?>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="out_alert" id="out_alertZone<?php echo time(); ?>" checked="true"><br>';
	htmltextZone +=	'</td>';
	htmltextZone +=	'</tr>';
	htmltextZone +=	'<tr>';
	htmltextZone +=	'<td colspan="2"><?php echo $this->lang->line("Sms Alert"); ?> <input type="checkbox" name="zone_sms_alertZone" id="zone_sms_alertZone<?php echo time(); ?>" checked="true">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line("Email Alert"); ?> <input type="checkbox" name="zone_email_alertZone" id="zone_email_alertZone<?php echo time(); ?>" checked="true"><br>';
	htmltextZone +=	'</td>';
	htmltextZone +=	'</tr>';
	htmltextZone +=	'<tr>';
	htmltextZone +=	'<td colspan="2" align="center"><input type="button" id="btnCreateZone<?php echo time(); ?>" value="<?php echo $this->lang->line("Create"); ?>" onClick="createZone()" style="width:35%">&nbsp;&nbsp;<input type="button" id="btnupdateZone<?php echo time(); ?>" value="<?php echo $this->lang->line("Save"); ?>" onClick="updateZone<?php echo time(); ?>()" style="display:none;">&nbsp;<input type="button"  style="width:35%" id="btnCancelPoly<?php echo time(); ?>" value="<?php echo $this->lang->line("Clear"); ?>" style="width:35%" onClick="clearSelectionZone<?php echo time(); ?>()"><input type="button" id="btnDeleteZone<?php echo time(); ?>" value="<?php echo $this->lang->line("Delete Zone"); ?>" style="display:none;">';
	htmltextZone +=	'</td>';
	htmltextZone +=	'</tr>';	
	htmltextZone +=	'<tr>';
	htmltextZone +=	'<td colspan="2" align="center"><input type="button" id="btnHelpZone<?php echo time(); ?>" value="<?php echo $this->lang->line("Help"); ?>" onClick="helpZone()" style="width:35%">&nbsp;&nbsp;&nbsp;<input type="button" id="btnbck_dZone<?php echo time(); ?>" value="<?php echo $this->lang->line("Back"); ?>" onClick="dialog_backbtnZone()" style="width:35%">';
	htmltextZone +=	'</td>';
	htmltextZone +=	'</tr>';
	htmltextZone +=	'</table>';
	
	htmltextZone += '<table id="poly_helpZone<?php echo time(); ?>" style="display:none">';
	htmltextZone +=	'<tr>';
	htmltextZone +=	'<td align="right"><input type="button" value="<?php echo $this->lang->line("Back"); ?>" onClick="mainZone()">';
	htmltextZone +=	'</td>';
	htmltextZone +=	'</tr>';
	htmltextZone +=	'<tr>';
	htmltextZone +=	'<td style="line-height:25px;">Step 1 : Insert Zone Name"); ?><br>Step 2 : Select devices you want to bind with this zone"); ?><br><?php echo $this->lang->line("Step_3_:_Click_on_Create"); ?><br>Step 4 : Click on Map And Creat Zone Minimun Three Point Require"<br><?php echo $this->lang->line("Step_5_:_Click_On_InseFrt_Button"); ?></td>';		
	htmltextZone +=	'</tr>';
	htmltextZone +=	'</table>';
	
	htmltextZone += '<table id="poly_other_optZone<?php echo time(); ?>">';
	htmltextZone +=	'<tr>';
	htmltextZone +=	'<td style="font-size:14px;"><input type="button" onclick="creatnewZone()" value="Create New" style="margin-top:3px"></td>';		
	htmltextZone +=	'</tr>';
	htmltextZone +=	'<tr>';
	htmltextZone +=	'<td style="font-size:14px;padding-left:50px"><h4 style="margin-bottom: 3px; margin-top: 5px;">OR</h4></td>';		
	htmltextZone +=	'</tr>';
	htmltextZone +=	'<tr>';
	htmltextZone +=	"<td style='font-size:14px;'><select id='live_assets_cmb_zone<?php echo time(); ?>' style='margin-top: 5px;padding: 0.4em;width: 94%;' class='select ui-widget-content ui-corner-all'><?php echo $live_combo; ?></select></td>";		
	htmltextZone +=	'</tr>';
	htmltextZone +=	'<tr>';
	htmltextZone +=	'<td style="font-size:14px;"><input type="button" onclick="addPointZonefrm_ComboZone()" value="Set Selected" style="margin-top:3px"></td>';		
	htmltextZone +=	'</tr>';
	htmltextZone +=	'</table>';
function hideShowAddressbookZone(){
	if($(".addressbook_trZone").css("display") == "none"){
		$(".addressbook_trZone").show();
	}else{
		$(".addressbook_trZone").hide();
	}
}
function selectAllZone<?php echo time(); ?>(id){
	if($(id+" option:selected").length == $(id+" option").length){
		$(id+" option").removeAttr('selected');
	}else{
		$(id+" option").attr('selected', 'selected');
	}
}
function selectAllZone_select<?php echo time(); ?>(id){
	$(id+" option").attr('selected', 'selected');
}
function creatnewZone(){
	$("#poly_mainZone<?php echo time(); ?>").show();
	$("#poly_other_optZone<?php echo time(); ?>").hide();
	$("#poly_helpZone<?php echo time(); ?>").hide();
	$("#btnCreateZone<?php echo time(); ?>").val('<?php echo $this->lang->line("Create"); ?>');
	newZone = false;
	clearMarkerZone();
	selectAllZone_select<?php echo time(); ?>("#cmbGroup_zone_<?php echo time(); ?>");
}
function dialog_backbtnZone(){
	$("#poly_mainZone<?php echo time(); ?>").hide();
	$("#poly_other_optZone<?php echo time(); ?>").show();
	$("#poly_helpZone<?php echo time(); ?>").hide();
	$("#btnCreateZone<?php echo time(); ?>").val('<?php echo $this->lang->line("Create"); ?>');
	newZone = false;
	clearMarkerZone();
	clearSelectionZone<?php echo time(); ?>();
}
function addZoneNew()
	{
		$("#dialog_zone<?php echo time(); ?>").html(htmltextZone);
		$("#dialog_zone<?php echo time(); ?>").dialog("open");
		jQuery("input:button, input:submit, input:reset").button();
		//context.show();
				$(".color-picker").miniColors({
					letterCase: 'uppercase',
					/*change: function(hex, rgb) {
						logData(hex, rgb);
					}*/
				});		
				
		options.length=0;
		 $("#<?php echo $dCombonm; ?>").find('option').each(function() {
                options.push({value: $(this).val(), text: $(this).text()});
            });		

}
function helpZone(){
	$("#poly_mainZone<?php echo time(); ?>").hide();
	$("#poly_helpZone<?php echo time(); ?>").show();
	$("#poly_other_optZone<?php echo time(); ?>").hide();
}
function mainZone(){
	$("#poly_mainZone<?php echo time(); ?>").show();
	$("#poly_helpZone<?php echo time(); ?>").hide();
	$("#poly_other_optZone<?php echo time(); ?>").hide();
}
var measure;
function onLoadmap() {
	var mapObjmap = document.getElementById("map_geoZone");
	if (mapObjmap != 'undefined' && mapObjmap != null) {

	mapOptionsmapZone = {
		zoom: 8,
		mapTypeId: google.maps.MapTypeId.HYBRID,
		mapTypeControl: true,
		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DEFAULT}
	};

	mapOptionsmapZone.center = new google.maps.LatLng(
		22.297744,
		70.792444
	);
	
	map_geoZone = new google.maps.Map(mapObjmap,mapOptionsmapZone);
	map_geoZone.enableKeyDragZoom();	
	
	
	// Create a meausure object to store our markers, MVCArrays, lines and polygons
	measure = {
		mvcLine: new google.maps.MVCArray(),
		mvcPolygon: new google.maps.MVCArray(),
		mvcMarkers: new google.maps.MVCArray(),
		line: null,
		polygon: null
	};
	
	geocoder = new google.maps.Geocoder();
	// Create the DIV to hold the control and call the TrackControl() constructor
  	// passing in this DIV.
	
	var TrackControlDiv = document.createElement('DIV');
	var TrackControl = new TrackControl_L(TrackControlDiv, map_geoZone);
	TrackControlDiv.index = 1;
	map_geoZone.controls[google.maps.ControlPosition.TOP_RIGHT].push(TrackControlDiv);
	
	var TrackControlDiv = document.createElement('DIV');
	var TrackControl = new TrackControl_s(TrackControlDiv, map_geoZone);
	TrackControlDiv.index = 1;
	map_geoZone.controls[google.maps.ControlPosition.TOP_RIGHT].push(TrackControlDiv);
	
	abounds = new google.maps.LatLngBounds();	
	
	<?php
	$i = 0;
	foreach($plyId as $pIdv){
	?>
		var bounds = new google.maps.LatLngBounds();
	<?php
		$pathArrZone = array();
		
		for($j=0; $j<count($plyLat[$pIdv]); $j++){
			$pathArrZone[] = 'new google.maps.LatLng('.sprintf("%.6f", $plyLat[$pIdv][$j]).', '.sprintf("%.6f", $plyLng[$pIdv][$j]).')';
		}
		$pathString = implode(",", $pathArrZone);
		
		if(count($plyDev[$pIdv]) > 0){
			$devices = implode("<br>", $plyDev[$pIdv]);
		}
		?>
		
		
		var polygonCoords = [<?php echo $pathString; ?>];

		for (i = 0; i < polygonCoords.length; i++){
		  bounds.extend(polygonCoords[i]);
		}
		//var devices = 'plyDev'
		label<?php echo $i; ?> = new ELabel({
		latlng: bounds.getCenter(), 
		label: "<div class='elable' id='elable_<?php echo $i; ?>' style='z-index:99999;border:2px solid red;padding:10px;width:auto;background-color:#000;color:#fff;'><?php echo $plyName[$pIdv][0]; ?></div>", 
		classname: "label", 
		offset: 0, 
		opacity: 100, 
		overlap: true,
		clicktarget: false
		});
		
		labelArrZone.push(label<?php echo $i; ?>);
		<?php if($show_zone_name == 1){ ?>
			label<?php echo $i; ?>.setMap(map_geoZone);
		<?php } ?>
		abounds.extend(bounds.getCenter());
		
		var polyV<?php echo $i; ?> = new google.maps.Polygon({
		      paths: [<?php echo $pathString; ?>],
		      strokeWeight: 2,
		      strokeOpacity : 0.6,
		      fillColor: '<?php echo $plyColor[$pIdv]; ?>'
		    });
		polyV<?php echo $i; ?>.setMap(map_geoZone);
		
		<?php if($area_id!="" && $area_id==$pIdv){ ?>
			glob_poly=polyV<?php echo $i; ?>;
		<?php } ?>
		
		polyVarrZone.push(polyV<?php echo $i; ?>);
		google.maps.event.addListener(polyV<?php echo $i; ?>,"click",function(event){
			editZone(<?php echo $pIdv; ?>,polyV<?php echo $i; ?>);
			//$(".elable").hide();
			//$("#dialog_zone_det<?php echo time(); ?>").dialog('open');
			//$("#elable_<?php echo time(); ?>_<?php echo $i; ?>").show();
			 //this.setOptions({fillColor: "#00FF00"});
			 //label<?php echo $i; ?>.setMap(map_geoZone);
			 //$("#elable_<?php echo $i; ?>").parent().parent().css('z-index','99999');
		});
		google.maps.event.addListener(polyV<?php echo $i; ?>,"click",function(event){
			google.maps.event.trigger(map_geoZone, 'click');
		});
		<?php if($show_zone_name == 0){ ?>
		google.maps.event.addListener(polyV<?php echo $i; ?>,"mouseover",function(event){
			label<?php echo $i; ?>.setMap(map_geoZone);
			$("#elable_<?php echo $i; ?>").parent().parent().css('z-index','99999');
		});
		google.maps.event.addListener(polyV<?php echo $i; ?>,"mouseout",function(event){
			label<?php echo $i; ?>.setMap(null);
		});
		<?php } ?>		
	<?php $i++; } ?>
		
	poly = new google.maps.Polygon({
	      strokeWeight: 2,
	      strokeOpacity : 0.6,
		  clickable:true,
	      fillColor: '#ff0000'
	    });
	poly.setMap(map_geoZone);
	poly.setPaths(new google.maps.MVCArray([path]));
	
	google.maps.event.addListener(map_geoZone, 'click', addPointZone);
	newZone = false;
	<?php if(count($plyId) > 1){ ?>
	map_geoZone.fitBounds(abounds);
	<?php } ?>
  }
  google.maps.Polygon.prototype.getBounds = function() {
    var bounds = new google.maps.LatLngBounds();
    var paths = this.getPaths();
    var path;        
    for (var i = 0; i < paths.getLength(); i++) {
        path = paths.getAt(i);
        for (var ii = 0; ii < path.getLength(); ii++) {
            bounds.extend(path.getAt(ii));
        }
    }
    return bounds;
	}
}
function editZone(id,edit_poly){
	//$("#loading_dialog").dialog('open');
	$("#loading_top").css("display","block");
	//alert(edit_poly.getBounds().toSource());
	zoom=16;
	map_geoZone.setCenter(edit_poly.getBounds().getCenter());
	map_geoZone.setZoom(zoom);
	$.post("<?php echo base_url(); ?>index.php/home/edit_zone", { id: id},
	 function(result) {
	 	$("#dialog_zone<?php echo time(); ?>").html(htmltextZone);
		$("#poly_mainZone<?php echo time(); ?>").show();
		$("#poly_helpZone<?php echo time(); ?>").hide();
		$("#poly_other_optZone<?php echo time(); ?>").hide();
					
		$("#dialog_zone<?php echo time(); ?>").dialog("open");
		
		options.length=0;
		$("#<?php echo $dCombonm; ?>").find('option').each(function() {
            options.push({value: $(this).val(), text: $(this).text()});
        });
			
		jQuery("input:button, input:submit, input:reset").button();
		
		$("#poly_id_Zone<?php echo time(); ?>").val(result.data.polyid);
		$("#txtPoly1<?php echo time(); ?>").val(result.data.polyname);
		$("#color<?php echo time(); ?>").val(result.data.color);
		
		var grps = result.data.group_id;
		if(grps != null && grps != '') {
			grps = grps.split(",");
			$("#cmbGroup_zone_<?php echo time(); ?>").val(grps);
		}
		if(result.data.sms_alert == 0)
			$("#zone_sms_alertZone<?php echo time(); ?>").attr("checked", false);
		if(result.data.email_alert == 0)
			$("#zone_email_alertZone<?php echo time(); ?>").attr("checked", false);
		if(result.data.in_alert == 0)
			$("#in_alertZone<?php echo time(); ?>").attr("checked", false);
		if(result.data.out_alert == 0)
			$("#out_alertZone<?php echo time(); ?>").attr("checked", false);
		
		var addressbook_ids = result.data.addressbook_ids;
		$("#in_zone_optZone<?php echo time(); ?> option[value='"+result.data.area_type_opt+"']").attr('selected','selected');
		if(addressbook_ids != "" && addressbook_ids != null){
			addressbook_ids = addressbook_ids.split(",");
			$("#addressbookZone<?php echo time(); ?>").val(addressbook_ids);
		}else{
			$("#addressbookZone<?php echo time(); ?>").val('');
		}
		
		$("#btnCancelPoly<?php echo time(); ?>").hide();
		$("#btnHelpZone<?php echo time(); ?>").hide();		
		$("#btnbck_dZone<?php echo time(); ?>").hide();		
		$("#btnDeleteZone<?php echo time(); ?>").show();	
		$("#btnDeleteZone<?php echo time(); ?>").click(function(){
			deleteZone(result.data.id);
		});
		$(".color-picker").miniColors({
					letterCase: 'uppercase',
		});
		$("#btnCreateZone<?php echo time(); ?>").hide();
		$("#btnUpdate<?php echo time(); ?>").show();
		$("#btnupdateZone<?php echo time(); ?>").show();
		
		//$("#loading_dialog").dialog('close');
		$("#loading_top").css("display","none");
		
	}, 'json');
}
function closeElable(lbl){
	labelArrZone[lbl].setMap(null);
}
function deleteZone(){
	$("#zone_confirm_dialog<?php echo time(); ?>").dialog('open');
	
}
function confirmdeleteZone(){
	var polyid = $("#poly_id_Zone<?php echo time(); ?>").val();
	$.post("<?php echo base_url(); ?>index.php/home/delete_zone", { id: polyid },
	 function(data) {
		$("#alert_dialog").html("Zone Deleted Successfully");
		$("#alert_dialog").dialog("open");
		$("#dialog_zone<?php echo time(); ?>").dialog('close');
		refreshZone();
	});

}


function addPointZonefrm_ComboZone(){
	
	clearMarkerZone();
	var latLng_1=$("#live_assets_cmb_zone<?php echo time(); ?>").val().split(",");
	lt1=latLng_1[0];
	lng1=latLng_1[1];
	if(lt1!=0 && lng1!=0){		
		var marker = new google.maps.Marker({
	      position: new google.maps.LatLng(lt1, lng1),
	      map: map_geoZone,
	    });
		marker_cobmo_arrZone.push(marker);
		map_geoZone.setCenter(new google.maps.LatLng(lt1, lng1));
		marker.setMap(map_geoZone);
		map_geoZone.setZoom(12);
		$("#poly_mainZone<?php echo time(); ?>").show();
		$("#poly_helpZone<?php echo time(); ?>").hide();
		$("#poly_other_optZone<?php echo time(); ?>").hide();
		$("#btnCreateZone<?php echo time(); ?>").val("<?php echo $this->lang->line("Save"); ?>");
		newZone = true;
		selectAllZone_select<?php echo time(); ?>("#cmbGroup_zone_<?php echo time(); ?>");
	}else{
		alert("Location not Found");
	}
	
}
function addPointZone(event) {

	if(newZone == true){
		$("#zone_size_div").show();
	    var PolygonOptions = {
	      strokeWeight: 2,
	      strokeOpacity : 0.6,
	      fillColor: $("#color<?php echo time(); ?>").val()
	    }
		poly.setOptions(PolygonOptions);
		path.insertAt(path.length, event.latLng);
	  //var img = new google.maps.MarkerImage("<?php echo base_url(); ?>assets/marker-images/kicon48.png", new google.maps.Size(10, 10), new google.maps.Point(0,0), new google.maps.Point(10, 10));
	  var image = new google.maps.MarkerImage("<?php echo base_url(); ?>assets/marker-images/kicon48.png", new google.maps.Size(24,24), new google.maps.Point(0,0), new google.maps.Point(12,12));
		var marker = new google.maps.Marker({
	      position: event.latLng,
	      map: map_geoZone,
	      draggable: true,		  
		  icon:image
	    });
		
	    polyMarkersZone.push(marker);
	    marker.setTitle("#" + path.length);
		
		//calculate area size		
		measureCalcZone();
		
	    google.maps.event.addListener(marker, 'click', function() {
	      marker.setMap(null);
	      for (var i = 0, I = polyMarkersZone.length; i < I && polyMarkersZone[i] != marker; ++i);
	      polyMarkersZone.splice(i, 1);
	      path.removeAt(i);
		  measureCalcZone();
	      }
		  
	    );

	    google.maps.event.addListener(marker, 'dragend', function() {
	      for (var i = 0, I = polyMarkersZone.length; i < I && polyMarkersZone[i] != marker; ++i);
			path.setAt(i, marker.getPosition());
			measureCalcZone();
	      }
	    );
	}
}

function createZone(event){
	btnCreateZone = document.getElementById('btnCreateZone<?php echo time(); ?>');
	if(btnCreateZone.value == "<?php echo $this->lang->line("Create"); ?>"){
		btnCreateZone.value="<?php echo $this->lang->line("Save"); ?>";
		newZone = true;
	}
	else{
		addZone<?php echo time(); ?>();
	}
}
function addZone<?php echo time(); ?>(){
	var in_alert = $("#in_alertZone<?php echo time(); ?>").is(':checked');
	var out_alert = $("#out_alertZone<?php echo time(); ?>").is(':checked');
	var sms_alert = $("#zone_sms_alertZone<?php echo time(); ?>").is(':checked');
	var email_alert = $("#zone_email_alertZone<?php echo time(); ?>").is(':checked');
	var addressbook_ids = $("#addressbookZone<?php echo time(); ?>").val();
	var area_type_opt = $("#in_zone_optZone<?php echo time(); ?>").val();
	var polyname = document.getElementById('txtPoly1<?php echo time(); ?>').value;
	//var deviceId = document.getElementById('cmbDevice').value;
	for(i=0;i<polyMarkersZone.length;i++){
		polyLattZone.push(polyMarkersZone[i].getPosition().lat());
		polyLngZone.push(polyMarkersZone[i].getPosition().lng());
	}
	if(polyname == "" || polyname == null){
		alert("Please enter Zone name");
		document.getElementById('txtPoly1<?php echo time(); ?>').focus();
		return false;
	}
	
	if(polyMarkersZone.length < 3){
		alert("Please select zone on map");
		return false;
	}
	grpId = $("#cmbGroup_zone_<?php echo time(); ?>").val();
	if(grpId)
		grpId = grpId.join(",")
	else
		grpId = '';
	var sUrl = "<?php echo base_url(); ?>index.php/home/add_zone";
	
	var area_size = $("#span-zone").text();
	$.post(sUrl,
		{in_alert:in_alert, out_alert:out_alert, sms_alert:sms_alert, email_alert:email_alert, group:grpId, name:polyname, latAdd:polyLattZone, lngAdd:polyLngZone, addressbook_ids:addressbook_ids, area_size:area_size, color:$('#color<?php echo time(); ?>').val(), area_type_opt:area_type_opt},
		function(data){
			
			clearSelectionZone<?php echo time(); ?>();
			$("#dialog_zone<?php echo time(); ?>").dialog('close');
			$("#alert_dialog").html("Zone Saved Successfully");
			$("#alert_dialog").dialog('open');
			refreshZone();
			newZone = true;
			polyLattZone = [];
			polyLngZone = [];
	});		
	
	
}
function updateZone<?php echo time(); ?>(){
	var polyid = $("#poly_id_Zone<?php echo time(); ?>").val();
	var in_alert = $("#in_alertZone<?php echo time(); ?>").is(':checked');
	var out_alert = $("#out_alertZone<?php echo time(); ?>").is(':checked');
	var sms_alert = $("#zone_sms_alertZone<?php echo time(); ?>").is(':checked');
	var email_alert = $("#zone_email_alertZone<?php echo time(); ?>").is(':checked');
	var addressbook_ids = $("#addressbookZone<?php echo time(); ?>").val();
	var area_type_opt = $("#in_zone_optZone<?php echo time(); ?>").val();
	var polyname = document.getElementById('txtPoly1<?php echo time(); ?>').value;
	
	if(polyname == "" || polyname == null){
		$("#alert_dialog").html("Please enter zone name");
		$("#alert_dialog").dialog("open");
		document.getElementById('txtPoly1<?php echo time(); ?>').focus();
		return false;
	}
	
	grpId = $("#cmbGroup_zone_<?php echo time(); ?>").val();
	if(grpId)
		grpId = grpId.join(",")
	else
		grpId = '';
	var sUrl = "<?php echo base_url(); ?>index.php/home/update_zone";
	
	$.post(sUrl,
		{polyid:polyid, in_alert:in_alert, out_alert:out_alert, sms_alert:sms_alert, email_alert:email_alert, group:grpId, name:polyname, color:$('#color<?php echo time(); ?>').val(), addressbook_ids:addressbook_ids, area_type_opt:area_type_opt},
		function(data){
			
			clearSelectionZone<?php echo time(); ?>();
			$("#dialog_zone<?php echo time(); ?>").dialog('close');
			$("#alert_dialog").html("Zone Saved Successfully");
			$("#alert_dialog").dialog('open');
			refreshZone();
	});		
	
	
}
function refreshZone(){
	clearMarkerZone();
	$.post("<?php echo base_url(); ?>index.php/home/refresh_zone",		
		function(data){
		clearZoneOverlays();
		
		for(k=0; k<data.plyId.length; k++){
			polyId = data.plyId[k];
			var bounds = new google.maps.LatLngBounds();
			var pathArrZone = [];
						
			for(j=0; j<data.plyLat[polyId].length; j++){
				pathArrZone.push(new google.maps.LatLng(data.plyLat[polyId][j], data.plyLng[polyId][j]));
			}
						
			for (i = 0; i < pathArrZone.length; i++) {
			  bounds.extend(pathArrZone[i]);
			}
			creatZoneAfterRefresh(polyId, bounds.getCenter(), pathArrZone, data.plyName[polyId][0], data.plyColor[polyId], k);
		}
		
	}, 'json');	
}
function creatZoneAfterRefresh(pid, center, pathArrZone, name, color, kk){
	var label = new ELabel({
	latlng: center, 
	label: "<div class='elable' id='elable_"+kk+"' style='z-index:99999;border:2px solid red;padding:10px;width:auto;background-color:#000;color:#fff;'>"+name+"</div>", 
	classname: "label", 
	offset: 0, 
	opacity: 100, 
	overlap: true,
	clicktarget: false
	});
	
	labelArrZone.push(label);
	
	var polyV = new google.maps.Polygon({
		  paths: pathArrZone,
		  strokeWeight: 2,
		  strokeOpacity : 0.6,
		  fillColor: color
		});
	
	polyV.setMap(map_geoZone);
	polyVarrZone.push(polyV);
	google.maps.event.addListener(polyV,"click",function(event){
		editZone(pid,polyV);
		
	});
	//comment by dharmik - > error occures, event is undefined
	/*google.maps.event.addListener(polyV,"click",function(event){
		google.maps.event.trigger(map_geoZone, 'click');
	});*/
	google.maps.event.addListener(polyV,"mouseover",function(event){
		label.setMap(map_geoZone);
		$("#elable_"+kk).parent().parent().css('z-index','99999');
	});
	
	google.maps.event.addListener(polyV,"mouseout",function(event){
		label.setMap(null);
	});		
	
}
function clearSelectionZone<?php echo time(); ?>(){
	
	if (polyMarkersZone) {
		for (i in polyMarkersZone) {
		  polyMarkersZone[i].setMap(null);
		  path.removeAt(i);
		  polyMarkersZone.splice(i, 1);
		}
	  }
	  if(polyMarkersZone.length > 0){
		clearSelectionZone<?php echo time(); ?>();
	  }
	  measureResetZone();
}
onLoadmap();
function searchCombZone()
{	
	var search = $.trim($("#search_c_1Zone<?php echo time(); ?>").val());
    var regex = new RegExp(search,"gi");
	for(i=0;i<options.length;i++)
	{
		var option = options[i];
		if(option.text.match(regex) !== null) {
			if ($('#<?php echo $dCombonm; ?> option:contains('+option.text+')').attr('selected')) {
				$('#<?php echo $dCombonm; ?> option:contains('+option.text+')').attr('selected', false);
				$('#<?php echo $dCombonm; ?> option:contains('+option.text+')').attr('selected', 'selected');
			} else {
				$('#<?php echo $dCombonm; ?> option:contains('+option.text+')').attr('selected', 'selected');
				$('#<?php echo $dCombonm; ?> option:contains('+option.text+')').attr('selected', false);
			}
			$("#search_c_1Zone<?php echo time(); ?>").removeClass("error_sel");
			$("#search_c_1Zone<?php echo time(); ?>").addClass("not_err");
			break;
		}
		else
		{
			$("#search_c_1Zone<?php echo time(); ?>").removeClass("not_err");
			$("#search_c_1Zone<?php echo time(); ?>").addClass("error_sel");
		}
	}
}
function clearZoneOverlays() {
 
  if (polyVarrZone) {
    for (i in polyVarrZone) {
      polyVarrZone[i].setMap(null);
    }
  }
  polyVarrZone = [];
}
function clearMarkerZone(){
if (marker_cobmo_arrZone){
		for (i in marker_cobmo_arrZone){
		  marker_cobmo_arrZone[i].setMap(null);
		}
	}
}
$(document).ready(function () {

	$("#dialog_zone<?php echo time(); ?>").dialog({
		autoOpen: false,
		draggable: true,
		resizable: true,
		modal: false,
		position:['right',0],
		title:'<?php echo $this->lang->line("Create Zone"); ?>',
		beforeClose: function(event, ui) { clearSelectionZone<?php echo time(); ?>(); newZone = false; }
	});
	$("#zone_confirm_dialog<?php echo time(); ?>").dialog({
		autoOpen: false,
      buttons : {
        "Confirm" : function() {
          confirmdeleteZone();
		  $(this).dialog("close");
        },
        "Cancel" : function() {
          $(this).dialog("close");
        }
      }
    });
	
	 $(function() {
    $("#address_geoZone").autocomplete({
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
        map_geoZone.setCenter(location);
		map_geoZone.setZoom(11);
      }
    });
  });
	<?php if($area_id!=""){ ?>
		editZone(<?php echo $area_id; ?>,glob_poly);
	<?php } ?>
  $("#loading_top").css("display","none");
  
});
function filterAddressbookZone(id){
	$.post("<?php echo base_url(); ?>index.php/home/filterAddressbook",	
		{id : id},
		function(data){
			$("#addressbookZone<?php echo time(); ?>").html(data.opt);		
	}, 'json');	
}
function measureCalcZone() {
    var area = google.maps.geometry.spherical.computeArea(poly.getPath());
    jQuery("#span-zone").text(area.toFixed(1));
}
function measureResetZone() {

    jQuery("#span-zone").text(0);

}

</script>
<div id="dialog_zone<?php echo time(); ?>" style="display:none">
</div>
<div id="zone_confirm_dialog<?php echo time(); ?>" style="display:none"><?php echo $this->lang->line("Do you want to delete this record"); ?> ?
</div>
<div id="search_zone" class="formtable" style="padding-bottom:5px; display:none;">
<label><?php echo $this->lang->line("Address"); ?>: </label><input id="address_geoZone" style="width:250px;" class="text ui-widget-content ui-corner-all" type="text"/>
</div>
<div id="map_geoZone" style="width: 100%; height: 98%; position:relative;"></div>
<div id="zone_size_div" style="display:none;"><p><?php echo $this->lang->line("Area_Size"); ?> : <span id="span-zone"></span> mt&sup2;</p></div>
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