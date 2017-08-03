<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo $this->lang->line('nkonnect'); ?></title>
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/style/css/style.css" />
		       
        <!--[if lte IE 6]>
            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/style/css/jquery.tabs-ie.css" type="text/css" media="projection, screen">
            <link rel="stylesheet" href="<?php echo base_url(); ?>assets/style/css/basic_ie.css" type="text/css" media="projection, screen">
        <![endif]-->
        <!--[if lte IE 7]>
                <link rel="stylesheet" href="<?php echo base_url(); ?>assets/style/css/jquery.tabs-ie.css" type="text/css" media="projection, screen">
            <![endif]-->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/style/css/menu_style.css" />     
		<link type="text/css" href="<?php echo base_url(); ?>assets/jquery/ui-themes/black-tie/jquery-ui-1.8.4.custom.css" rel="stylesheet" />
		<link href="<?php echo base_url(); ?>assets/style/css/flexigrid.css" rel="stylesheet" type="text/css" />
		<style type="text/css">
		.formtable input.text { margin-top:5px; padding-bottom:12px; width:90%; padding: .4em;  }
		.formtable lable { height:10px;  }
		.formtable input.button { margin-top:5px;padding-bottom:12px; width:90%; padding: .4em; }
		.formtable select { margin-top:5px;padding-bottom:12px; width:92%; padding: .4em;}
		.formtable textarea { margin-top:5px;padding-bottom:12px; width:90%; padding: .4em; }
		.ui-state-error { padding: .3em;}
		.formtable td { padding-top: 0.5em; }
		form{
			width: 100%;
			height: 100%;
		}
		</style>		
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/jquery.ui.all.js"></script>		
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/jquery.layout.js"></script>		
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/menu.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/jquery.simplemodal.js"></script>
  		<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/jquery-ui.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/ui.tabs.closable.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/javascript/general.js"></script>
				
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/flexigrid.pack.js"></script>
				
        <?php echo $headerjs;	// Loading the Google Map javascript api file ?>
		<?php if (isset($header_insert)) { $this->load->view($header_insert); } ?>
		<script type="text/javascript">
			
			$(document).ready(function () {
			var file;
			var menu;
			var tId;
			var tabArr = Array();
			
			$('#tabs').tabs({
				closable: true, 
				cache:true, 
				remove: function(e, ui){
					tabArr = removeItems(tabArr, ui.panel.id)
				},
				add: function(e, ui){
					$('#tabs').tabs('select', '#' + ui.panel.id);					
					tId = ui.panel.id;
					tabArr.push(ui.panel.id);
				}
			});
				
			$('.link')
				.click(function(){
					$(".link").css('background-color','');
					$(this).css('background-color','#F7B549');
					var inarray = false;
					for(i=0; i<tabArr.length; i++){
						if(tabArr[i] == this.id)
							inarray = true;
					}
					if(inarray == true){
						var tabid = 'ui-tabs-2';
						$('#tabs').tabs('select', '#' + this.id);
					}else{
						$('#tabs').tabs('add', this.name, this.title);
						$(this).attr('overflow', 'auto');
						$(this).attr('id', tId);
						return false;
					}
			});
			
   			$('a[rel="external"]').click( function() {
        		window.open( $(this).attr('href') );
        		return false;
			});
                    
            function removeItems(array, item) {
				var i = 0;
				while (i < array.length) {
					if (array[i] == item) {
						array.splice(i, 1);
					} else {
						i++;
					}
				}
				return array;
			}	

			
			myLayout = $('body').layout({
				onclose_end: function () {
					var obj = document.getElementById('imgmaxmin');
					var is_close_west  = myLayout.state.west.isClosed;
					var is_close_south = myLayout.state.south.isClosed;
					var is_close_north = myLayout.state.north.isClosed;
					if(is_close_west==true && is_close_north==true && is_close_south==true){
						obj.alt="min";
						obj.title = "Minimize";
						obj.src="<?php echo base_url(); ?>assets/style/img/min_button.png";
					}
				},
				onopen_end: function () {
					var obj = document.getElementById('imgmaxmin');
					var is_close_west  = myLayout.state.west.isClosed;
					var is_close_south = myLayout.state.south.isClosed;
					var is_close_north = myLayout.state.north.isClosed;
					if(is_close_west==false || is_close_north==false || is_close_south==false){
						obj.alt="max";
						obj.title = "Maximize";
						obj.src="<?php echo base_url(); ?>assets/style/img/max_button.png";
					}
				}
			});
			
			myLayout.toggle('south');
			if(file=='' && menu==''){
				myLayout.toggle('west');	
			}
			if(file=='' && menu==''){
				myLayout.toggle('north');	
			}
			if(menu == 'aboutus'){
				myLayout.toggle('west');
			}
			
			$("input[type=text]").keyup(function(){
				// Select field contents
				if($(this).attr("alt")==""){
					transcrire(this);
				}
			});
			$("textarea").keyup(function(){
				// Select field contents
				if($(this).attr("alt")==""){
					transcrire(this);
				}
			});
			
		});
				
		function maximize(obj){
			
			if(obj.alt=="max"){
				$.each('north,west'.split(','), function(){myLayout.close(this);});
			}
			else{
				$.each('north,west'.split(','), function(){myLayout.open(this);});								
			}
		}
		function loadDefault(){
			$.each('north,west'.split(','), function(){myLayout.open(this);});
		}
		
		</script>
		<?php //echo $headermap; ?>
	</head>
    <body>
    <div class="ui-layout-north">
     	  
	  <div id="header_wrapper">

			<div class="container_10" id="header_content">

				<h1><?php echo $this->lang->line('nkonnect'); ?></h1>

			</div>

		</div>

		<div id="navigation_wrapper">

			<ul class="container_10" id="navigation">

				<li <?php if (!$this->uri->segment(1) or $this->uri->segment(1) == 'dashboard') { ?>class="selected"<?php } ?>><?php echo anchor('dashboard', $this->lang->line('dashboard')); ?></li>
			
				<?php if ($this->session->userdata('global_admin')) { ?>

				<li <?php if ($this->uri->segment(1) == 'live') { ?>class="selected"<?php } ?>>
					<?php echo anchor('live', $this->lang->line('live')); ?>
				</li>
				
				<li <?php if ($this->uri->segment(1) == 'reports') { ?>class="selected"<?php } ?>>
					<?php echo anchor('reports', $this->lang->line('reports')); ?>
				</li>
				
				<li <?php if ($this->uri->segment(1) == 'assets') { ?>class="selected"<?php } ?>>
					<?php echo anchor('assets', $this->lang->line('assets')); ?>
				</li>
				
				<li <?php if ($this->uri->segment(1) == 'group') { ?>class="selected"<?php } ?>>
					<?php echo anchor('group', 'Group'); ?>
				</li>
				
				<li <?php if ($this->uri->segment(1) == 'fields') { ?>class="selected"<?php } ?>>
					<?php echo anchor('alerts', $this->lang->line('alerts')); ?>
				</li>
				
				<li <?php if (!$this->uri->segment(1) or $this->uri->segment(1) == 'settings') { ?>class="selected"<?php } ?>><?php echo anchor('settings', $this->lang->line('settings')); ?></li>
				
				<li <?php if ($this->uri->segment(1) == 'contact') { ?>class="selected"<?php } ?>>
					<?php echo anchor('contact', $this->lang->line('contact')); ?>
				</li>
                
                <li <?php if ($this->uri->segment(1) == 'concept') { ?>class="selected"<?php } ?>>
					<?php echo anchor('concept', $this->lang->line('concept')); ?>
				</li>
                
				<li <?php if ($this->uri->segment(1) == 'about') { ?>class="selected"<?php } ?>>
					<?php echo anchor('about', $this->lang->line('about')); ?>
				</li>

				<?php } else { ?>
                  <li <?php if ($this->uri->segment(1) == 'live') { ?>class="selected"<?php } ?>> <?php echo anchor('live', $this->lang->line('live')); ?> </li>
                  <li <?php if ($this->uri->segment(1) == 'reports') { ?>class="selected"<?php } ?>> <?php echo anchor('reports', $this->lang->line('reports')); ?> </li>
                  <?php } ?>
				
				<li><?php echo anchor('sessions/logout', $this->lang->line('log_out')); ?></li>
			</ul>

		</div>
		
    </div>
