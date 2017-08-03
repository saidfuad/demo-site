<style>
#suggestions{
    display: none;
}
</style>
<div class="container-fluid">
    <div class="row">
        <form id="add-feedback-form">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Please Write The Feedback You Want To Submit.
                    </div>
                    <div class="panel-body">
                         <div class="col-md-6">
                        <div class="form-group">
                            <label for="reservation">Rate Us:</label>
                            <br>
                            <input type="radio" name="rate_us" id="rate_us" value="Excellent" />Excellent&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="rate_us" id="rate_us" value="Very Good" />Very Good&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="rate_us" id="rate_us" value="Good" checked/>Good&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="rate_us" id="rate_us" value="Average"/>Average&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="radio" name="rate_us" id="rate_us" value="Poor"/>Poor
                        </div>

                         <div class="form-group">
                            <label for="reservation">Choose Types Of Feedback:(You can explain the choices picked in the text box)</label>
                            <br>
                            <input type="checkbox" name="choices[]" id="choices" value="Cannot locate my vehicles on map" />Cannot locate my vehicles on map&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                            <input type="checkbox" name="choices[]" id="choices" value="Vehicle in different location from its actual location" />Vehicle in different location from its actual location&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                            <input type="checkbox" name="choices[]" id="choices" value="Slow To Load"/>Slow To Load&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                            <input type="checkbox" name="choices[]" id="choices" value="Not receiving alert messages or emails"/>Not receiving alert messages or emails&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                            <input type="checkbox" name="choices[]" id="sug" value="Suggestions"/>Suggestions
                        </div>
                    </div>

                     <div class="col-md-6">
                        <div class="form-group" id="comments">
                            <label for="reservation">Other Comments:</label>
                            <textarea class="form-control" rows="7" name="comments"></textarea>
                        </div>

                        <div class="form-group" id="suggestions">
                            <label for="reservation">Suggestions:</label>
                            <textarea class="form-control" rows="7" name="suggestions"></textarea>
                        </div>
                   
                         <div class="form-group" style="display:none;">
                            <label for="reservation">Add UID<sup title="Required field">*</sup>:</label>
                            <input class="form-control" type="text" name="add_uid" id="add_uid" value="<?php echo $uid; ?>" />
                        </div>
                        <br>


                    </div>

                    </div>
                </div>

                <div class="panel-footer " align="right">
                    <button class="btn btn-primary" type="submit" id="save-feedback">Send </button>
                </div>


            </div>
        </form>
    </div>
</div> 

<script>

    $(function () {

        $('#sug').change(function () {
            if(document.getElementById('sug').checked){
                $('#suggestions').css({
                display: 'block'
                });

                $('#comments').css({
                    display: 'none'
                });

            }else{
                 $('#suggestions').css({
                 display: 'none'
                 });

                 $('#comments').css({
                    display: 'block'
                 });
            }
            
        });


        $('#add-feedback-form').on('submit', function () {

            var $this = $(this);

            if ($('#rate_us').val().trim().length == 0 && $('#choices').val().trim().length == 0 && $('#comments').val().trim().length == 0) {
                swal({title: "Info", text: "Fill at least one Field", type: "info", confirmButtonText: "ok"});
                return false;
            }

            $('#save-feedback').html('<i class="fa fa-spinner fa-spin"></i>');
            $('#save-feedback').prop('disabled', true);

            swal({
                title: "Info",
                text: "Write Feedback?",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                allowOutsideClick: false,
                showLoaderOnConfirm: true
            }, function () {
                $.ajax({
                    method: 'post',
                    url: '<?= base_url('index.php/feedback/save_feedback') ?>',
                    data: $this.serialize(),
                    success: function (response) {
                            swal({title: "Info", text: "Saved successfully", type: "success", confirmButtonText: "ok"},
                                function (){
                                    document.location.href="<?php echo base_url('index.php/feedback') ?>";
                                }
                            );

                        $('#save-feedback').html('Save');
                        $('#save-feedback').prop('disabled', false);
                    }
                });
            });

            $('#save-feedback').html('Save');
            $('#save-feedback').prop('disabled', false);

            return false;
        });

    });
</script> 
