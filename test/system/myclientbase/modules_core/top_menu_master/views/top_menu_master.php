<style>
#load_top_menu_master_grid
{
	display:none !important; 
}
</style>
<script type="text/javascript">
var menuname="";
jQuery().ready(function (){ 
	jQuery(".date").datepicker({dateFormat:"dd.mm.yy",changeMonth: true,changeYear: true});
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#top_menu_master_grid").jqGrid({
		url:"<?php echo site_url('top_menu_master/loadData'); ?>", 
		datatype: "json",
		colNames:["<?php echo $this->lang->line("ID"); ?>",'<?php echo $this->lang->line("Menu Id"); ?>','<?php echo $this->lang->line("Menu Name"); ?>','<?php echo $this->lang->line("Display"); ?>','<?php echo $this->lang->line("Comments"); ?>'],
		colModel:[
			{name:"id",index:"id",hidden:true, width:15, jsonmap:"id"},
			{name:"menu_id",editable:true,hidden:true, index:"menu_id", width:90, align:"center", jsonmap:"menu_id"},
			{name:"menu_name",editable:true, index:"menu_name", width:90, align:"center", jsonmap:"Text"},
			{name:"status",editable:true, index:"status", width:90, align:"center", jsonmap:"status",formatter:formatter_yes_no},
			{name:"comments",editable:true,hidden:true, index:"comments", width:90, align:"center", jsonmap:"comments"}
		],
		rowNum:100,
		height: 'auto', 
		rownumbers: true,
		autowidth: true,
		shrinkToFit: true,
		rowList:[10,20,30,50,100],
		pager: jQuery("#top_menu_master_pager"),
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
		caption:"<?php echo $this->lang->line("User Top Menu List"); ?>",
		jsonReader: { repeatitems : false, id: "0" }
	});
	
	jQuery("#top_menu_master_grid").jqGrid("navGrid", "#top_menu_master_pager", {add:false, edit:false, del:false, search:false}, {}, {}, {}, {multipleSearch:false});
	
	jQuery("#top_menu_master_grid").jqGrid("navButtonAdd","#top_menu_master_pager",{caption:"<?php echo $this->lang->line("edit"); ?>",
			onClickButton:function(){
				var gsr = jQuery("#top_menu_master_grid").jqGrid("getGridParam","selarrrow");
				if(gsr.length > 0){
					if(gsr.length > 1){
						$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Only One Row"); ?>");
						$("#alert_dialog").dialog("open");
					}
					else{
						$('#top_menu_master_form_div').show();
						$('#top_menu_master_llist_div').hide();
						$('#top_menu_master_form_div').load('<?php echo site_url('top_menu_master/form/id'); ?>/'+gsr[0]);
					}
				} else {
					$("#alert_dialog").html("<?php echo $this->lang->line("Please Select Row"); ?>");
					$("#alert_dialog").dialog("open");
				}
			}
		}); 
		cancelloading();
});
function submitFormuser_top_menu_group(id)
{
	$.post("<?php echo site_url('top_menu_master/form/id'); ?>/"+id, 
		$("#frm_top_menu_master").serialize(), 
			function(data){
				if(data){
					if(id != "")
						$("#alert_dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
					else
						$("#alert_dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
					$("#alert_dialog").dialog('open');
					$('#top_menu_master_llist_div').show();
					$('#top_menu_master_form_div').hide();
					$("#alert_dialog").dialog('open');
					jQuery("#top_menu_master_grid").trigger("reloadGrid");
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
function cancel_top_menu_master(){
	$('#top_menu_master_llist_div').show();
	$('#top_menu_master_form_div').hide();
	jQuery("#top_menu_master_grid").trigger("reloadGrid");
}
function searchallpoints(){ 
	
	var user_name = $('#user_name').val();
	
	jQuery("#top_menu_master_grid").jqGrid('setGridParam',{postData:{user_name:user_name, page:1}}).trigger("reloadGrid");
	
	return false;	
}
</script>
<div id="top_menu_master_llist_div">
<form onsubmit="return searchallpoints()">
<div align="center">
<table width="60%">
	<tr>
		<td><?php echo $this->lang->line("User Name"); ?> :</td>
		<td>
		<select name="user_name" id="user_name" class="select ui-widget-content ui-corner-all" style="margin-top: 5px;padding: 0.4em;width: 94%;">
		<option value=" "><?php echo $this->lang->line("select User Name"); ?></option>
		<?php
				$SQL = "SELECT user_id, username FROM `tbl_users`"; 		
				$query = $this->db->query($SQL);
				//$row = $query->result(); 
				$level_no = 0;
				foreach ($query->result() as $row)
				{
					if($row->user_id != 1)
					{
						$user_id[] = $row->user_id;
						$username[] = $row->username;
						$level_no++;
					}
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
<table id="top_menu_master_grid" class="jqgrid"></table>
</div>
<div id="top_menu_master_pager"></div>
<div id="top_menu_master_form_div" style="padding:10px;display:none;height:450px;">
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