<?php //$this->load->view('users/header'); ?>
<script type="text/javascript">
function cancel_users(){
	$('#users_list_div').show();
	$('#users_form_div').hide();
	jQuery("#users_list_div").flexReload();
}
function actionUsers(com,grid)
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
		var rows = $("table#users_list").find("tr").get();
		$.each(rows,function(i,n) {
			$(n).toggleClass("trSelected");
		});
		
    }
    if (com=='Export')
    {
		document.location = "<?php echo site_url("/users/export"); ?>"
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
					   url: "<?php echo site_url("/users/ajax/deletec"); ?>",
					   data: "items="+itemlist,
					   success: function(data){
					   	$('#users_list').flexReload();
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
			alert('please select only one row');
			return false;
		}
		else if(items.length == 0){
			alert('please select row');
			return false;
		}else{
			var id = items[0].id.substr(3);
		}
		$("#users_loading").dialog("open");
		$('#users_form_div').show();
		$('#users_list_div').hide();
		$('#users_form_div').load('<?php echo site_url('users/form/id'); ?>/'+id);
		
		
	} 
	if(com=='Add'){
		$("#users_loading").dialog("open");
		$('#users_list_div').hide();
		$('#users_form_div').show();
		$('#users_form_div').load('<?php echo site_url('/users/form/'); ?>');
		
	}  
	
} 
function submitFormUsers(id){
	$("#users_loading").dialog("open");
	$.post("<?php echo site_url('users/form/id'); ?>/"+id, $("#frm_users").serialize(), 
			function(data){
				if(data){
					$('#users_form_div').html(data);
				}else{
					if(id != "")
						$("#dialog").html('<?php echo $this->lang->line("Record Updated Successfully"); ?>');
					else
						$("#dialog").html('<?php echo $this->lang->line("Record Inserted Successfully"); ?>');
					$("#dialog").dialog('open');
					jQuery("#users_list").flexReload();
				}
				$("#users_loading").dialog("close");
			} 
		);
	return false;	
}
$(document).ready(function() {
	jQuery("input:button, input:submit, input:reset").button();	
	$("#ui-datepicker-div").hide();
	$("#users_loading").dialog({
	  autoOpen: false,
	  modal: true
	});
	
	$("#dialog").dialog({
	  autoOpen: false,
	  modal: true
	});
		
});
$("#users_loading").dialog("close");
</script>
<?php
echo $js_grid;
?>
<div id="users_list_div" style="padding:10px;">
<table id="users_list" style="display:none"></table>
</div>
<div id="users_form_div" style="padding:10px;display:none;">
</div>
<div id="users_loading" title="" style="display:none"><?php echo $this->lang->line("Loading..."); ?></div>
