<link href="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/css/dropzone.css') ?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/choosen/chosen.css')?>" rel="stylesheet" type="text/css" />
<style>
.chosen-choices{
    border-radius: 5px;
    padding-top: 5px !important;
}
</style>
<div class="container-fluid">
    <div class="row">
        <form id="edit-expense-form">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Basic Details (<span style="text-transform:lowercase"><sup title="Required field">*</sup> Required fields</span>)
                    </div>
                    <div class="panel-body">
                         <div class="col-md-6">
                             <input class="form-control" style="display:none;" type="text" name="accounting_id" id="accounting_id" value="<?php echo $expense['accounting_id'];?>" />
                          <div class="form-group">
                                <label for="reservation">Choose Vehicle<sup title="Required field">*</sup>:</label>
                                    <select name="vehicle_id" id="vehicle_id" class="form-control">
                                        <?php foreach($vehicles as $new) {
                                            $sel = "";
                                            if($expense['vehicle_id']==$new->vehicle_id){?>
                                                <option selected="selected" value="<?php echo $new->vehicle_id;?>"><?php echo $new->model." - ".$new->plate_no;?></option>
                                            <?php }else{?>
                                                <option value="<?php echo $new->vehicle_id;?>"><?php echo $new->model." - ".$new->plate_no;?></option>
                                            <?php }
                                        }
                                        
                                        ?>
                                        
                                    </select>
                        </div>

                             <div class="form-group">
                                <label for="reservation">Expense Type<sup title="Required field">*</sup>:</label>
                                    <select name="expense_type_id" id="expense_type_id" class="form-control">
                                        <?php foreach($extype as $new) {
                                            $sel = "";
                                            if($expense['expense_type_id']==$new->expense_type_id){?>
                                                <option selected="selected" value="<?php echo $new->expense_type_id;?>"><?php echo $new->name;?></option>
                                            <?php }else{?>
                                                <option value="<?php echo $new->expense_type_id;?>"><?php echo $new->name;?></option>
                                            <?php }
                                        }
                                        
                                        ?>
                                        
                                    </select>
                        </div>
                   
                        <div class="form-group">
                            <label for="reservation">Description<sup title="Required field">*</sup>:</label>
                            <textarea class="form-control" name="description" id="description" maxlength="1000" rows="2"><?php echo $expense['description'];?></textarea>
                        </div>
                    
                         <div class="form-group">
                            <label for="reservation">Amount<sup title="Required field">*</sup>:</label>
                            <input class="form-control" type="number" value="<?php echo $expense['amount'];?>" name="amount" id="amount"/>
                        </div>                              

                        <div class="form-group" style="display:none;">
                            <label for="reservation">Account Name<sup title="Required field">*</sup>:</label>
                            <input class="form-control" type="text" name="account_id" id="account_id" value="<?php echo $accountid; ?>" />
                        </div>

                         <div class="form-group" style="display:none;">
                            <label for="reservation">Add UID<sup title="Required field">*</sup>:</label>
                            <input class="form-control" type="text" name="add_uid" id="add_uid" value="<?php echo $uid; ?>" />
                        </div>
                        <button class="btn btn-primary pull-right" type="submit" id="save-expense">Save </button>


                    </div>

                      <div class="col-md-6 col-lg-6">
                        <div class="col-md-12 bg-crumb" align="center">
                            <h2><i class="fa fa-car"></i>Accounting</h2>
                            <br>
                            <p>Manage your Expenses</p>

                            <a href="<?php echo site_url('accounting'); ?>" class="btn btn-primary">View Accounting Records</a> 
                        </div>

                    </div>

                    </div>
                </div>

                <div class="panel-footer " align="right">
                </div>


            </div>
        </form>
    </div>
</div> 

<script src="<?php echo base_url('assets/js/plugins/dropzone-3.8.4/downloads/dropzone.min.js') ?>"></script>  
<script src="<?php echo base_url('assets/choosen/chosen.jquery.min.js')?>" type="text/javascript"></script>

<script>

    $(function () {

        $('.chzn').chosen();


        $('#edit-expense-form').on('submit', function () {

            var $this = $(this);

            if ($('#vehicle_id').val().trim().length == 0 || $('#expense_type_id').val().trim().length == 0 || $('#amount').val().trim().length == 0) {
                swal({title: "Info", text: "Fill in all required fields ( * )", type: "info", confirmButtonText: "ok"});
                return false;
            }

            $('#save-expense').html('<i class="fa fa-spinner fa-spin"></i>');
            $('#save-expense').prop('disabled', true);

            swal({
                title: "Info",
                text: "Edit Expense Details?",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                allowOutsideClick: false,
                showLoaderOnConfirm: true
            }, function () {
                $.ajax({
                    method: 'post',
                    url: '<?= base_url('index.php/accounting/update_expense') ?>',
                    data: $this.serialize(),
                    success: function (response) {

                            swal({title: "Info", text: "Saved successfully", type: "success", confirmButtonText: "ok"},
                                function (){
                                    document.location.href="<?php echo base_url('index.php/accounting') ?>";
                                }
                            );

                        $('#save-expense').html('Save');
                        $('#save-expense').prop('disabled', false);
                    }
                });
            });

            $('#save-expense').html('Save');
            $('#save-expense').prop('disabled', false);

            return false;
        });

    });
</script> 
