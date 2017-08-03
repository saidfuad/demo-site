<?php if($this->form_model->id == ""){ ?> 
<h3 class="title_black">Add Schedule Report </h3>
<?php }else{ ?>  
<h3 class="title_black">Update Schedule Report </h3>
<?php } ?>

<?php $this->load->view("dashboard/system_messages"); ?>
<div class="content toggle" align="center">
  <form id="frm_schedule_reports" method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" onsubmit="return submitFormschedule_reportsmaster('<?php echo uri_assoc("id")?>')" enctype="multipart/form-data">
    <p id="error" class="addTips" color="#CC0000">* Fields are mendatory</p>
    <table width="80%" align="center" class="formtable">
      <tbody>
       	<tr>
			<td width="50%"><?php echo $this->lang->line("Assests Name(Device)"); ?> <font color="#CC0000">*</font> </td>
			<td width="50%"><select name="assets_ids[]" id="schedule_reports_assets_ids" class="select ui-widget-content ui-corner-all" style="width:50% !important" multiple='multiple' ></select></td>
		</tr>
		<tr>
			<td width="50%"><?php echo $this->lang->line("Email_Address"); ?> <font color="#CC0000">*</font> </td>
			<td width="50%"><input type="text" name="email_addresses" id="schedule_reports_email_addresses" class="text ui-widget-content ui-corner-all" value="<?php echo $this->form_model->email_addresses; ?>"  /></td>
		</tr>
		<tr>
			<?php 
				$array =$this->form_model->reports; 
				if(!is_array($array)){
					$array = explode(",",$array);	
				}
			?>
			<td width="50%"><?php echo $this->lang->line("Reports"); ?> </td>
			<td width="50%">
				<input type="checkbox" style="width:11%" name="reports[]"  class="text ui-widget-content ui-corner-all" 
				<?php echo (in_array(1,$array))?"checked='checked'":""; ?> value=1 />
				<label><?php echo $this->lang->line("Stop Report"); ?></label><Br>
				
				<input type="checkbox" style="width:11%" name="reports[]"  class="text ui-widget-content ui-corner-all" 
				<?php echo (in_array(2,$array))?"checked='checked'":""; ?> value=2 />
				<label><?php echo $this->lang->line("Landmark Report"); ?></label><Br>
				
				<input type="checkbox" style="width:11%" name="reports[]"  class="text ui-widget-content ui-corner-all" 
				<?php echo (in_array(3,$array))?"checked='checked'":""; ?> value=3 />
				<label><?php echo $this->lang->line("Run Report"); ?></label><Br>
				
				<input type="checkbox" style="width:11%" name="reports[]"  class="text ui-widget-content ui-corner-all" 
				<?php echo (in_array(4,$array))?"checked='checked'":""; ?> value=4 />
				<label><?php echo $this->lang->line("Distance Report"); ?></label><Br>
				
				<input type="checkbox" style="width:11%" name="reports[]"  class="text ui-widget-content ui-corner-all" 
				<?php echo (in_array(5,$array))?"checked='checked'":""; ?> value=5 />
				<label><?php echo $this->lang->line("All Points"); ?></label><Br>
				
				<input type="checkbox" style="width:11%" name="reports[]"  class="text ui-widget-content ui-corner-all" 
				<?php echo (in_array(6,$array))?"checked='checked'":""; ?> value=6 />
				<label><?php echo $this->lang->line("Alerts"); ?></label><Br>
				
				<input type="checkbox" style="width:11%" name="reports[]"  class="text ui-widget-content ui-corner-all" 
				<?php echo (in_array(7,$array))?"checked='checked'":""; ?> value=7 />
				<label><?php echo $this->lang->line("Battery Status"); ?></label><Br>
			</td>
		</tr>
		<tr>
		<?php 
	$array =$this->form_model->daily_monthly_weekly; 
	if(!is_array($array)){
		$array = explode(",",$array);	
	}
