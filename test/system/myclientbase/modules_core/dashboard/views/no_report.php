<?php $this->load->view('dashboard/header'); ?>
<?php  //$this->load->view('dashboard/sidebar');  ?>
<link href="<?php echo base_url(); ?>assets/style/css/inettuts.js.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/style/css/inettuts.css" rel="stylesheet" type="text/css" />

<div class="ui-layout-center"> 
  <div id="reload" style="text-align: center;"><?php echo $this->lang->line('No_Default_Reports_In_Dashboard'); ?></div>
</div>
<?php $this->load->view('dashboard/footer'); ?>
