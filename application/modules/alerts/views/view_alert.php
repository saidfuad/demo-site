<style>

    .modal-body{
        margin-bottom: 48px;
        margin-top: -8px;
    }

    .modal-body{
        margin-bottom: 8px;
    }

    .modal-footer{
        font-size: 16px;
        letter-spacing: .05em
    }

    .body1{
        text-align: center;
        font-weight: 900;
        font-size: 15px;
        padding: 0;
        margin-bottom: 0;
        line-height: 16px;
    }

    .body2{
        text-align: left;
        font-weight: 500;
        font-size: 13px;
        margin-bottom: 0;
    }

    .order-status{
        float: left;
    }

    .pending{
       color: #FFC107;
    }

    .complete{
       color: #009688;
    }

    .transaction_no{
        position: absolute;
        right: 16px;
        top: 24px;
    }


</style>
<?php foreach ($alerts as $key => $value) {

$alert = $this->mdl_alerts->fetch_alerts($this->session->userdata('hawk_account_id'), null, null, $value->alert_id);
//$this->mdl_alerts->read_alert($this->session->userdata('hawk_account_id'), null, null, $value->alert_id);

    /*echo "<pre>";
    print_r($alert);
    exit;*/

?>
<div class="modal fade" id="view_alert<?php echo $value->alert_id;?>" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title"><b><?=strtoupper($value->name)." ALERT" ?></b></h4>
            </div>

            <div class="modal-body">

                <div class="col-xs-2 col-md-4 body1">
                    <?php echo $value->model; ?>
                </div>

                <div class="col-xs-4 col-md-4 body1">
                    <?php echo $value->plate_no; ?>
                </div>

<!--                <div class="col-xs-2 col-md-4 body1">
                    <?php// echo strtoupper($value->name." alert"); ?>
                </div>-->

            </div>

            <legend></legend>

            <div class="modal-body">

                <div class="col-xs-2 col-md-6 body2">
                    <b>Alert Date</b>
                </div>

                <div class="col-xs-4 col-md-6 body2">
                    <b>Alert Location</b>
                </div>

            </div>

             <div class="modal-body">

                <div class="col-xs-2 col-md-6 body2">
                     <?php echo $value->start_date; ?>
                </div>

                <div class="col-xs-4 col-md-6 body2">
                    <?php echo $value->start_address; ?>
                </div>
            </div>

            <br>

            <?php if($value->status == 0){ ?>

            <div class="modal-body">

                <div class="col-xs-2 col-md-6 body2">
                    <b>Resolve Date</b>
                </div>

                <div class="col-xs-4 col-md-6 body2">
                    <b>Resolve Location</b>
                </div>

            </div>

             <div class="modal-body">

                <div class="col-xs-2 col-md-6 body2">
                     <?php echo $value->stop_date; ?>
                </div>

                <div class="col-xs-4 col-md-6 body2">
                    <?php echo $value->stop_address; ?>
                </div>
            </div>

            <?php } ?>

            <div class="modal-footer">
                <?php if($value->status == 1){ ?>
                <b style="color: #FFA000"><span class="fa fa-exclamation-triangle"></span> &nbsp; Unresolved</b>
                <?php }else{ ?>
                <b style="color: #00796B"><span class="fa fa-check"></span> &nbsp; Resolved</b>
                <?php } ?>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php }?>
