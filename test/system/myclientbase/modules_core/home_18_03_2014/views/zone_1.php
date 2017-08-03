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
$dCombonm="cmbDevice_landmark_".time();
$dCombo = "<select class='select ui-widget-content ui-corner-all' id='cmbDevice_landmark_".time()."' style='height:100px;' name='cmbDevice' multiple='multiple'>";
$dCombo .= $deviceOpt;
$dCombo .= "</select>";
$dCombo_land = "<select id='land_device_".time()."' style='height:100px;' name='land_device' multiple='multiple'>";

$dCombo_land .= $deviceOpt;

$dCombo_land .= "</select>";


//path to directory to scan
$directory = "assets/landmark_images/";
 
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
	
	#poly_mainLandmark<?php echo time(); ?> td{
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

var sidebar_htmlmapLandmark  = '';
var marker_htmlmapLandmark  = [];
var marker_cobmo_arrLandmark = [];

var polylinesmapLandmark = [];
var polylineCoordsmapLandmark = [];
var map_geoLandmark = null;
var mapOptionsmapLandmark;

var polyVarrLandmark = [];
var labelArrLandmark = [];
var abounds;
function TrackControl_L(controlDiv, map_geoLandmark) {
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
  var areaText = document.createElement('DIV');
  areaText.style.fontFamily = 'Arial,sans-serif';
  areaText.style.fontSize = '12px';
  areaText.style.height = '20px';
  areaText.style.paddingTop = '3px';
  areaText.style.paddingLeft = '4px';
  areaText.style.paddingRight = '4px';
  areaText.innerHTML = 'Zone';
  control_UI.appendChild(areaText);
  
  google.maps.event.addDomListener(control_UI, 'click', function() {
	addLandmarkNew();
  });
  <?php } ?>
}
function TrackControl_s(controlDiv, map_geoLandmark) {
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
var path = new google.maps.MVCArray;
var poly;
var glob_poly;
var polyMarkersLandmark = [];
var polyLattLandmark = [];
var polyLngLandmark = [];
var newLandmark = false;
var deviceCombo = "<?php echo $dCombo; ?>";
var options = [];
var htmltextLandmark = '<table class="formtable" id="poly_mainLandmark<?php echo time(); ?>" style="display:none">';
	htmltextLandmark +=	'<input type="hidden" id="poly_id_Landmark<?php echo time(); ?>">';
	htmltextLandmark +=	'<tr>';
	htmltextLandmark +=	'<td><?php echo $this->lang->line("Zone_Name");  ?></td>';
	htmltextLandmark +=	'<td><input type="text" class="text ui-widget-content ui-corner-all" style="width:100%" id="txtPoly1<?php echo time(); ?>" name="txtPoly1" onmouseover="this.focus();"></td>';
	htmltextLandmark +=	'</tr>';
	htmltextLandmark +=	'<tr>';
	htmltextLandmark +=	'<td><?php echo $this->lang->line("Zone_Color"); ?></td>';
	htmltextLandmark +=	'<td><input type="text" name="color" id="color<?php echo time(); ?>" value="#ff0000" class="color-picker text ui-widget-content ui-corner-all" size="6" autocomplete="on" maxlength="10" /></td>';
	htmltextLandmark +=	'</tr>';
	htmltextLandmark +=	'<tr>';
	htmltextLandmark +=	'<td><?php echo $this->lang->line("Search"); ?></td>';
	htmltextLandmark +=	'<td><input type="text" class="text ui-widget-content ui-corner-all" style="width:100%" class="not_err" name="search_c" id="search_c_1Landmark<?php echo time(); ?>" onKeyUp="searchCombLandmark()" /></td>';
	htmltextLandmark +=	'</tr>';
	htmltextLandmark +=	'<tr style="line-height:10px">';
	htmltextLandmark +=	'<td>&nbsp;</td>';
	htmltextLandmark +=	'<td><span><a href="#" style="float:right;margin-right:15px;color:blue;font-size:10px" onclick="selectAllLandmark<?php echo time(); ?>(\'<?php echo "#cmbDevice_landmark_".time(); ?>\')"><?php echo $this->lang->line("Select/Unselect All"); ?></a></span>';
	htmltextLandmark += '</td>';
	htmltextLandmark +=	'</tr>';
	htmltextLandmark +=	'<tr>';
	htmltextLandmark +=	'<td style="vertical-align: middle !important;"><?php echo $this->lang->line("Device_Name"); ?></td>';
	htmltextLandmark +=	'<td>';
	deviceCombo = deviceCombo.replace(/ selected/g,'');
	htmltextLandmark += deviceCombo;
	
	htmltextLandmark += '</td>';
	htmltextLandmark +=	'</tr>';
	
	htmltextLandmark +=	'<tr><td colspan="2"><a href="#" style="text-decoration:underline;" onclick="hideShowAddressbookLandmark()">Addressbook</a></td></tr>';
/*	
	htmltextLandmark +=	'<tr class="addressbook_trLandmark" style="display:none;">';
	htmltextLandmark +=	'<td style="vertical-align: middle !important;">Group<?php //echo $this->lang->line("Device_Name"); ?></td>';
	htmltextLandmark +=	'<td><select id="addressbook_group<?php echo time(); ?>" class="select ui-widget-content ui-corner-all" name="addressbook_group" onchange="filterAddressbookLandmark(this.value)"><option value="">Select Group</option><?php echo $addressbookGroupOpt; ?></select></td>';
	htmltextLandmark +=	'</tr>';
	
	htmltextLandmark +=	'<tr class="addressbook_trLandmark" style="display:none;">';
	htmltextLandmark +=	'<td style="vertical-align: middle !important;">Addressbook<?php //echo $this->lang->line("Device_Name"); ?></td>';
	htmltextLandmark +=	'<td><select id="addressbookLandmark<?php echo time(); ?>" class="select ui-widget-content ui-corner-all" style="height:100px;" name="addressbook" multiple="multiple"><?php echo $addressbookOpt; ?></select></td>';
	htmltextLandmark +=	'</tr>';	
	
	htmltextLandmark +=	'<tr>';
	htmltextLandmark +=	'<td style="vertical-align: middle !important;">Zone Type</td>';
	htmltextLandmark +=	'<td><select id="in_area_optLandmark<?php echo time(); ?>" class="select ui-widget-content ui-corner-all" name="area_type_opt"><option value="Dealer">Dealer</option><option value="Tall Tax">Tall Tax</option><option value="Others">Others</option><option value="All">All</option></select></td>';
	htmltextLandmark +=	'</tr>';
*/	
	htmltextLandmark +=	'<tr>';
	htmltextLandmark +=	'<td colspan="2"><?php echo $this->lang->line("In Alert"); ?>  &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="in_alert" id="in_alertLandmark<?php echo time(); ?>" checked="true">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line("Out Alert"); ?>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="out_alert" id="out_alertLandmark<?php echo time(); ?>" checked="true"><br>';
	htmltextLandmark +=	'</td>';
	htmltextLandmark +=	'</tr>';
	htmltextLandmark +=	'<tr>';
	htmltextLandmark +=	'<td colspan="2"><?php echo $this->lang->line("Sms Alert"); ?> <input type="checkbox" name="area_sms_alertLandmark" id="area_sms_alertLandmark<?php echo time(); ?>" checked="true">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line("Email Alert"); ?> <input type="checkbox" name="area_email_alertLandmark" id="area_email_alertLandmark<?php echo time(); ?>" checked="true"><br>';
	htmltextLandmark +=	'</td>';
	htmltextLandmark +=	'</tr>';
	htmltextLandmark +=	'<tr>';
	htmltextLandmark +=	'<td colspan="2" align="center"><input type="button" id="btnCreateLandmark<?php echo time(); ?>" value="<?php echo $this->lang->line("Create"); ?>" onClick="createLandmark()" style="width:35%">&nbsp;&nbsp;<input type="button" id="btnupdateLandmark<?php echo time(); ?>" value="<?php echo $this->lang->line("Save"); ?>" onClick="updateLandmark<?php echo time(); ?>()" style="display:none;">&nbsp;<input type="button"  style="width:35%" id="btnCancelPoly<?php echo time(); ?>" value="<?php echo $this->lang->line("Clear"); ?>" style="width:35%" onClick="clearSelectionLandmark<?php echo time(); ?>()"><input type="button" id="btnDeleteArea<?php echo time(); ?>" value="<?php echo $this->lang->line("Delete Area"); ?>" style="display:none;">';
	htmltextLandmark +=	'</td>';
	htmltextLandmark +=	'</tr>';	
	htmltextLandmark +=	'<tr>';
	htmltextLandmark +=	'<td colspan="2" align="center"><input type="button" id="btnHelpArea<?php echo time(); ?>" value="<?php echo $this->lang->line("Help"); ?>" onClick="helpLandmark()" style="width:35%">&nbsp;&nbsp;&nbsp;<input type="button" id="btnbck_dArea<?php echo time(); ?>" value="<?php echo $this->lang->line("Back"); ?>" onClick="dialog_backbtnLandmark()" style="width:35%">';
	htmltextLandmark +=	'</td>';
	htmltextLandmark +=	'</tr>';
	htmltextLandmark +=	'</table>';
	
	htmltextLandmark += '<table id="poly_helpLandmark<?php echo time(); ?>" style="display:none">';
	htmltextLandmark +=	'<tr>';
	htmltextLandmark +=	'<td align="right"><input type="button" value="<?php echo $this->lang->line("Back"); ?>" onClick="mainLandmark()">';
	htmltextLandmark +=	'</td>';
	htmltextLandmark +=	'</tr>';
	htmltextLandmark +=	'<tr>';
	htmltextLandmark +=	'<td style="line-height:25px;">Step 1 : Insert Zone Name"); ?><br>Step 2 : Select devices you want to bind with this zone"); ?><br><?php echo $this->lang->line("Step_3_:_Click_on_Create"); ?><br>Step 4 : Click on Map And Creat Zone Minimun Three Point Require"<br><?php echo $this->lang->line("Step_5_:_Click_On_InseFrt_Button"); ?></td>';		
	htmltextLandmark +=	'</tr>';
	htmltextLandmark +=	'</table>';
	
	htmltextLandmark += '<table id="poly_other_optLandmark<?php echo time(); ?>">';
	htmltextLandmark +=	'<tr>';
	htmltextLandmark +=	'<td style="font-size:14px;"><input type="button" onclick="creatNewLandmark()" value="Create New" style="margin-top:3px"></td>';		
	htmltextLandmark +=	'</tr>';
	htmltextLandmark +=	'<tr>';
	htmltextLandmark +=	'<td style="font-size:14px;padding-left:50px"><h4 style="margin-bottom: 3px; margin-top: 5px;">OR</h4></td>';		
	htmltextLandmark +=	'</tr>';
	htmltextLandmark +=	'<tr>';
	htmltextLandmark +=	"<td style='font-size:14px;'><select id='live_assets_cmb_area<?php echo time(); ?>' style='margin-top: 5px;padding: 0.4em;width: 94%;' class='select ui-widget-content ui-corner-all'><?php echo $live_combo; ?></select></td>";		
	htmltextLandmark +=	'</tr>';
	htmltextLandmark +=	'<tr>';
	htmltextLandmark +=	'<td style="font-size:14px;"><input type="button" onclick="addPointLandmarkfrm_ComboLandmark()" value="Set Selected" style="margin-top:3px"></td>';		
	htmltextLandmark +=	'</tr>';
	htmltextLandmark +=	'</table>';
function hideShowAddressbookLandmark(){
	if($(".addressbook_trLandmark").css("display") == "none"){
		$(".addressbook_trLandmark").show();
	}else{
		$(".addressbook_trLandmark").hide();
	}
}
function selectAllLandmark<?php echo time(); ?>(id){
	if($(id+" option:selected").length == $(id+" option").length){
		$(id+" option").removeAttr('selected');
	}else{
		$(id+" option").attr('selected', 'selected');
	}
}
function selectAllLandmark_select<?php echo time(); ?>(id){
	$(id+" option").attr('selected', 'selected');
}
function creatNewLandmark(){
	$("#poly_mainLandmark<?php echo time(); ?>").show();
	$("#poly_other_optLandmark<?php echo time(); ?>").hide();
	$("#poly_helpLandmark<?php echo time(); ?>").hide();
	$("#btnCreateLandmark<?php echo time(); ?>").val('<?php echo $this->lang->line("Create"); ?>');
	newLandmark = false;
	clearMarkerLandmark();
	selectAllLandmark_select<?php echo time(); ?>("#cmbDevice_landmark_<?php echo time(); ?>");
}
function dialog_backbtnLandmark(){
	$("#poly_mainLandmark<?php echo time(); ?>").hide();
	$("#poly_other_optLandmark<?php echo time(); ?>").show();
	$("#poly_helpLandmark<?php echo time(); ?>").hide();
	$("#btnCreateLandmark<?php echo time(); ?>").val('<?php echo $this->lang->line("Create"); ?>');
	newLandmark = false;
	clearMarkerLandmark();
	clearSelectionLandmark<?php echo time(); ?>();
}
function addLandmarkNew()
	{
		$("#dialog_landmark<?php echo time(); ?>").html(htmltextLandmark);
		$("#dialog_landmark<?php echo time(); ?>").dialog("open");
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
function helpLandmark(){
	$("#poly_mainLandmark<?php echo time(); ?>").hide();
	$("#poly_helpLandmark<?php echo time(); ?>").show();
	$("#poly_other_optLandmark<?php echo time(); ?>").hide();
}
function mainLandmark(){
	$("#poly_mainLandmark<?php echo time(); ?>").show();
	$("#poly_helpLandmark<?php echo time(); ?>").hide();
	$("#poly_other_optLandmark<?php echo time(); ?>").hide();
}
var measure;
function onLoadmap() {
	var mapObjmap = document.getElementById("map_geoLandmark");
	if (mapObjmap != 'undefined' && mapObjmap != null) {

	mapOptionsmapLandmark = {
		zoom: 8,
		mapTypeId: google.maps.MapTypeId.HYBRID,
		mapTypeControl: true,
		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DEFAULT}
	};

	mapOptionsmapLandmark.center = new google.maps.LatLng(
		<?php echo $lati;?>,
		<?php echo $longi;?>
	);
	
	map_geoLandmark = new google.maps.Map(mapObjmap,mapOptionsmapLandmark);
	map_geoLandmark.enableKeyDragZoom();	
	
	
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
	var TrackControl = new TrackControl_L(TrackControlDiv, map_geoLandmark);
	TrackControlDiv.index = 1;
	map_geoLandmark.controls[google.maps.ControlPosition.TOP_RIGHT].push(TrackControlDiv);
	
	var TrackControlDiv = document.createElement('DIV');
	var TrackControl = new TrackControl_s(TrackControlDiv, map_geoLandmark);
	TrackControlDiv.index = 1;
	map_geoLandmark.controls[google.maps.ControlPosition.TOP_RIGHT].push(TrackControlDiv);
	
	abounds = new google.maps.LatLngBounds();	
	
	<?php
	$i = 0;
	foreach($plyId as $pIdv){
	?>
		var bounds = new google.maps.LatLngBounds();
	<?php
		$pathArrLandmark = array();
		
		for($j=0; $j<count($plyLat[$pIdv]); $j++){
			$pathArrLandmark[] = 'new google.maps.LatLng('.sprintf("%.6f", $plyLat[$pIdv][$j]).', '.sprintf("%.6f", $plyLng[$pIdv][$j]).')';
		}
		$pathString = implode(",", $pathArrLandmark);
		
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
		
		labelArrLandmark.push(label<?php echo $i; ?>);
		<?php if($show_zone_name == 1){ ?>
			label<?php echo $i; ?>.setMap(map_geoLandmark);
		<?php } ?>
		abounds.extend(bounds.getCenter());
		
		var polyV<?php echo $i; ?> = new google.maps.Polygon({
		      paths: [<?php echo $pathString; ?>],
		      strokeWeight: 2,
		      strokeOpacity : 0.6,
		      fillColor: '<?php echo $plyColor[$pIdv]; ?>'
		    });
		polyV<?php echo $i; ?>.setMap(map_geoLandmark);
		
		<?php if($area_id!="" && $area_id==$pIdv){ ?>
			glob_poly=polyV<?php echo $i; ?>;
		<?php } ?>
		
		polyVarrLandmark.push(polyV<?php echo $i; ?>);
		google.maps.event.addListener(polyV<?php echo $i; ?>,"click",function(event){
			editLandmark(<?php echo $pIdv; ?>,polyV<?php echo $i; ?>);
			//$(".elable").hide();
			//$("#dialog_landmark_det<?php echo time(); ?>").dialog('open');
			//$("#elable_<?php echo time(); ?>_<?php echo $i; ?>").show();
			 //this.setOptions({fillColor: "#00FF00"});
			 //label<?php echo $i; ?>.setMap(map_geoLandmark);
			 //$("#elable_<?php echo $i; ?>").parent().parent().css('z-index','99999');
		});
		google.maps.event.addListener(polyV<?php echo $i; ?>,"click",function(event){
			google.maps.event.trigger(map_geoLandmark, 'click');
		});
		<?php if($show_zone_name == 0){ ?>
		google.maps.event.addListener(polyV<?php echo $i; ?>,"mouseover",function(event){
			label<?php echo $i; ?>.setMap(map_geoLandmark);
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
	poly.setMap(map_geoLandmark);
	poly.setPaths(new google.maps.MVCArray([path]));
	
	google.maps.event.addListener(map_geoLandmark, 'click', addPointLandmark);
	newLandmark = false;
	<?php if(count($plyId) > 1){ ?>
	map_geoLandmark.fitBounds(abounds);
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
function editLandmark(id,edit_poly){
	//$("#loading_dialog").dialog('open');
	$("#loading_top").css("display","block");
	//alert(edit_poly.getBounds().toSource());
	zoom=16;
	map_geoLandmark.setCenter(edit_poly.getBounds().getCenter());
	map_geoLandmark.setZoom(zoom);
	$.post("<?php echo base_url(); ?>index.php/home/edit_zone", { id: id},
	 function(result) {
	 	$("#dialog_landmark<?php echo time(); ?>").html(htmltextLandmark);
		$("#poly_mainLandmark<?php echo time(); ?>").show();
		$("#poly_helpLandmark<?php echo time(); ?>").hide();
		$("#poly_other_optLandmark<?php echo time(); ?>").hide();
					
		$("#dialog_landmark<?php echo time(); ?>").dialog("open");
		
		options.length=0;
		$("#<?php echo $dCombonm; ?>").find('option').each(function() {
            options.push({value: $(this).val(), text: $(this).text()});
        });
			
		jQuery("input:button, input:submit, input:reset").button();
		
		$("#poly_id_Landmark<?php echo time(); ?>").val(result.data.polyid);
		$("#txtPoly1<?php echo time(); ?>").val(result.data.polyname);
		$("#color<?php echo time(); ?>").val(result.data.color);
		
		var devc = result.data.deviceid;
		devc = devc.split(",");
		$("#cmbDevice_landmark_<?php echo time(); ?>").val(devc);
		if(result.data.sms_alert == 0)
			$("#area_sms_alertLandmark<?php echo time(); ?>").attr("checked", false);
		if(result.data.email_alert == 0)
			$("#area_email_alertLandmark<?php echo time(); ?>").attr("checked", false);
		if(result.data.in_alert == 0)
			$("#in_alertLandmark<?php echo time(); ?>").attr("checked", false);
		if(result.data.out_alert == 0)
			$("#out_alertLandmark<?php echo time(); ?>").attr("checked", false);
		
		var addressbook_ids = result.data.addressbook_ids;
		$("#in_area_optLandmark<?php echo time(); ?> option[value='"+result.data.area_type_opt+"']").attr('selected','selected');
		if(addressbook_ids != "" && addressbook_ids != null){
			addressbook_ids = addressbook_ids.split(",");
			$("#addressbookLandmark<?php echo time(); ?>").val(addressbook_ids);
		}else{
			$("#addressbookLandmark<?php echo time(); ?>").val('');
		}
		
		$("#btnCancelPoly<?php echo time(); ?>").hide();
		$("#btnHelpArea<?php echo time(); ?>").hide();		
		$("#btnbck_dArea<?php echo time(); ?>").hide();		
		$("#btnDeleteArea<?php echo time(); ?>").show();	
		$("#btnDeleteArea<?php echo time(); ?>").click(function(){
			deleteLandmark(result.data.id);
		});
		$(".color-picker").miniColors({
					letterCase: 'uppercase',
		});
		$("#btnCreateLandmark<?php echo time(); ?>").hide();
		$("#btnUpdate<?php echo time(); ?>").show();
		$("#btnupdateLandmark<?php echo time(); ?>").show();
		
		//$("#loading_dialog").dialog('close');
		$("#loading_top").css("display","none");
		
	}, 'json');
}
function closeElable(lbl){
	labelArrLandmark[lbl].setMap(null);
}
function deleteLandmark(){
	$("#landmark_confirm_dialog<?php echo time(); ?>").dialog('open');
	
}
function confirmDeleteLandmark(){
	var polyid = $("#poly_id_Landmark<?php echo time(); ?>").val();
	$.post("<?php echo base_url(); ?>index.php/home/delete_zone", { id: polyid },
	 function(data) {
		$("#alert_dialog").html("Zone Deleted Successfully");
		$("#alert_dialog").dialog("open");
		$("#dialog_landmark<?php echo time(); ?>").dialog('close');
		refreshLandmark();
	});

}


function addPointLandmarkfrm_ComboLandmark(){
	
	clearMarkerLandmark();
	var latLng_1=$("#live_assets_cmb_area<?php echo time(); ?>").val().split(",");
	lt1=latLng_1[0];
	lng1=latLng_1[1];
	if(lt1!=0 && lng1!=0){		
		var marker = new google.maps.Marker({
	      position: new google.maps.LatLng(lt1, lng1),
	      map: map_geoLandmark,
	    });
		marker_cobmo_arrLandmark.push(marker);
		map_geoLandmark.setCenter(new google.maps.LatLng(lt1, lng1));
		marker.setMap(map_geoLandmark);
		map_geoLandmark.setZoom(12);
		$("#poly_mainLandmark<?php echo time(); ?>").show();
		$("#poly_helpLandmark<?php echo time(); ?>").hide();
		$("#poly_other_optLandmark<?php echo time(); ?>").hide();
		$("#btnCreateLandmark<?php echo time(); ?>").val("<?php echo $this->lang->line("Save"); ?>");
		newLandmark = true;
		selectAllLandmark_select<?php echo time(); ?>("#cmbDevice_landmark_<?php echo time(); ?>");
	}else{
		alert("Location not Found");
	}
	
}
function addPointLandmark(event) {

	if(newLandmark == true){
		$("#area_size_div").show();
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
	      map: map_geoLandmark,
	      draggable: true,		  
		  icon:image
	    });
		
	    polyMarkersLandmark.push(marker);
	    marker.setTitle("#" + path.length);
		
		//calculate area size		
		measureCalcLandmark();
		
	    google.maps.event.addListener(marker, 'click', function() {
	      marker.setMap(null);
	      for (var i = 0, I = polyMarkersLandmark.length; i < I && polyMarkersLandmark[i] != marker; ++i);
	      polyMarkersLandmark.splice(i, 1);
	      path.removeAt(i);
		  measureCalcLandmark();
	      }
		  
	    );

	    google.maps.event.addListener(marker, 'dragend', function() {
	      for (var i = 0, I = polyMarkersLandmark.length; i < I && polyMarkersLandmark[i] != marker; ++i);
			path.setAt(i, marker.getPosition());
			measureCalcLandmark();
	      }
	    );
	}
}

function createLandmark(event){
	btnCreateLandmark = document.getElementById('btnCreateLandmark<?php echo time(); ?>');
	if(btnCreateLandmark.value == "<?php echo $this->lang->line("Create"); ?>"){
		btnCreateLandmark.value="<?php echo $this->lang->line("Save"); ?>";
		newLandmark = true;
	}
	else{
		addLandmark<?php echo time(); ?>();
	}
}
function addLandmark<?php echo time(); ?>(){
	var in_alert = $("#in_alertLandmark<?php echo time(); ?>").is(':checked');
	var out_alert = $("#out_alertLandmark<?php echo time(); ?>").is(':checked');
	var sms_alert = $("#area_sms_alertLandmark<?php echo time(); ?>").is(':checked');
	var email_alert = $("#area_email_alertLandmark<?php echo time(); ?>").is(':checked');
	var addressbook_ids = $("#addressbookLandmark<?php echo time(); ?>").val();
	var area_type_opt = $("#in_area_optLandmark<?php echo time(); ?>").val();
	var polyname = document.getElementById('txtPoly1<?php echo time(); ?>').value;
	//var deviceId = document.getElementById('cmbDevice').value;
	for(i=0;i<polyMarkersLandmark.length;i++){
		polyLattLandmark.push(polyMarkersLandmark[i].getPosition().lat());
		polyLngLandmark.push(polyMarkersLandmark[i].getPosition().lng());
	}
	if(polyname == "" || polyname == null){
		alert("Please enter landmark name");
		document.getElementById('txtPoly1<?php echo time(); ?>').focus();
		return false;
	}
	
	if(polyMarkersLandmark.length < 3){
		alert("Please select zone on map");
		return false;
	}
	devId = $("#cmbDevice_landmark_<?php echo time(); ?>").val();
	if(devId)
		devId = devId.join(",")
	else
		devId = '';
	var sUrl = "<?php echo base_url(); ?>index.php/home/add_zone";
	
	var area_size = $("#span-landmark").text();
	$.post(sUrl,
		{in_alert:in_alert, out_alert:out_alert, sms_alert:sms_alert, email_alert:email_alert, device:devId, name:polyname, latAdd:polyLattLandmark, lngAdd:polyLngLandmark, addressbook_ids:addressbook_ids, area_size:area_size, color:$('#color<?php echo time(); ?>').val(), area_type_opt:area_type_opt},
		function(data){
			
			clearSelectionLandmark<?php echo time(); ?>();
			$("#dialog_landmark<?php echo time(); ?>").dialog('close');
			$("#alert_dialog").html("Zone Saved Successfully");
			$("#alert_dialog").dialog('open');
			refreshLandmark();
			newLandmark = true;
			polyLattLandmark = [];
			polyLngLandmark = [];
	});		
	
	
}
function updateLandmark<?php echo time(); ?>(){
	var polyid = $("#poly_id_Landmark<?php echo time(); ?>").val();
	var in_alert = $("#in_alertLandmark<?php echo time(); ?>").is(':checked');
	var out_alert = $("#out_alertLandmark<?php echo time(); ?>").is(':checked');
	var sms_alert = $("#area_sms_alertLandmark<?php echo time(); ?>").is(':checked');
	var email_alert = $("#area_email_alertLandmark<?php echo time(); ?>").is(':checked');
	var addressbook_ids = $("#addressbookLandmark<?php echo time(); ?>").val();
	var area_type_opt = $("#in_area_optLandmark<?php echo time(); ?>").val();
	var polyname = document.getElementById('txtPoly1<?php echo time(); ?>').value;
	
	if(polyname == "" || polyname == null){
		$("#alert_dialog").html("Please enter zone name");
		$("#alert_dialog").dialog("open");
		document.getElementById('txtPoly1<?php echo time(); ?>').focus();
		return false;
	}
	
	devId = $("#cmbDevice_landmark_<?php echo time(); ?>").val();
	if(devId)
		devId = devId.join(",")
	else
		devId = '';
	var sUrl = "<?php echo base_url(); ?>index.php/home/update_zone";
	
	$.post(sUrl,
		{polyid:polyid, in_alert:in_alert, out_alert:out_alert, sms_alert:sms_alert, email_alert:email_alert, device:devId, name:polyname, color:$('#color<?php echo time(); ?>').val(), addressbook_ids:addressbook_ids, area_type_opt:area_type_opt},
		function(data){
			
			clearSelectionLandmark<?php echo time(); ?>();
			$("#dialog_landmark<?php echo time(); ?>").dialog('close');
			$("#alert_dialog").html("Zone Saved Successfully");
			$("#alert_dialog").dialog('open');
			refreshLandmark();
	});		
	
	
}
function refreshLandmark(){
	clearMarkerLandmark();
	$.post("<?php echo base_url(); ?>index.php/home/refresh_zone",		
		function(data){
		clearLandmarkOverlays();
		
		for(k=0; k<data.plyId.length; k++){
			polyId = data.plyId[k];
			var bounds = new google.maps.LatLngBounds();
			var pathArrLandmark = [];
						
			for(j=0; j<data.plyLat[polyId].length; j++){
				pathArrLandmark.push(new google.maps.LatLng(data.plyLat[polyId][j], data.plyLng[polyId][j]));
			}
						
			for (i = 0; i < pathArrLandmark.length; i++) {
			  bounds.extend(pathArrLandmark[i]);
			}
			creatLandmarkAfterRefresh(polyId, bounds.getCenter(), pathArrLandmark, data.plyName[polyId][0], data.plyColor[polyId], k);
		}
		
	}, 'json');	
}
function creatLandmarkAfterRefresh(pid, center, pathArrLandmark, name, color, kk){
	var label = new ELabel({
	latlng: center, 
	label: "<div class='elable' id='elable_"+kk+"' style='z-index:99999;border:2px solid red;padding:10px;width:auto;background-color:#000;color:#fff;'>"+name+"</div>", 
	classname: "label", 
	offset: 0, 
	opacity: 100, 
	overlap: true,
	clicktarget: false
	});
	
	labelArrLandmark.push(label);
	
	var polyV = new google.maps.Polygon({
		  paths: pathArrLandmark,
		  strokeWeight: 2,
		  strokeOpacity : 0.6,
		  fillColor: color
		});
	
	polyV.setMap(map_geoLandmark);
	polyVarrLandmark.push(polyV);
	google.maps.event.addListener(polyV,"click",function(event){
		editLandmark(pid,polyV);
		
	});
	//comment by dharmik - > error occures, event is undefined
	/*google.maps.event.addListener(polyV,"click",function(event){
		google.maps.event.trigger(map_geoLandmark, 'click');
	});*/
	google.maps.event.addListener(polyV,"mouseover",function(event){
		label.setMap(map_geoLandmark);
		$("#elable_"+kk).parent().parent().css('z-index','99999');
	});
	
	google.maps.event.addListener(polyV,"mouseout",function(event){
		label.setMap(null);
	});		
	
}
function clearSelectionLandmark<?php echo time(); ?>(){
	
	if (polyMarkersLandmark) {
		for (i in polyMarkersLandmark) {
		  polyMarkersLandmark[i].setMap(null);
		  path.removeAt(i);
		  polyMarkersLandmark.splice(i, 1);
		}
	  }
	  if(polyMarkersLandmark.length > 0){
		clearSelectionLandmark<?php echo time(); ?>();
	  }
	  measureResetLandmark();
}
onLoadmap();
function searchCombLandmark()
{	
	var search = $.trim($("#search_c_1Landmark<?php echo time(); ?>").val());
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
			$("#search_c_1Landmark<?php echo time(); ?>").removeClass("error_sel");
			$("#search_c_1Landmark<?php echo time(); ?>").addClass("not_err");
			break;
		}
		else
		{
			$("#search_c_1Landmark<?php echo time(); ?>").removeClass("not_err");
			$("#search_c_1Landmark<?php echo time(); ?>").addClass("error_sel");
		}
	}
}
function clearLandmarkOverlays() {
 
  if (polyVarrLandmark) {
    for (i in polyVarrLandmark) {
      polyVarrLandmark[i].setMap(null);
    }
  }
  polyVarrLandmark = [];
}
function clearMarkerLandmark(){
if (marker_cobmo_arrLandmark){
		for (i in marker_cobmo_arrLandmark){
		  marker_cobmo_arrLandmark[i].setMap(null);
		}
	}
}
$(document).ready(function () {

	$("#dialog_landmark<?php echo time(); ?>").dialog({
		autoOpen: false,
		draggable: true,
		resizable: true,
		modal: false,
		position:['right',0],
		title:'<?php echo $this->lang->line("Create_Landmark"); ?>',
		beforeClose: function(event, ui) { clearSelectionLandmark<?php echo time(); ?>(); newLandmark = false; }
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
        }
      }
    });
	
	 $(function() {
    $("#address_geoLandmark").autocomplete({
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
        map_geoLandmark.setCenter(location);
		map_geoLandmark.setZoom(11);
      }
    });
  });
	<?php if($area_id!=""){ ?>
		editLandmark(<?php echo $area_id; ?>,glob_poly);
	<?php } ?>
  $("#loading_top").css("display","none");
  
});
function filterAddressbookLandmark(id){
	$.post("<?php echo base_url(); ?>index.php/home/filterAddressbook",	
		{id : id},
		function(data){
			$("#addressbookLandmark<?php echo time(); ?>").html(data.opt);		
	}, 'json');	
}
function measureCalcLandmark() {
    var area = google.maps.geometry.spherical.computeArea(poly.getPath());
    jQuery("#span-landmark").text(area.toFixed(1));
}
function measureResetLandmark() {

    jQuery("#span-landmark").text(0);

}

</script>
<div id="dialog_landmark<?php echo time(); ?>" style="display:none">
</div>
<div id="landmark_confirm_dialog<?php echo time(); ?>" style="display:none"><?php echo $this->lang->line("Do you want to delete this record"); ?> ?
</div>
<div id="search_landmark" class="formtable" style="padding-bottom:5px; display:none;">
<label><?php echo $this->lang->line("Address"); ?>: </label><input id="address_geoLandmark" style="width:250px;" class="text ui-widget-content ui-corner-all" type="text"/>
</div>
<div id="map_geoLandmark" style="width: 100%; height: 98%; position:relative;"></div>
<div id="area_size_div" style="display:none;"><p><?php echo $this->lang->line("Area_Size"); ?> : <span id="span-landmark"></span> mt&sup2;</p></div>
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