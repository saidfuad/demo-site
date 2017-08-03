<script>
	jQuery().ready(function (){
	$("#loading_dialog").dialog("close");
		jQuery("input:button, input:submit, input:reset").button();
	});	
	
</script>
<style>
dd {
    margin: 0 0 20px 10px !important;
}
</style>
<div class="content toggle">
	<?php $this->load->view('dashboard/system_messages'); ?>
	<form class='formtable' id='form_settings'>
	
	<table border=1 width='100%'>
		<tbody>
			<tr>
				<td>Message<input type='text'  class='text ui-widget-content ui-corner-all' value='<?php echo $result->data_value; ?>' id="message" name='message'></td>
			</tr>
			<tr>
				<td colspan=2 align='center' style='height:47px;padding-top:14px'><input type='button' value='Save Changes' onclick='form_submit()'></td>
			</tr>
		</tbody>
	</table>	
	</form>
</div>