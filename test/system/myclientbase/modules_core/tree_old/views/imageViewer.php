<script type="text/javascript">
loadFancyBox();
</script>
<?php
function paginate($page, $tpages, $adjacents, $totalRecords, $limit,$language_formate,$img_ast) {
	$prevlabel = $language_formate->line("prev");
	$nextlabel = $language_formate->line("next");
	$firstlabel = $language_formate->line("First");
	$out = '<div class="sixteen columns centre" id="bottomPaging">';
	if($tpages>1 && $page!=1)
	{
		$out.= "<span><a class='ui-state-default' style='cursor:pointer;' onclick='changePage_img(1,".$img_ast.",".time().")'>".$language_formate->line("First")."</a></span>\n";
	}
	else
	{
		$out.= "<span><a class='ui-state-default paginDisabled' style='cursor:pointer;'>".$language_formate->line("First")."</a></span>\n";
	}
	// previous
	if($page==1) {
		$out.= "<span><a class='ui-state-default paginDisabled'>" . $prevlabel . "</a></span>\n";
	}
	else {
		$out.= "<span><a class='ui-state-default' style='cursor:pointer;' onclick='changePage_img(" . ($page-1) . ",".$img_ast.",".time().")'>" . $prevlabel . "</a></span>\n";
	}
	// first
	if($page>($adjacents+1)) {
		$out.= "<a class='pagelink' onclick='changePage_img(1,".$img_ast.",".time().")'>1</a>\n";
	}
	
	// interval
	if($page>($adjacents+2)) {
		$out.= "...\n";
	}
	
	// pages
	$pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
	$pmax = ($page<($tpages-$adjacents)) ? ($page+$adjacents) : $tpages;
	for($i=$pmin; $i<=$pmax; $i++) {
		if($i==$page) {
			$out.= "<a class='activePage'>" . $i . "</a>\n";
		}
		else {
			$out.= "<a class='pagelink' onclick=changePage_img($i,".$img_ast.",".time().")>" . $i . "</a>\n";
		}
	}
	
	// interval
	if($page<($tpages-$adjacents-1)) {
		$out.= "...\n";
	}
	
	// last
	if($page<($tpages-$adjacents)) {
		$out.= "<a class='pagelink' onclick=changePage_img(" . $tpages . ",".$img_ast.",".time().")>" . $tpages . "</a>\n";
	}
	
	// next
	if($page<$tpages) {
		$out.= "<span><a class='ui-state-default' style='cursor:pointer;' onclick='changePage_img(" . ($page+1) . ",".$img_ast.",".time().")'>" . $nextlabel . "</a></span>\n";
	}
	else {
		$out.= "<span><a class='ui-state-default paginDisabled'>" . $nextlabel . "</a></span>\n";
	}
	
	if($tpages>1 && $page!=$tpages)
	{
		$out.= "<span onclick=changePage_img(" . ($tpages) . ",".$img_ast.",".time().")><a style='cursor:pointer;' class='ui-state-default'>".$language_formate->line("Last")."</a></span>  | \n";
	}
	else
	{
		$out.= "<span><a style='cursor:pointer;' class='ui-state-default paginDisabled'>".$language_formate->line("Last")."</a></span>  | \n";
	}
	//$out.= '<span style="display: inline-block; margin-top: 10px;">'.$language_formate->line("view").' : <strong>1 - 8 of '.$totalRecords.' </strong>  ';
	//$out.= "</span>";
	$out.= '<span style="display: inline-block; margin-top: 10px;">'.$language_formate->line("Total Imaages").' : <strong> '.$totalRecords.' </strong> | '.$language_formate->line("Number of Imaages per page").' : ';
	
	$out.= "<select onchange='changePage_img(1,".$img_ast.",".time().")' style='margin:0' id='numImage".time()."' >";
	$out .= "<option";
	if($limit == 8)
		$out.= " selected='selected'";
	$out.= ">8</option><option";
	if($limit == 12)
		$out.= " selected='selected'";
	$out.= ">12</option><option";
	if($limit == 24)
		$out.= " selected='selected'";
	$out.= ">24</option><option";
	if($limit == 48)
		$out.= " selected='selected'";
	$out.= ">48</option><option";
	if($limit == 98)
		$out.= " selected='selected'";
	$out.= ">98</option><option value='all'";
	if($limit == 'all')
		$out.= " selected='selected'";
	$out.= ">All</option>";
	$out.= "</select> <a style='cursor:pointer;' onclick='changePage_img(" . $page . ",".$img_ast.",".time().")'>Refresh</a></span></div>";
	return $out;
}


?>
<?php
 $date_format = $this->session->userdata('date_format');  
 $time_format = $this->session->userdata('time_format');  
 $js_date_format = $this->session->userdata('js_date_format');  
 $js_time_format = $this->session->userdata('js_time_format');
?>
<script>

$(document).ready(function(){
	$(".fancybox").fancybox({
		loop:false,
		fitToView:false,
		openEffect:'fade',
		closeEffect:'fade',
		nextEffect:'fade',
		prevEffect:'fade' 
		});
});
</script>
<style>
.td_padding{
	padding:2px;
}
.fancybox-custom .fancybox-skin {
	box-shadow: 0 0 50px #222;
}
</style>
	<div id="ImageContainer_<?php echo $img_assets_id; ?>" align="center">
	 <table><tr>
	<?php if(count($coords) > 0) {
		$cntr=0;
		foreach ($coords as $coord) {
		
		if($cntr!=0 && $cntr%4==0){
		?>
		</tr><tr><td class="td_padding" align="center">
		<a class="fancybox" rel="gallery1" href="<?php echo base_url(); ?>assets/captured/<?php echo $coord->captured_image; ?>" title="<?php echo date('d.m.Y h:i A', strtotime($coord->add_date)); ?>"><img src="<?php echo base_url(); ?>assets/captured/<?php echo $coord->captured_image; ?>" width="200"/></a><br /><?php echo date('d.m.Y h:i A', strtotime($coord->add_date)) ; ?>
		<td>
		<?php
		}else{ ?>
		<td class="td_padding" align="center">
		<a class="fancybox" rel="gallery1" href="<?php echo base_url(); ?>assets/captured/<?php echo $coord->captured_image; ?>" title="<?php echo date('d.m.Y h:i A', strtotime($coord->add_date)); ?>"><img src="<?php echo base_url(); ?>assets/captured/<?php echo $coord->captured_image; ?>" width="200"/></a><br /><?php echo date('d.m.Y h:i A', strtotime($coord->add_date)) ; ?>
		<td>
		<?php }
		$cntr++;
		}
	}
	else
	{
		echo "<li style='padding-top:10%;float:none'>No Data Found</li>";
	}
	?>
	</tr></table>
	<?php if($this->session->userdata('show_dash_paging')==1){
	 echo paginate($page, $totalPage, 5, $totalRecords, $limit,$this->lang,$img_assets_id); 
	 }
	?>
    
	</div>
	<div style="clear:both"></div>
	
<div style="clear:both"></div>
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