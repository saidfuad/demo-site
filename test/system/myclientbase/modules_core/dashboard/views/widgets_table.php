<?php
	
	$headers = count($table["headers"]);
	
	if(! isset($class) || trim($class) == '') {
		$class = "color-red";
	}
	
    $my_template = array (
		'table_open' 			=> '<table border="0" class="'.$class.' abc" cellspacing="1" cellpadding="4" width="100%" style=" color:#000000;">',

		'heading_row_start' 	=> '<tr style="background-color:#ebf4fb">',
		'heading_row_end' 		=> '</tr>',
		'heading_cell_start'	=> '<th style="height:18px;">',
		'heading_cell_end'		=> '</th>',
		
		'row_start' 			=> '<tr style="background-color: #FFFFFF;">',
		'row_end' 				=> '</tr>',
		'cell_start'			=> '<td style="height:17px; padding-left:2px; text-align: center;">',
		'cell_end'				=> '</td>',
		
		'row_alt_start' 		=> '<tr style="background-color: rgb(245, 245, 245);">',
		'row_alt_end' 			=> '</tr>',
		'cell_alt_start'		=> '<td style="height:17px; padding-left:2px; text-align: center;">',
		'cell_alt_end'			=> '</td>',

		'table_close' 			=> '</table>'
	);	
	
	$this->table->set_heading($table["headers"]);
	
	$this->table->set_template($my_template);
	
	foreach($table["body"] as $row=>$value) {
		$this->table->add_row($value);
	}
	
	echo $this->table->generate();
	$this->table->clear();
?>
