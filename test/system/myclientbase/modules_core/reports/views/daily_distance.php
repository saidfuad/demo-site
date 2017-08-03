<?php
	$uid = $this->session->userdata('user_id');
?>
<?php
	 $date_format = $this->session->userdata('date_format');  
	 $time_format = $this->session->userdata('time_format');  
	 $js_date_format = $this->session->userdata('js_date_format');  
	 $js_time_format = $this->session->userdata('js_time_format');  
	 
?>
<script type="text/javascript">

jQuery().ready(function (){
	$("#loading_top").css("display","none");
	jQuery("input:button, input:submit, input:reset").button();
	jQuery("#dailydistancesdate").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	jQuery("#dailydistanceedate").datepicker({dateFormat:"<?php echo $js_date_format; ?>",changeMonth: true,changeYear: true});
	
	$("#dailydistancesdate").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s", strtotime("-1 day")); ?>'));
	$("#dailydistanceedate").datepicker('setDate', new Date('<?php echo date("Y/m/d H:i:s"); ?>'));
	/*
	$("#loading_top").css("display","block");
	$.post(
		"http://gatti.nkonnect.com/daily_distance.php",{user:<?php echo $uid; ?>},
		function(data){
			$("#loading_top").css("display","none");
			$("#daily_distance_div").html(data);
		});
		*/
});
function searchdailydistance(){
	
	var sdate = $('#dailydistancesdate').val();
	var edate = $('#dailydistanceedate').val();
	
	$("#loading_top").css("display","block");	
	$.post(
		"http://gatti.nkonnect.com/daily_distance.php",{user:<?php echo $uid; ?>, sdate:sdate, edate:edate},
		function(data){
			$("#loading_top").css("display","none");
			$("#daily_distance_div").html(data);
		});
	
	return false;	
}
function exportDailtDistance(){
	var sdate = $('#dailydistancesdate').val();
	var edate = $('#dailydistanceedate').val();
	document.location = "http://gatti.nkonnect.com/daily_distance.php?cmd=export&sdate="+sdate+"&edate="+edate+"&user=<?php echo $uid; ?>";
}
function printDailtDistance(){	
	var sdate = $('#dailydistancesdate').val();
	var edate = $('#dailydistanceedate').val();
	var url = "http://gatti.nkonnect.com/daily_distance.php?cmd=print&sdate="+sdate+"&edate="+edate+"&user=<?php echo $uid; ?>";
	window.open(url);
}
</script>
<form onsubmit="return searchdailydistance()">
<table width="100%" class="formtable" style="margin-bottom: 5px;">
	<tr>
		<td width="20%"><?php echo $this->lang->line("Start"); ?> : <input type="text" id="dailydistancesdate" class="date text ui-widget-content ui-corner-all" style="width:120px" readonly="readonly"/></td>
		<td width="20%"><?php echo $this->lang->line("End"); ?> : <input type="text" id="dailydistanceedate" class="date text ui-widget-content ui-corner-all" style="width:120px" readonly="readonly"/></td>
		<td width="10%"><input type="submit" value="<?php echo $this->lang->line("view"); ?>"/></td>
		<td width="10%"><input type="button" value="Export" onclick="exportDailtDistance()"></td>
		<td width="10%"><input type="button" value="Print" onclick="printDailtDistance()"></td>
	</tr>
</table>
</form>

<div id="daily_distance_div"></div>

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