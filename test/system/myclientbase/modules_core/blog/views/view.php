<?php 
	$site_url = base_url()."assets_gatti/";
	$data['site_url']=$site_url;
	$data['page']="blog";
	$this->load->view("sessions/header",$data);
	$count = $comment->num_rows();
	
?>
<script type="text/javascript" src="<?php echo $site_url; ?>js/jquery.timeago.js"></script>
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
<!-- A BLOG ELEMENT -->
<div class="blog blog_wrapper">
<!-- A BLOG ELEMENT DETAILS -->
<div class="blog_details_wrapper">
<div class="blog_details">
<div class="day"><?php echo date("d",strtotime($row['blog_date'])); ?></div>
<div class="month"><?php echo date("F",strtotime($row['blog_date'])); ?></div>
<div class="comments"><?php echo $count; ?> comments</div>
<div class="author"><?php echo $row['blog_author']; ?></div>
<div class="clear"></div>
</div>
</div>
<!-- THE CONTENT OF BLOG ENTRY -->
<div class="blog_content_wrapper blogstyle2">
<?php if($row['path']!=""){ $row['path'] = str_replace("_base_url_", $site_url, $row['path']); ?>
		<div class="blog_media">
			<?php echo $row['path']; ?>
		</div>
		<?php } ?>

<div class="blog_intro">
<div class="divide20"></div>
<h3><?php echo $row['blog_title']; ?></h3>
<p><?php echo $row['blog_desc']; ?></p>
<div class="divide20"></div>
</div>
<div class="divide40"></div>
<!-- THE COMMENTS -->
<h3 class="thin">Comments <?php echo $count; ?></h3>
<div class="divide40"></div>
<div class="comment_wrapper">

<?php 
	foreach($comment->result_Array() as $rows){
	?>
	 	<!-- COMMENT REPLY -->
		<div class="comment">
		<div class="portrait"><div class="portrait-holder"></div></div>
		<div class="comment-details">
		<h3 class="thin"><?php echo $rows['blog_comment_author']; ?></h3>
		<p><?php echo $rows['blog_comment']; ?></p>
		<div class="divide10"></div>
		<div class="date rightfloat"><abbr class="timeago" title="<?php echo $rows['add_date']; ?>"><?php echo $rows['add_date']; ?></abbr></div>
		</div>
		</div>
		<div class="divide40"></div>
	 <?php	
	}

?>



</div> <!-- END OF THE COMMENT WRAPPER -->
<!-- LEAVE A COMMENT HERE -->
<div class="divide55"></div>
<div class="two_third">
<h3 class="thin">Leave a Comment</h3>
<div class="divide25"></div>
<table class="contacttable">
<tr>
<td>
&nbsp;
</td>
<td>
* Fields Are Mendatory
</td>
</tr>

<tr>
<td>
<h4 class="gray thin">Name*</h4>
</td>
<td>
<input id="contactname" name="contactname">
</td>
</tr>
<tr>
<td>
<h4 class="gray thin">E-mail*</h4>
</td>
<td>
<input id="contactemail" name="contactemail">
</td>
</tr>
<tr>
<td>
<h4 class="gray thin">Message*</h4>
</td>
<td>
<textarea name='contactmessage' id="contactmessage" rows="3"></textarea>
</td>
</tr>
<tr>
<td></td>
<td><div class="divide5"></div><div class="rightfloat"><input type="button" id="post_comment" class="btn btn-primary centered green" value="Post Comment" onClick='post_comment();' /></div></td>
</tr>
</table>
</div>
<div class="one_third lastcolumn"></div>
<div class="clear"></div><!-- END OF LEAVE A COMMENT HERE -->
</div>
</div>
</div><!-- END OF CONTENT CONTAINER HERE -->
</div> <!-- END OF COLORED BG FOR CONTENT -->
<script type="text/javascript">
   jQuery(document).ready(function() {
     $("abbr.timeago").timeago();
	 post_comment = function(){
			if($('#contactname').val() != "" && $('#contactmessage').val() != "" && $('#contactemail').val() != ""){
				var blog_comment_author = $('#contactname').val();
				var blog_comment = $('#contactmessage').val();
				var author_email = $('#contactemail').val();
				$.post("<?php echo site_url("blog/add_comment"); ?>/",{blog_id:<?php echo $id; ?>,blog_comment_author:blog_comment_author,blog_comment:blog_comment,author_email:author_email,count:<?php $t_count=$count+1; echo $t_count; ?>},function(data){
					$("#contactname").val("");
					$("#contactmessage").val("");
					$("#contactemail").val("");
					window.location.reload();
				});
			}
			else{
				alert("All Fields Are Mendatory");
			}
		}
   });
</script>
<!-- A FULLWIDTH CONTAINER // HERE YOU NEED FIRST T OCLOSE THE BOXED CONTENT CONTAINER FIRST-->
<?php $this->load->view("sessions/footer",$data); ?>