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
$dCombonm="cmbDevice_".time();
$dCombo = "<select class='select ui-widget-content ui-corner-all' id='cmbDevice_".time()."' style='height:100px;' name='cmbDevice' multiple='multiple'>";
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
	
	#poly_main<?php echo time(); ?> td{
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

var sidebar_htmlmap  = '';
var marker_htmlmap  = [];
var marker_cobmo_arr = [];

var polylinesmap = [];
var polylineCoordsmap = [];
var map_geo = null;
var mapOptionsmap;

var polyVarr = [];
var labelArr = [];
var abounds;
function TrackControl(controlDiv, map_geo) {
<?php
	if(in_array('Create Zone',$data)){
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
  control_UI.title = '<?php echo $this->lang->line("Click to Create Zone"); ?>';
  controlDiv.appendChild(control_UI);
  // Set CSS for the control interior
  var areaText = document.createElement('DIV');
  areaText.style.fontFamily = 'Arial,sans-serif';
  areaText.style.fontSize = '12px';
  areaText.style.height = '20px';
  areaText.style.paddingTop = '3px';
  areaText.style.paddingLeft = '4px';
  areaText.style.paddingRight = '4px';
  areaText.innerHTML = '<?php echo $this->lang->line("Zone"); ?>';
  control_UI.appendChild(areaText);
  
  google.maps.event.addDomListener(control_UI, 'click', function() {
	addPolyNew();
  });
  <?php } ?>
}
function TrackControl_s(controlDiv, map_geo) {
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
	if($("#search_geo").css('display') == 'block'){
		$("#search_geo").css('display', 'none');
	}else{
		$("#search_geo").css('display', 'block');
	}	
  });
   <?php } ?>
}
var path = new google.maps.MVCArray;
var poly;
var glob_poly;
var polyMarkers = [];
var polyLat = [];
var polyLng = [];
var newPoly = false;
var deviceCombo = "<?php echo $dCombo; ?>";
var options = [];
var htmltext = '<table class="formtable" id="poly_main<?php echo time(); ?>" style="display:none">';
	htmltext +=	'<input type="hidden" id="poly_id_<?php echo time(); ?>">';
	htmltext +=	'<tr>';
	htmltext +=	'<td><?php echo $this->lang->line("Area_Name");  ?></td>';
	htmltext +=	'<td><input type="text" class="text ui-widget-content ui-corner-all" style="width:100%" id="txtPoly1<?php echo time(); ?>" name="txtPoly1" onmouseover="this.focus();"></td>';
	htmltext +=	'</tr>';
	htmltext +=	'<tr>';
	htmltext +=	'<td><?php echo $this->lang->line("Area_Color"); ?></td>';
	htmltext +=	'<td><input type="text" name="color" id="color<?php echo time(); ?>" value="#ff0000" class="color-picker text ui-widget-content ui-corner-all" size="6" autocomplete="on" maxlength="10" /></td>';
	htmltext +=	'</tr>';
	htmltext +=	'<tr>';
	htmltext +=	'<td><?php echo $this->lang->line("Search"); ?></td>';
	htmltext +=	'<td><input type="text" class="text ui-widget-content ui-corner-all" style="width:100%" class="not_err" name="search_c" id="search_c_1<?php echo time(); ?>" onKeyUp="searchComb()" /></td>';
	htmltext +=	'</tr>';
	htmltext +=	'<tr style="line-height:10px">';
	htmltext +=	'<td>&nbsp;</td>';
	htmltext +=	'<td><span><a href="#" style="float:right;margin-right:15px;color:blue;font-size:10px" onclick="selectAllarea<?php echo time(); ?>(\'<?php echo "#cmbDevice_".time(); ?>\')"><?php echo $this->lang->line("Select/Unselect All"); ?></a></span>';
	htmltext += '</td>';
	htmltext +=	'</tr>';
	htmltext +=	'<tr>';
	htmltext +=	'<td style="vertical-align: middle !important;"><?php echo $this->lang->line("Device_Name"); ?></td>';
	htmltext +=	'<td>';
	deviceCombo = deviceCombo.replace(/ selected/g,'');
	htmltext += deviceCombo;
	
	htmltext += '</td>';
	htmltext +=	'</tr>';
	
	htmltext +=	'<tr><td colspan="2"><a href="#" style="text-decoration:underline;" onclick="hideShowAddressbook()">Addressbook</a></td></tr>';
	
	htmltext +=	'<tr class="addressbook_tr" style="display:none;">';
	htmltext +=	'<td style="vertical-align: middle !important;">Group<?php //echo $this->lang->line("Device_Name"); ?></td>';
	htmltext +=	'<td><select id="addressbook_group<?php echo time(); ?>" class="select ui-widget-content ui-corner-all" name="addressbook_group" onchange="filterAddressbook(this.value)"><option value="">Select Group</option><?php echo $addressbookGroupOpt; ?></select></td>';
	htmltext +=	'</tr>';
	
	htmltext +=	'<tr class="addressbook_tr" style="display:none;">';
	htmltext +=	'<td style="vertical-align: middle !important;">Addressbook<?php //echo $this->lang->line("Device_Name"); ?></td>';
	htmltext +=	'<td><select id="addressbook<?php echo time(); ?>" class="select ui-widget-content ui-corner-all" style="height:100px;" name="addressbook" multiple="multiple"><?php echo $addressbookOpt; ?></select></td>';
	htmltext +=	'</tr>';	
	
	htmltext +=	'<tr>';
	htmltext +=	'<td style="vertical-align: middle !important;"><?php echo $this->lang->line("Area Type"); ?></td>';
	htmltext +=	'<td><select id="in_area_opt<?php echo time(); ?>" class="select ui-widget-content ui-corner-all" name="area_type_opt"><option value="Dealer">Dealer</option><option value="Tall Tax">Tall Tax</option><option value="Others">Others</option><option value="All">All</option></select></td>';
	htmltext +=	'</tr>';
	
	htmltext +=	'<tr>';
	htmltext +=	'<td colspan="2"><?php echo $this->lang->line("In Alert"); ?>  &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="in_alert" id="in_alert<?php echo time(); ?>" checked="true">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line("Out Alert"); ?>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="out_alert" id="out_alert<?php echo time(); ?>" checked="true"><br>';
	htmltext +=	'</td>';
	htmltext +=	'</tr>';
	htmltext +=	'<tr>';
	htmltext +=	'<td colspan="2"><?php echo $this->lang->line("Sms Alert"); ?> <input type="checkbox" name="area_sms_alert" id="area_sms_alert<?php echo time(); ?>" checked="true">&nbsp;&nbsp;&nbsp;<?php echo $this->lang->line("Email Alert"); ?> <input type="checkbox" name="area_email_alert" id="area_email_alert<?php echo time(); ?>" checked="true"><br>';
	htmltext +=	'</td>';
	htmltext +=	'</tr>';
	htmltext +=	'<tr>';
	htmltext +=	'<td colspan="2" align="center"><input type="button" id="btnCreate<?php echo time(); ?>" value="<?php echo $this->lang->line("Create"); ?>" onClick="createPoly()" style="width:35%">&nbsp;&nbsp;<input type="button" id="btnUpdatePoly<?php echo time(); ?>" value="<?php echo $this->lang->line("Save"); ?>" onClick="updatePoly<?php echo time(); ?>()" style="display:none;">&nbsp;<input type="button"  style="width:35%" id="btnCancelPoly<?php echo time(); ?>" value="<?php echo $this->lang->line("Clear"); ?>" style="width:35%" onClick="clearSelection<?php echo time(); ?>()"><input type="button" id="btnDeleteArea<?php echo time(); ?>" value="<?php echo $this->lang->line("Delete Area"); ?>" style="display:none;">';
	htmltext +=	'</td>';
	htmltext +=	'</tr>';	
	htmltext +=	'<tr>';
	htmltext +=	'<td colspan="2" align="center"><input type="button" id="btnHelpArea<?php echo time(); ?>" value="<?php echo $this->lang->line("Help"); ?>" onClick="helpPoly()" style="width:35%">&nbsp;&nbsp;&nbsp;<input type="button" id="btnbck_dArea<?php echo time(); ?>" value="<?php echo $this->lang->line("Back"); ?>" onClick="dialog_backbtn()" style="width:35%">';
	htmltext +=	'</td>';
	htmltext +=	'</tr>';
	htmltext +=	'</table>';
	
	htmltext += '<table id="poly_help<?php echo time(); ?>" style="display:none">';
	htmltext +=	'<tr>';
	htmltext +=	'<td align="right"><input type="button" value="<?php echo $this->lang->line("Back"); ?>" onClick="mainPoly()">';
	htmltext +=	'</td>';
	htmltext +=	'</tr>';
	htmltext +=	'<tr>';
	htmltext +=	'<td style="line-height:25px;"><?php echo $this->lang->line("Step_1_:_Insert_Area_Name"); ?><br><?php echo $this->lang->line("Step_2_:_Select_devices_you_want_to_bind_with_this_area"); ?><br><?php echo $this->lang->line("Step_3_:_Click_on_Create"); ?><br><?php echo $this->lang->line("Step_4_:_Click_on_Map_And_Creat_Area_Minimun_Three_Point_Require"); ?><br><?php echo $this->lang->line("Step_5_:_Click_On_InseFrt_Button"); ?></td>';		
	htmltext +=	'</tr>';
	htmltext +=	'</table>';
	
	htmltext += '<table id="poly_other_opt<?php echo time(); ?>">';
	htmltext +=	'<tr>';
	htmltext +=	'<td style="font-size:14px;"><input type="button" onclick="creatNew()" value="Create New" style="margin-top:3px"></td>';		
	htmltext +=	'</tr>';
	htmltext +=	'<tr>';
	htmltext +=	'<td style="font-size:14px;padding-left:50px"><h4 style="margin-bottom: 3px; margin-top: 5px;">OR</h4></td>';		
	htmltext +=	'</tr>';
	htmltext +=	'<tr>';
	htmltext +=	"<td style='font-size:14px;'><select id='live_assets_cmb_area<?php echo time(); ?>' style='margin-top: 5px;padding: 0.4em;width: 94%;' class='select ui-widget-content ui-corner-all'><?php echo $live_combo; ?></select></td>";		
	htmltext +=	'</tr>';
	htmltext +=	'<tr>';
	htmltext +=	'<td style="font-size:14px;"><input type="button" onclick="addPointfrm_Combo()" value="Set Selected" style="margin-top:3px"></td>';		
	htmltext +=	'</tr>';
	htmltext +=	'</table>';
