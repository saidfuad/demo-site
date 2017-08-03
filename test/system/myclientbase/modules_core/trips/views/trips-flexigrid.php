<script type="text/javascript">
function cancel_group(){
	$('#allgroup_list_div').show();
	$('#allgroup_form_div').hide();
	jQuery("#allgroup_list_div").flexReload();
}
function actionGroup(com,grid)
{
    if (com=='Select All')
    {
		$('.bDiv tbody tr',grid).addClass('trSelected');
    }
    
    if (com=='DeSelect All')
    {
		$('.bDiv tbody tr',grid).removeClass('trSelected');
    }
	if (com=='Invert Selection')
    {
		var rows = $("table#country_list").find("tr").get();
		$.each(rows,function(i,n) {
			$(n).toggleClass("trSelected");
		});
		
    }
    if (com=='Export')
    {
		document.location = "<?php echo site_url("/group/export"); ?>"
    }
    if (com=='Delete')
        {
           if($('.trSelected',grid).length>0){
			   if(confirm('Delete ' + $('.trSelected',grid).length + ' items?')){
		            var items = $('.trSelected',grid);
		            var itemlist ='';
		        	for(i=0;i<items.length;i++){
						itemlist+= items[i].id.substr(3)+",";
					}
					$.ajax({
					   type: "POST",
					   url: "<?php echo site_url("/group/ajax/deletec"); ?>",
					   data: "items="+itemlist,
					   success: function(data){
					   	$('#allgroup_list').flexReload();
					  	$("#dialog").html(data);
						$("#dialog").dialog(open);
					   }
					});
				}
			} else {
				return false;
			} 
        } 
	if(com=='Edit'){
		var items = $('.trSelected',grid);
		if(items.length > 1){
			alert('<?php echo $this->lang->line("Please Select Only One Row"); ?>');
			return false;
		}
		else if(items.length == 0){
			alert('<?php echo $this->lang->line("Please Select Row"); ?>');
			return false;
		}else{
			var id = items[0].id.substr(3);
		}
		$("#loading_dialog").dialog("open");
		$('#allgroup_form_div').show();
		$('#allgroup_list_div').hide();
		$('#allgroup_form_div').load('<?php echo site_url('group/form/id'); ?>/'+id);
		
		
	} 
	if(com=='Add'){
		$("#loading_dialog").dialog("open");
		$('#allgroup_list_div').hide();
		$('#allgroup_form_div').show();
		$('#allgroup_form_div').load('<?php echo site_url('/group/form/'); ?>');
		
	}  
	
} 
function submitFormGroup(id){
	$("#loading_dialog").dialog("open");
	$.post("<?php echo site_url('group/form/id'); ?>/"+id, $("#frm").serialize(), 
			function(data){
				if(data){
					$('#allgroup_form_div').html(data);
				}else{
					if(id != "")
						$("#dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
					else
						$("#dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
					$("#dialog").dialog('open');
					jQuery("#allgroup_list").flexReload();
				}
				$("#loading_dialog").dialog("close");
			} 
		);
	return false;	
}
$(document).ready(function() {
	jQuery("input:button, input:submit, input:reset").button();	
	$("#ui-datepicker-div").hide();

	$("#dialog").dialog({
	  autoOpen: false,
	  modal: true
	});
		
});
$("#loading_dialog").dialog("close");
</script>
<?php
echo $js_grid;
?>

<div id="allgroup_list_div" style="padding:10px;">
<table id="allgroup_list" style="display:none"></table>
</div>
<div id="allgroup_form_div" style="padding:10px;display:none">
</div>