?>
			<td width="50%"><?php echo $this->lang->line("Report_Type"); ?> </td>
			<td width="50%">
			<input type="checkbox" style="width:11%" name="daily_monthly_weekly[]" id="schedule_reports_daily" class="text ui-widget-content ui-corner-all" 
			<?php echo (in_array(1,$array))?"checked='checked'":""; ?> value=1 />
			<label><?php echo $this->lang->line("schedule_reports_daily"); ?></label>
			
			<input type="checkbox" style="width:11%" name="daily_monthly_weekly[]" id="schedule_reports_monthly" class="text ui-widget-content ui-corner-all" 
			<?php echo (in_array(2,$array))?"checked='checked'":""; ?> value=2 />
			<label><?php echo $this->lang->line("schedule_reports_monthly"); ?></label>
			
			<input type="checkbox" style="width:11%" name="daily_monthly_weekly[]" id="schedule_reports_weekly" class="text ui-widget-content ui-corner-all" 
			<?php echo (in_array(3,$array))?"checked='checked'":""; ?> value=3 />
			<label><?php echo $this->lang->line("schedule_reports_weekly"); ?></label>
			</td>
		</tr>
		<tr>
			<td width="50%"><?php echo $this->lang->line("File_Type"); ?></td>
					<?php 
	$array =$this->form_model->excel_pdf; 
	if(!is_array($array)){
		$array = explode(",",$array);	
	}
?>
			<td width="50%">			
			<input type="checkbox" style="width:11%" name="excel_pdf[]" id="schedule_reports_excel" class="text ui-widget-content ui-corner-all" 
			<?php echo (in_array(1,$array))?"checked='checked'":""; ?> value=1 />
			<label><?php echo $this->lang->line("schedule_reports_excel"); ?></label>
			
			<input type="checkbox" style="width:11%" name="excel_pdf[]" id="schedule_reports_pdf" class="text ui-widget-content ui-corner-all" 
			<?php echo (in_array(2,$array))?"checked='checked'":""; ?> value=2 />
			<label><?php echo $this->lang->line("schedule_reports_pdf"); ?></label>
			</td>
		</tr>
		
		<tr>
          <td align="center" colspan="2"><input type="submit" id="btn_menu_submit" value="Submit" name="btn_menu_submit"/>
            &nbsp;&nbsp;
            <input type="button" id="btn_cancel" onclick="cancel_schedule_reports()" name="btn_cancel" value="Back" /></td> 
        </tr>
		</tbody>
    </table>
  </form>
</div>
<?php 
	$array =$this->form_model->assets_ids; 
	if(!is_array($array)){
		$array = explode(",",$array);	
	}
?>
<script type="text/javascript">
	jQuery("input:button, input:submit, input:reset").button();	
	$("#schedule_reports_assets_ids").html(assets_combo_opt_report);
	<?php
		foreach($array as $key){
			echo '$("#schedule_reports_assets_ids option[value=\''.$key.'\']").attr("selected","selected");';
		}
	?>
	 $("#loading_top").css("display","none");
	 $("#schedule_reports_assets_ids").dropdownchecklist({ firstItemChecksAll: true, textFormatFunction: function(options) {
                var selectedOptions = options.filter(":selected");
                var countOfSelected = selectedOptions.size();
                switch(countOfSelected) {
                    case 0: return "<i>Please Select<i>";
                    case 1: return selectedOptions.text();
                    case options.size(): return "<b>All Assets</b>";
                    default: return countOfSelected + " Assets";
                }
            }, icon: {}, width: 150});
	$("#ddcl-schedule_reports_assets_ids").css('vertical-align','middle');
	$("#ddcl-schedule_reports_assets_ids-ddw").css('overflow-x','hidden');
	$("#ddcl-schedule_reports_assets_ids-ddw").css('overflow-y','auto');
	$("#ddcl-schedule_reports_assets_ids-ddw").css('height','200px');
	$(".ui-dropdownchecklist-dropcontainer").css('overflow','visible');
	var dev="";
	for(i=0;i<=assets_count;i++){
		if($("#ddcl-lanamarkdevice-i"+i).is(':checked')){
			dev+=$("#ddcl-lanamarkdevice-i"+i).val()+",";
		}
	}
	
	$("input[name='daily_monthly_weekly[]']").change(function(){
		var bool=true;
		$.each($("input[name='daily_monthly_weekly[]']"), function() {
			if($(this).attr("checked")!="checked"){
				bool=false;
			}
		});
	});
	
	$("input[name='excel_pdf[]']").change(function(){
		var bool=true;
		$.each($("input[name='excel_pdf[]']"), function() {
			if($(this).attr("checked")!="checked"){
				bool=false;
			}
		});
	});
</script>