function hideShowAddressbook(){
	if($(".addressbook_tr").css("display") == "none"){
		$(".addressbook_tr").show();
	}else{
		$(".addressbook_tr").hide();
	}
}
function selectAllarea<?php echo time(); ?>(id){
	if($(id+" option:selected").length == $(id+" option").length){
		$(id+" option").removeAttr('selected');
	}else{
		$(id+" option").attr('selected', 'selected');
	}
}
function selectAllarea_select<?php echo time(); ?>(id){
	$(id+" option").attr('selected', 'selected');
}
function creatNew(){
	$("#poly_main<?php echo time(); ?>").show();
	$("#poly_other_opt<?php echo time(); ?>").hide();
	$("#poly_help<?php echo time(); ?>").hide();
	$("#btnCreate<?php echo time(); ?>").val('<?php echo $this->lang->line("Create"); ?>');
	newPoly = false;
	clearMarker();
	selectAllarea_select<?php echo time(); ?>("#cmbDevice_<?php echo time(); ?>");
}
function dialog_backbtn(){
	$("#poly_main<?php echo time(); ?>").hide();
	$("#poly_other_opt<?php echo time(); ?>").show();
	$("#poly_help<?php echo time(); ?>").hide();
	$("#btnCreate<?php echo time(); ?>").val('<?php echo $this->lang->line("Create"); ?>');
	newPoly = false;
	clearMarker();
	clearSelection<?php echo time(); ?>();
}
function addPolyNew()
	{
		$("#dialog_poly<?php echo time(); ?>").html(htmltext);
		$("#dialog_poly<?php echo time(); ?>").dialog("open");
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
function helpPoly(){
	$("#poly_main<?php echo time(); ?>").hide();
	$("#poly_help<?php echo time(); ?>").show();
	$("#poly_other_opt<?php echo time(); ?>").hide();
}
function mainPoly(){
	$("#poly_main<?php echo time(); ?>").show();
	$("#poly_help<?php echo time(); ?>").hide();
	$("#poly_other_opt<?php echo time(); ?>").hide();
}
var measure;
function onLoadmap() {
	var mapObjmap = document.getElementById("map_geo");
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
	
	map_geo = new google.maps.Map(mapObjmap,mapOptionsmap);
	map_geo.enableKeyDragZoom();	
	
	
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
		
	var trackControlDiv = document.createElement('DIV');
	var trackControl = new TrackControl(trackControlDiv, map_geo);
	trackControlDiv.index = 1;
	map_geo.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackControlDiv);
	
	var trackControlDiv = document.createElement('DIV');
	var trackControl = new TrackControl_s(trackControlDiv, map_geo);
	trackControlDiv.index = 1;
	map_geo.controls[google.maps.ControlPosition.TOP_RIGHT].push(trackControlDiv);
	
	abounds = new google.maps.LatLngBounds();	
	<?php
	$i = 0;
	foreach($plyId as $pIdv){
	?>
		var bounds = new google.maps.LatLngBounds();
	<?php
		$pathArr = array();
		
		for($j=0; $j<count($plyLat[$pIdv]); $j++){
			$pathArr[] = 'new google.maps.LatLng('.sprintf("%.6f", $plyLat[$pIdv][$j]).', '.sprintf("%.6f", $plyLng[$pIdv][$j]).')';
		}
		$pathString = implode(",", $pathArr);
		
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
		
		labelArr.push(label<?php echo $i; ?>);
		<?php if($show_zone_name == 1){ ?>
			label<?php echo $i; ?>.setMap(map_geo);
		<?php } ?>
		abounds.extend(bounds.getCenter());
		
		var polyV<?php echo $i; ?> = new google.maps.Polygon({
		      paths: [<?php echo $pathString; ?>],
		      strokeWeight: 2,
		      strokeOpacity : 0.6,
		      fillColor: '<?php echo $plyColor[$pIdv]; ?>'
		    });
		polyV<?php echo $i; ?>.setMap(map_geo);
		
		<?php if($area_id!="" && $area_id==$pIdv){ ?>
			glob_poly=polyV<?php echo $i; ?>;
		<?php } ?>
		
		polyVarr.push(polyV<?php echo $i; ?>);
		google.maps.event.addListener(polyV<?php echo $i; ?>,"click",function(event){
			editArea(<?php echo $pIdv; ?>,polyV<?php echo $i; ?>);
			//$(".elable").hide();
			//$("#dialog_poly_det<?php echo time(); ?>").dialog('open');
			//$("#elable_<?php echo time(); ?>_<?php echo $i; ?>").show();
			 //this.setOptions({fillColor: "#00FF00"});
			 //label<?php echo $i; ?>.setMap(map_geo);
			 //$("#elable_<?php echo $i; ?>").parent().parent().css('z-index','99999');
		});
		google.maps.event.addListener(polyV<?php echo $i; ?>,"click",function(event){
			google.maps.event.trigger(map_geo, 'click');
		});
		<?php if($show_zone_name == 0){ ?>
		google.maps.event.addListener(polyV<?php echo $i; ?>,"mouseover",function(event){
			label<?php echo $i; ?>.setMap(map_geo);
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
	poly.setMap(map_geo);
	poly.setPaths(new google.maps.MVCArray([path]));
	
	google.maps.event.addListener(map_geo, 'click', addPoint);
	newPoly = false;
	<?php if(count($plyId) > 1){ ?>
	map_geo.fitBounds(abounds);
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
function editArea(id,edit_poly){
	//$("#loading_dialog").dialog('open');
	$("#loading_top").css("display","block");
	//alert(edit_poly.getBounds().toSource());
	zoom=16;
	map_geo.setCenter(edit_poly.getBounds().getCenter());
	map_geo.setZoom(zoom);
	$.post("<?php echo base_url(); ?>index.php/home/edit_zone", { id: id},
	 function(result) {
	 	$("#dialog_poly<?php echo time(); ?>").html(htmltext);
		$("#poly_main<?php echo time(); ?>").show();
		$("#poly_help<?php echo time(); ?>").hide();
		$("#poly_other_opt<?php echo time(); ?>").hide();
					
		$("#dialog_poly<?php echo time(); ?>").dialog("open");
		
		options.length=0;
		$("#<?php echo $dCombonm; ?>").find('option').each(function() {
            options.push({value: $(this).val(), text: $(this).text()});
        });
			
		jQuery("input:button, input:submit, input:reset").button();
		
		$("#poly_id_<?php echo time(); ?>").val(result.data.polyid);
		$("#txtPoly1<?php echo time(); ?>").val(result.data.polyname);
		$("#color<?php echo time(); ?>").val(result.data.color);
		
		var devc = result.data.deviceid;
		devc = devc.split(",");
		$("#cmbDevice_<?php echo time(); ?>").val(devc);
		if(result.data.sms_alert == 0)
			$("#area_sms_alert<?php echo time(); ?>").attr("checked", false);
		if(result.data.email_alert == 0)
			$("#area_email_alert<?php echo time(); ?>").attr("checked", false);
		if(result.data.in_alert == 0)
			$("#in_alert<?php echo time(); ?>").attr("checked", false);
		if(result.data.out_alert == 0)
			$("#out_alert<?php echo time(); ?>").attr("checked", false);
		
		var addressbook_ids = result.data.addressbook_ids;
		$("#in_area_opt<?php echo time(); ?> option[value='"+result.data.area_type_opt+"']").attr('selected','selected');
		if(addressbook_ids != "" && addressbook_ids != null){
			addressbook_ids = addressbook_ids.split(",");
			$("#addressbook<?php echo time(); ?>").val(addressbook_ids);
		}else{
			$("#addressbook<?php echo time(); ?>").val('');
		}
		
		$("#btnCancelPoly<?php echo time(); ?>").hide();
		$("#btnHelpArea<?php echo time(); ?>").hide();		
		$("#btnbck_dArea<?php echo time(); ?>").hide();		
		$("#btnDeleteArea<?php echo time(); ?>").show();	
		$("#btnDeleteArea<?php echo time(); ?>").click(function(){
			deletePoly(result.data.id);
		});
		$(".color-picker").miniColors({
					letterCase: 'uppercase',
		});
		$("#btnCreate<?php echo time(); ?>").hide();
		$("#btnUpdate<?php echo time(); ?>").show();
		$("#btnUpdatePoly<?php echo time(); ?>").show();
		
		//$("#loading_dialog").dialog('close');
		$("#loading_top").css("display","none");
		
	}, 'json');
}
function closeElable(lbl){
	labelArr[lbl].setMap(null);
}
function deletePoly(){
	$("#area_confirm_dialog<?php echo time(); ?>").dialog('open');
	
}
function confirmDeleteArea(){
	var polyid = $("#poly_id_<?php echo time(); ?>").val();
	$.post("<?php echo base_url(); ?>index.php/live/deletepolyzone", { id: polyid },
	 function(data) {
		$("#alert_dialog").html("<?php echo $this->lang->line("Zone Deleted Successfully"); ?>");
		$("#alert_dialog").dialog("open");
		$("#dialog_poly<?php echo time(); ?>").dialog('close');
		refreshArea();
	});

}


function addPointfrm_Combo(){
	
	clearMarker();
	var latLng_1=$("#live_assets_cmb_area<?php echo time(); ?>").val().split(",");
	lt1=latLng_1[0];
	lng1=latLng_1[1];
	if(lt1!=0 && lng1!=0){		
		var marker = new google.maps.Marker({
	      position: new google.maps.LatLng(lt1, lng1),
	      map: map_geo,
	    });
		marker_cobmo_arr.push(marker);
		map_geo.setCenter(new google.maps.LatLng(lt1, lng1));
		marker.setMap(map_geo);
		map_geo.setZoom(12);
		$("#poly_main<?php echo time(); ?>").show();
		$("#poly_help<?php echo time(); ?>").hide();
		$("#poly_other_opt<?php echo time(); ?>").hide();
		$("#btnCreate<?php echo time(); ?>").val("<?php echo $this->lang->line("Save"); ?>");
		newPoly = true;
		selectAllarea_select<?php echo time(); ?>("#cmbDevice_<?php echo time(); ?>");
	}else{
		alert("Location not Found");
	}
	
}
function addPoint(event) {

	if(newPoly == true){
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
	      map: map_geo,
	      draggable: true,		  
		  icon:image
	    });
		
	    polyMarkers.push(marker);
	    marker.setTitle("#" + path.length);
		
		//calculate area size		
		measureCalc();
		
	    google.maps.event.addListener(marker, 'click', function() {
	      marker.setMap(null);
	      for (var i = 0, I = polyMarkers.length; i < I && polyMarkers[i] != marker; ++i);
	      polyMarkers.splice(i, 1);
	      path.removeAt(i);
		  measureCalc();
	      }
		  
	    );

	    google.maps.event.addListener(marker, 'dragend', function() {
	      for (var i = 0, I = polyMarkers.length; i < I && polyMarkers[i] != marker; ++i);
			path.setAt(i, marker.getPosition());
			measureCalc();
	      }
	    );
	}
}

function createPoly(event){
	btnCreate = document.getElementById('btnCreate<?php echo time(); ?>');
	if(btnCreate.value == "<?php echo $this->lang->line("Create"); ?>"){
		btnCreate.value="<?php echo $this->lang->line("Save"); ?>";
		newPoly = true;
	}
	else{
		addPoly<?php echo time(); ?>();
	}
}
function addPoly<?php echo time(); ?>(){
	var in_alert = $("#in_alert<?php echo time(); ?>").is(':checked');
	var out_alert = $("#out_alert<?php echo time(); ?>").is(':checked');
	var sms_alert = $("#area_sms_alert<?php echo time(); ?>").is(':checked');
	var email_alert = $("#area_email_alert<?php echo time(); ?>").is(':checked');
	var addressbook_ids = $("#addressbook<?php echo time(); ?>").val();
	var area_type_opt = $("#in_area_opt<?php echo time(); ?>").val();
	var polyname = document.getElementById('txtPoly1<?php echo time(); ?>').value;
	//var deviceId = document.getElementById('cmbDevice').value;
	for(i=0;i<polyMarkers.length;i++){
		polyLat.push(polyMarkers[i].getPosition().lat());
		polyLng.push(polyMarkers[i].getPosition().lng());
	}
	if(polyname == "" || polyname == null){
		alert("<?php echo $this->lang->line("Please enter zone name"); ?>");
		document.getElementById('txtPoly1<?php echo time(); ?>').focus();
		return false;
	}
	
	if(polyMarkers.length < 3){
		alert("<?php echo $this->lang->line("Please select zone on map"); ?>");
		return false;
	}
	devId = $("#cmbDevice_<?php echo time(); ?>").val();
	if(devId)
		devId = devId.join(",")
	else
		devId = '';
	var sUrl = "<?php echo base_url(); ?>index.php/live/addPoly";
	
	var area_size = $("#span-area").text();
	$.post(sUrl,
		{in_alert:in_alert, out_alert:out_alert, sms_alert:sms_alert, email_alert:email_alert, device:devId, name:polyname, latAdd:polyLat, lngAdd:polyLng, addressbook_ids:addressbook_ids, area_size:area_size, color:$('#color<?php echo time(); ?>').val(), area_type_opt:area_type_opt},
		function(data){
			
			clearSelection<?php echo time(); ?>();
			$("#dialog_poly<?php echo time(); ?>").dialog('close');
			$("#alert_dialog").html("<?php echo $this->lang->line("Zone Saved Successfully"); ?>");
			$("#alert_dialog").dialog('open');
			refreshArea();
			newPoly = true;
			polyLat = [];
			polyLng = [];
	});		
	
	
}
function updatePoly<?php echo time(); ?>(){
	var polyid = $("#poly_id_<?php echo time(); ?>").val();
	var in_alert = $("#in_alert<?php echo time(); ?>").is(':checked');
	var out_alert = $("#out_alert<?php echo time(); ?>").is(':checked');
	var sms_alert = $("#area_sms_alert<?php echo time(); ?>").is(':checked');
	var email_alert = $("#area_email_alert<?php echo time(); ?>").is(':checked');
	var addressbook_ids = $("#addressbook<?php echo time(); ?>").val();
	var area_type_opt = $("#in_area_opt<?php echo time(); ?>").val();
	var polyname = document.getElementById('txtPoly1<?php echo time(); ?>').value;
	
	if(polyname == "" || polyname == null){
		$("#alert_dialog").html("<?php echo $this->lang->line("Please enter area name"); ?>");
		$("#alert_dialog").dialog("open");
		document.getElementById('txtPoly1<?php echo time(); ?>').focus();
		return false;
	}
	
	devId = $("#cmbDevice_<?php echo time(); ?>").val();
	if(devId)
		devId = devId.join(",")
	else
		devId = '';
	var sUrl = "<?php echo base_url(); ?>index.php/home/updateZone";
	
	$.post(sUrl,
		{polyid:polyid, in_alert:in_alert, out_alert:out_alert, sms_alert:sms_alert, email_alert:email_alert, device:devId, name:polyname, color:$('#color<?php echo time(); ?>').val(), addressbook_ids:addressbook_ids, area_type_opt:area_type_opt},
		function(data){
			
			clearSelection<?php echo time(); ?>();
			$("#dialog_poly<?php echo time(); ?>").dialog('close');
			$("#alert_dialog").html("<?php echo $this->lang->line("Area Saved Successfully"); ?>");
			$("#alert_dialog").dialog('open');
			refreshArea();
	});		
	
	
}
function refreshArea(){
	clearMarker();
	$.post("<?php echo base_url(); ?>index.php/home/refreshArea",		
		function(data){
		clearPolyOverlays();
		
		for(k=0; k<data.plyId.length; k++){
			polyId = data.plyId[k];
			var bounds = new google.maps.LatLngBounds();
			var pathArr = [];
						
			for(j=0; j<data.plyLat[polyId].length; j++){
				pathArr.push(new google.maps.LatLng(data.plyLat[polyId][j], data.plyLng[polyId][j]));
			}
						
			for (i = 0; i < pathArr.length; i++) {
			  bounds.extend(pathArr[i]);
			}
			creatAreaAfterRefresh(polyId, bounds.getCenter(), pathArr, data.plyName[polyId][0], data.plyColor[polyId], k);
		}
		
	}, 'json');	
}
function creatAreaAfterRefresh(pid, center, pathArr, name, color, kk){
	var label = new ELabel({
	latlng: center, 
	label: "<div class='elable' id='elable_"+kk+"' style='z-index:99999;border:2px solid red;padding:10px;width:auto;background-color:#000;color:#fff;'>"+name+"</div>", 
	classname: "label", 
	offset: 0, 
	opacity: 100, 
	overlap: true,
	clicktarget: false
	});
	
	labelArr.push(label);
	
	var polyV = new google.maps.Polygon({
		  paths: pathArr,
		  strokeWeight: 2,
		  strokeOpacity : 0.6,
		  fillColor: color
		});
	
	polyV.setMap(map_geo);
	polyVarr.push(polyV);
	google.maps.event.addListener(polyV,"click",function(event){
		editArea(pid,polyV);
		
	});
	//comment by dharmik - > error occures, event is undefined
	/*google.maps.event.addListener(polyV,"click",function(event){
		google.maps.event.trigger(map_geo, 'click');
	});*/
	google.maps.event.addListener(polyV,"mouseover",function(event){
		label.setMap(map_geo);
		$("#elable_"+kk).parent().parent().css('z-index','99999');
	});
	
	google.maps.event.addListener(polyV,"mouseout",function(event){
		label.setMap(null);
	});		
	
}
function clearSelection<?php echo time(); ?>(){
	
	if (polyMarkers) {
		for (i in polyMarkers) {
		  polyMarkers[i].setMap(null);
		  path.removeAt(i);
		  polyMarkers.splice(i, 1);
		}
	  }
	  if(polyMarkers.length > 0){
		clearSelection<?php echo time(); ?>();
	  }
	  measureReset();
}
onLoadmap();
function searchComb()
{	
	var search = $.trim($("#search_c_1<?php echo time(); ?>").val());
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
			$("#search_c_1<?php echo time(); ?>").removeClass("error_sel");
			$("#search_c_1<?php echo time(); ?>").addClass("not_err");
			break;
		}
		else
		{
			$("#search_c_1<?php echo time(); ?>").removeClass("not_err");
			$("#search_c_1<?php echo time(); ?>").addClass("error_sel");
		}
	}
}
function clearPolyOverlays() {
 
  if (polyVarr) {
    for (i in polyVarr) {
      polyVarr[i].setMap(null);
    }
  }
  polyVarr = [];
}
function clearMarker(){
if (marker_cobmo_arr){
		for (i in marker_cobmo_arr){
		  marker_cobmo_arr[i].setMap(null);
		}
	}
}
$(document).ready(function () {

	$("#dialog_poly<?php echo time(); ?>").dialog({
		autoOpen: false,
		draggable: true,
		resizable: true,
		modal: false,
		position:['right',0],
		title:'<?php echo $this->lang->line("Create_Area"); ?>',
		beforeClose: function(event, ui) { clearSelection<?php echo time(); ?>(); newPoly = false; }
	});
	$("#area_confirm_dialog<?php echo time(); ?>").dialog({
		autoOpen: false,
      buttons : {
        "Confirm" : function() {
          confirmDeleteArea();
		  $(this).dialog("close");
        },
        "Cancel" : function() {
          $(this).dialog("close");
        }
      }
    });
	
	 $(function() {
    $("#address_geo").autocomplete({
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
        map_geo.setCenter(location);
		map_geo.setZoom(11);
      }
    });
  });
	<?php if($area_id!=""){ ?>
		editArea(<?php echo $area_id; ?>,glob_poly);
	<?php } ?>
  $("#loading_top").css("display","none");
  
});
function filterAddressbook(id){
	$.post("<?php echo base_url(); ?>index.php/home/filterAddressbook",	
		{id : id},
		function(data){
			$("#addressbook<?php echo time(); ?>").html(data.opt);		
	}, 'json');	
}
function measureCalc() {
    var area = google.maps.geometry.spherical.computeArea(poly.getPath());
    jQuery("#span-area").text(area.toFixed(1));
}
function measureReset() {

    jQuery("#span-area").text(0);

}

</script>
<div id="dialog_poly<?php echo time(); ?>" style="display:none">
</div>
<div id="area_confirm_dialog<?php echo time(); ?>" style="display:none"><?php echo $this->lang->line("Do you want to delete this record"); ?> ?
</div>
<div id="search_geo" class="formtable" style="padding-bottom:5px; display:none;">
<label><?php echo $this->lang->line("Address"); ?>: </label><input id="address_geo" style="width:250px;" class="text ui-widget-content ui-corner-all" type="text"/>
</div>
<div id="map_geo" style="width: 100%; height: 98%; position:relative;"></div>
<div id="area_size_div" style="display:none;"><p><?php echo $this->lang->line("Area_Size"); ?> : <span id="span-area"></span> mt&sup2;</p></div>
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