<div class="ui-layout-center">

<div id="content_userlist">

	<div class="section_wrapper">
	
		<h3 class="title_black"><?php echo $this->lang->line('user_accounts'); ?><div style="float:right; padding-top:5px;"><a href="#" onclick="add()"><?php echo icon('add','Create User', 'gif'); ?></a></div></h3>

		<?php $this->load->view('dashboard/system_messages'); ?>
		<div class="content toggle no_padding">
			<table>
				<tr>
					<th width="5%" scope="col" class="first"><?php echo $this->lang->line('id'); ?></th>
					<th width="20%" scope="col"><?php echo $this->lang->line('name'); ?></th>
					<th width="20%" scope="col"><?php echo $this->lang->line('company_name'); ?></th>
					<th width="20%" scope="col"><?php echo $this->lang->line('email'); ?></th>
					<th width="20%" scope="col"><?php echo $this->lang->line('phone_number'); ?></th>
					<th width="10%" scope="col" class="last"><?php echo $this->lang->line('actions'); ?></th>
				</tr>
				
				<?php foreach ($users as $user) { ?>
				<tr>
					<td class="first"><?php echo $user->user_id; ?></td>
					<td><?php echo $user->last_name . ', ' . $user->first_name; ?></td>
					<td><?php echo $user->company_name; ?></td>
					<td><?php echo $user->email_address; ?></td>
					<td><?php echo $user->phone_number; ?></td>
					<td class="last">
						<a href="#" title="<?php echo $this->lang->line('edit'); ?>" onclick="edit('<?php echo $user->user_id; ?>')">
						<!--<a href="<?php echo site_url('users/form/user_id/' . $user->user_id); ?>" title="<?php echo $this->lang->line('edit'); ?>"  onclick="edit('<?php echo $user->user_id; ?>')">-->
						
						<?php echo icon('edit'); ?>
						</a>
						<a href="#" title="<?php echo $this->lang->line('delete'); ?>" onclick="deleteRec('<?php echo $user->user_id; ?>')">
						<!--<a href="<?php echo site_url('users/delete/user_id/' . $user->user_id); ?>" title="<?php echo $this->lang->line('delete'); ?>" onclick="javascript:if(!confirm('<?php echo $this->lang->line('confirm_delete'); ?>')) return false">-->
							<?php echo icon('delete'); ?>
						</a>
					</td>
				</tr>
				<?php } ?>
				</table>
			<?php if ($this->mdl_users->page_links) { ?>
			<div id="pagination">
				<?php echo $this->mdl_users->page_links; ?>
			</div>
			<?php } ?>
			
		</div>
		
	</div>
	</div>
	
</div>
<script type="text/javascript">
var deleteId;
function edit(id){
	$("#loading_dialog").dialog("open");
	$('#content_userlist').load('<?php echo site_url('users/form/user_id'); ?>/'+id);
}
function add(id){
	$("#loading_dialog").dialog("open");
	$('#content_userlist').load('<?php echo site_url('users/form/'); ?>');
}

function deleteRec(id){
	deleteId = id;
	$("#dialog").dialog("open");
}
function cancel(){
	$("#loading_dialog").dialog("open");
	$('#content_userlist').load('<?php echo site_url('users/userlist'); ?>');
}
function paging(page){
	$("#loading_dialog").dialog("open");
	$('#content_userlist').load('<?php echo site_url('users/userlist/page'); ?>/'+page);
}
function submitForm(id){
	$("#loading_dialog").dialog("open");
	$.post("<?php echo site_url('users/form/user_id'); ?>/"+id, $("#frm").serialize(), 
			function(data){
				if(data){
					$('#content_userlist').html(data);
				}else{
					$('#content_userlist').load('<?php echo site_url('users/userlist'); ?>');
				}
			} 
		);
	return false;	
}
function search(id){
	alert('sdfs');
	return false;	
}

$(document).ready(function() {
	$("#dialog").dialog({
	  autoOpen: false,
	  modal: true,
	  buttons : {
		"Confirm" : function() {
		   //$('#content_userlist').load('<?php echo site_url('users/delete/user_id'); ?>/'+deleteId);
		   $.post("<?php echo site_url('users/delete/user_id'); ?>/"+deleteId, 
				function(data){
					$('#content_userlist').load('<?php echo site_url('users/userlist'); ?>');
					$("#dialog").dialog("close");
				} 
			);
		},
		"Cancel" : function() {
		  $(this).dialog("close");
		}
	  }
	});
	
	
});
$("#loading_dialog").dialog("close");

</script>
<div id="dialog" title="Confirmation Required">
  Are you sure to delete this record?
</div>
