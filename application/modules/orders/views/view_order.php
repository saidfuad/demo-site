<style>

    .modal-body{
        margin-bottom: 48px;
        margin-top: -8px;
    }

    .quantity{
        font-weight: 600;
        width: 56px;
        height: 56px;
        text-align: center;
        padding: 16px;
        border-radius: 50%;
        color: #fff;
        background: #172230;
    }

    .items{
        text-align: left;
        padding: 16px;
    }

    .price{
        text-align: right;
        padding: 16px;
    }

    .plate{
        text-align: center;
        padding: 16px;
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
<?php foreach ($orders as $key => $value) {

$order=$this->mdl_orders->fetch_order($value->order_id);

    /*echo "<pre>";
    print_r($order);
    exit;*/

?>
<div class="modal fade" id="view_order<?php echo $value->order_id;?>" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Order Details</h4>
                <h6 class="transaction_no">
                    <?php if($order[0]->transaction_no != ""){
                        echo "Transaction No. :".$order[0]->transaction_no;
                    } ?>
                </h6>
            </div>

            <?php

            $total=0;
            $order_status = $order[0]->order_status;

            foreach($order as $key => $val){
                $total+=$val->total_price;
            ?>

            <div class="modal-body">

                <div class="col-xs-2 col-md-2 quantity">
                    <?php echo $val->quantity; ?>
                </div>

                <div class="col-xs-4 col-md-4 items">
                    <?php if($val->quantity > 1){

                        echo $val->product_type."s";

                    }else{

                        echo $val->product_type;

                    } ?>

                </div>

                <div class="col-xs-2 col-md-2 plate">
                    <?php if($val->plate_no == ""){

                        echo "-";

                    }else{

                        echo $val->plate_no;

                    } ?>

                </div>

                <div class="col-xs-4 col-md-4 price">
                    <?php echo "Kshs. ".$val->total_price.".00"; ?>
                </div>

            </div>
            <?php } ?>

            <div class="modal-footer">
                <div class="order-status">
                    <?php if($order_status == "pending"){

                        echo "<status class='pending'><span class='fa fa-hourglass fa-fw'></span> &nbsp; ORDER ".strtoupper($order_status)."</status>";

                    }else{

                        echo "<status class='complete'><span class='fa fa-check fa-fw'></span> &nbsp; ORDER ".strtoupper($order_status)."</status";

                    } ?>
                </div>
                <div class="total-price">
                    Total: &nbsp; <b>Kshs.</b> <b><?php echo $total;?></b><b>.00</b>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php }?>
