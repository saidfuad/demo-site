<style>
#load_user_menu_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript"> 
jQuery().ready(function (){ 
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button(); 
	jQuery("#user_menu_grid").jqGrid({
		url:"<?php echo site_url('user_menu/loadData'); ?>", 
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Menu Name"); ?>','<?php echo $this->lang->line("Display"); ?>','<?php echo $this->lang->line("Priority"); ?>','<?php echo $this->lang->line("Where To Show"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"menu_name",editable:true, index:"menu_name", width:90, align:"center", jsonmap:"menu_name"},
		//	{name:"menu_id",editable:true, index:"menu_id", width:90, align:"center", jsonmap:"menu_id",formatter:formatter_yes_no},
			{name:"status",editable:true, index:"status", width:90, align:"center", jsonmap:"status",formatter:formatter_yes_no},
			{name:"priority",editable:true, index:"priority", width:90, align:"center", jsonmap:"priority"},
			{name:"where_to_show",editable:true, index:"where_to_show", width:90, align:"center", jsonmap:"where_to_show"}
		],
		rowNum:100,
		height: 'auto',  
		rownumbers: true,
		autowidth: true,
		shrinkToFit: true,
		rowList:[10,20,30,50,100],
		pager: jQuery("#user_menu_pager"),
		sortname: "id",
		loadComplete: function(){
			$("#loading_top").css("display","none");
		},
		loadBeforeSend: function(){$("#loading_top").css("display","block");},
		viewrecords: true,
		multiselect: true, 
		sortorder: "desc",
		footerrow : false, 
		userDataOnFooter : false,
		caption:"<?php echo $this->lang->line("User Menu List"); ?>",
		jsonReader: { repeatitems : false, id: "0" }
	});
	
	jQuery("#user_menu_grid").jqGrid("navGrid", "#user_menu_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
	
	jQuery("#user_menu_grid").jqGrid("navButtonAdd","#user_menu_pager",{caption:"<?php echo $this->lang->line("edit"); ?>",
			onClickButton:function(){
				var gsr = jQuery("#user_menu_grid").jqGrid("getGridParam","selarrrow");
				if(gsr.length > 0){
					if(gsr.length > 1){
						$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
						$("#alert_dialog").dialog("open");
					}
					else{
						$('#user_menu_form_div').show();
						$('#user_menu_list_div').hide();
						$('#user_menu_form_div').load('<?php echo site_url('user_menu/form/id'); ?>/'+gsr[0]);
					}
				} else {
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
			}
		});
	$("#loading_top").css("display","none");
});
function submitFormuser_group(id)
{
	$.post("<?php echo site_url('user_menu/form/id'); ?>/"+id, 
		$("#frm_user_menu").serialize(), 
			function(data){
				if(data){
					$("#loading_dialog").dialog("close");
					if(data){
						$('#user_menu_form_div').html(data);
					}
					if(id != ""){  
						$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
					} else { 
						$("#alert_dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
					}
					
					$('#user_menu_list_div').show();
					$('#user_menu_form_div').hide();
					jQuery("#user_menu_grid").trigger("reloadGrid");
					//}
				}
			});
	return false;	
}
function formatter_yes_no(cellvalue, options, rowObject){
	if(cellvalue == 1)
		return "<span style='color:green'>Yes</span>";
	else
		return "<span style='color:red'>NO</span>";
//	rowObject.account
}
function cancel_user_menu(){
	$('#user_menu_list_div').show();
	$('#user_menu_form_div').hide();
	jQuery("#user_menu_grid").trigger("reloadGrid");
}
function searchallpoints(){
	
	var user_name = $('#user_name').val();
	
	jQuery("#user_menu_grid").jqGrid('setGridParam',{postData:{user_name:user_name, page:1}}).trigger("reloadGrid");
	
	return false;	
}
</script>
<div id="user_menu_list_div">
<form onsubmit="return searchallpoints()">
<div align="center">
<table width="60%">
	<tr>
		<td><?php echo $this->lang->line("User Name"); ?> :</td>
		<td>
		<select name="user_name" id="user_name" class="select ui-widget-content ui-corner-all" style="margin-top: 5px;padding: 0.4em;width: 94%;">
		<option value=" "><?php echo $this->lang->line('Select User Name'); ?></option>
		<?php
				$SQL = "SELECT user_id, username FROM `tbl_users` where usertype_id !=1 "; 		
				$query = $this->db->query($SQL);
				//$row = $query->result(); 
				$level_no = 0;
				foreach ($query->result() as $row)
				{
					$user_id[] = $row->user_id;
				   $username[] = $row->username;
					$level_no++;
				}
				for($i=0;$i<$level_no;$i++){  
					echo "<option value='$user_id[$i]'>".$username[$i]."</option>";
				}
		?> 
		</select>
		</td>
		<td><input type="submit" value="<?php echo $this->lang->line("view"); ?>"/></td>
	</tr>
</table>
</div>	
</form>
<table id="user_menu_grid" class="jqgrid"></table> 
</div>

<div id="user_menu_pager"></div>
<div id="user_menu_form_div" style="padding:10px;display:none;height:450px;">
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