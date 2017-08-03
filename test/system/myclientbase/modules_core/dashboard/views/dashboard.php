<?php $this->load->view('dashboard/header'); ?>
<?php  //$this->load->view('dashboard/sidebar');  ?>
<link href="<?php echo base_url(); ?>assets/style/css/inettuts.js.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/style/css/inettuts.css" rel="stylesheet" type="text/css" />

<div class="ui-layout-center"> 
  <!--<div id="tabs">
    <ul>
      <li><a href="#tabs-1">Home</a></li>
      <div class="addons"> <img id="imgmaxmin" src="<?php echo base_url(); ?>assets/style/img/icons/window_full_screen.png" style="cursor:pointer;" alt="max" title="Maximize" onclick="maximize(this)" /> <?php echo anchor(site_url($this->uri->uri_string()), "<img src=\"".base_url()."assets/style/img/icons/new_window.png\" title=\"".$this->lang->line('new_window')."\" />", 'rel="external"'); ?> <?php echo anchor(site_url($this->uri->uri_string()), "<img src=\"".base_url()."assets/style/img/icons/printer.png  \" title=\"".$this->lang->line('print')."\" />", 'rel="print"'); ?> </div>
    </ul>
    <div id="tabs-1">  onclick="doTimer()"-->
  <div id="reload" style="text-align: center;"><span id="message"><?php echo $this->lang->line('Reload_in'); ?><?php echo $interval; ?> <?php echo $this->lang->line('Seconds'); ?></span> <a href="#" onclick="doTimer();" id="txt_timer"><?php echo $auto_reload > 0 ? "Stop Auto Reload" : "Start Auto Reload" ?></a></div>
  <form name="frm_interval" action="" method="post" style="display: inline; ">
    <input type="hidden" name="interval" id="interval" value="<?php echo $interval; ?>" />
  </form>
  <div id="columns" style="height:50%;">
    <ul id="column1" class="column" style="white-space:0px;list-style:none">
      <?php				
			echo $widgets;
		?>
    </ul>
  </div>
</div>

<!-- Js Files For Dash board --> 
<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/jquery-ui-personalized-1.6rc2.min.js"></script> 
<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/inettuts.js"></script> 
<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/jquery.simplemodal.js"></script> 
<script type="text/javascript">
	var T;
	var I;
	var sec = <?php echo $interval; ?>;
	var timer_is_on=0;
	var tim;
	
	$(document).ready(function () {
			
			$('.abc tr').each(function() {
				var dvc = this.cells[1].innerHTML;
				if(dvc != "Device"){
					$(this).click(function(){
						window.location = "<?php echo base_url(); ?>index.php/live/"+dvc;
					});
					var bgc = $(this).css('background-color');
					var fclr = $(this).css('color');
					$(this).mouseover(function(){
						$(this).css('background-color','#000');
						$(this).css('color','#FFF');
						$(this).css('cursor','pointer');
					});
					$(this).mouseout(function(){
						$(this).css('background-color',bgc);
						$(this).css('color',fclr);
					});
					
				}				
			
			});
			$('.widget-content').css("resize", "vertical");
	});
	
	function Refresh() {
		document.frm_interval.submit();
	}
	
	function timedCount() {
		tim = sec;
		if(sec > 0) T = setTimeout ("Refresh()", sec * 1000);
	}
	
	function Counter()	{
		tim = tim - 1;
		if(tim >= 0) $("#message").html("Reload in " + tim + " Seconds");
	}
	
	function doTimer() {
			if (!timer_is_on) {
			timer_is_on=1;
			timedCount();
			I = setInterval ("Counter()", 1000);
			$("#txt_timer").html("<?php echo $this->lang->line('Stop_Auto_Reload'); ?>");
		}
		else {
			clearTimeout(T);
			clearInterval(I);
			timer_is_on = 0;
			sec = tim;
			$("#txt_timer").html("<?php echo $this->lang->line('Start_Auto_Reload'); ?>");
		}
	}
<?php if ($auto_reload) { ?> doTimer(); <?php } ?>
</script>
<?php $this->load->view('dashboard/footer'); ?>
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
