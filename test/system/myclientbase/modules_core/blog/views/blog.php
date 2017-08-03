<?php 
	$site_url = base_url()."assets_gatti/";
	$data['site_url']=$site_url;
	$data['page']="blog";
	$this->load->view("sessions/header",$data);
	
?>
<div class="bgcolor">
<div class="container withverticalpadding content">
<h1 class="pagetitle">Gatti-Blog</h1>
</div> <!-- END OF GRAYVERTICALPADDING CONTENT-->
</div> <!-- END OF PAGETITLE CONTENT PART -->
<!-- A COLORED BG AROUND THE WHLE CONTENT PART -->
<div class="bgcolor white">
<!-- THE CONTENT PART STARTS HERE :) -->
<div class="container withverticalpadding content">
<!-- BLOG ENTRIES -->


	<?php
			if($res->num_rows()==0){
				echo "<h2>No Blog Posts to View</h2>";
			}else{
				if($page>1){?>
				<div class="date leftfloat"><a href='<?php echo site_url("blog/page/".($page-1)); ?>'>New Entries</a></abbr></div>
				<?php }?>
				<?php if($total_page!=$page){?>
				<div class="date rightfloat"><a href='<?php echo site_url("blog/page/".($page+1)); ?>'>Old Entries</a></abbr></div>
				<?php }
				?><Br><Br><?php
				foreach($res->result_Array() as $row){
					?>
						<!-- A BLOG ELEMENT -->
						<div class="blog blog_wrapper">
							<!-- A BLOG ELEMENT DETAILS -->
							<div class="blog_details_wrapper">
								<div class="blog_details">
									<div class="day"><?php echo date("d",strtotime($row['blog_date'])); ?></div>
									<div class="month"><?php echo date("F",strtotime($row['blog_date'])); ?></div>
									<div class="comments"><?php echo $row['total_comment']; ?> comments</div>
									<div class="author"><?php echo $row['blog_author']; ?></div>
									<div class="clear"></div>
								</div>
							</div>
							<!-- THE CONTENT OF BLOG ENTRY -->
							<div class="blog_content_wrapper">
								<?php if($row['path']!="" && $row['small_photo_display']==1){ $row['path'] = str_replace("_base_url_", $site_url, $row['path']); ?>
								<div class="blog_media">
									<?php echo $row['path']; ?>
								</div>
								<?php } ?>
								<div class="blog_intro">
									<h3><?php echo $row['blog_title']; ?></h3>
									<p><?php echo $row['blog_short_desc']; ?></p>
									<?php
										$search = array(
											" ","&","/"
										);
										$result = array(
											"-","-","-"
										);
									?>
									<a href='<?php echo site_url("blog/view/".$row['id']." - ".str_replace($search, $result, $row['blog_title'])); ?>'><div class="more dark"></div></a>
									<div class="clear"></div>
								</div>
							</div>
						</div>
						<div class="divide20"></div>
					<?php
				}
				if($page>1){?>
				<div class="date leftfloat"><a href='<?php echo site_url("blog/page/".($page-1)); ?>'>New Entries</a></abbr></div>
				<?php }?>
				<?php if($total_page!=$page){?>
				<div class="date rightfloat"><a href='<?php echo site_url("blog/page/".($page+1)); ?>'>Old Entries</a></abbr></div>
				<?php }
			}

		?>
		
</div><!-- END OF CONTENT CONTAINER HERE -->
</div> <!-- END OF COLORED BG FOR CONTENT -->

<!-- A FULLWIDTH CONTAINER // HERE YOU NEED FIRST T OCLOSE THE BOXED CONTENT CONTAINER FIRST-->
<?php $this->load->view("sessions/footer",$data); ?>