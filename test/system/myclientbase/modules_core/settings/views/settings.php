<?php $data=$result;?>
<style>
.formtable input.text{
padding:0.4em;
margin-top:
}

#account_settings_tbl td,#pesonal_settings_tbl td {
	vertical-align: middle;
	padding:0px 4px 9px 4px;
	width:50%;
}
#settings_left_panel tr td a{
	display:block;
	text-decoration:none;
	line-height:22px;
	padding:5px;
}
</style>
<script>
jQuery().ready(function (){
	$("#loading_top").css("display","none");
	$("#settings_dialog").dialog({
		autoOpen: false,
		height: 'auto',
		width: 'auto',
		draggable: false,
		resizable: false,
		modal: true,
		close : function(){
			
		}
	});
});
function form_submit()
{
	$.post("<?php echo base_url(); ?>index.php/settings/form/form_name/", $("#form_settings").serialize(),
	function(data) {
		if($.trim(data)!=""){
			$('#settings_form_div').html(data);
		}else{
			$("#settings_dialog").html("Record Updated Successfully.");
			$("#settings_dialog").dialog('open');
		}
	});		
}
</script>
<div id="settings_dialog" style="display:none">
</div>
<div id='settings_form_div'>
	<?php $this->load->view('form',$data); ?>
</div>