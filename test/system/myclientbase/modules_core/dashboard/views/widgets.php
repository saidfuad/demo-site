<?php	
		$wdg = '';
		
		//widget status min or max
		$min_max = "";
		if($reports->rpt_status == "min")	$min_max = "display:none;";		//if min then hide content
				
		if($secondColumn == "" && $loop == ceil(count($total_reports)/2)){		//start second column
			
			$wdg .='</ul>';
			$wdg .='<ul id="column2" class="column" style="white-space:0px;list-style:none">';
		}
		else if($reports->id == $newColumn[0])
		{
			
			$wdg .='</ul>';
			$wdg .='<ul id="column2" class="column" style="white-space:0px;list-style:none">';
		}
				
		if(trim($reports->rpt_color) == '') $reports->rpt_color = 'color-red';
		$wdg .='<li class="widget '.$reports->rpt_color.'" id="'.$loop.'">';
		$wdg .='<div class="widget-index" style="display:none;">'.$reports->id.'</div>';
		$wdg .='<div class="widget-head"><h3>'.$reports->report_title.', Total Records : '.$reports->count_rec.'</h3><span style="display:none;">'.$command.'</span></div>';
		$wdg .='<div align="center" class="widget-content" style="height:250px; width:100%; overflow-y:auto; '.$min_max.'">';
			$wdg .= $widgets_data;
		$wdg .='</div>';
		$wdg .='</li>';
		
		echo $wdg;
?>
