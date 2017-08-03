<div class="shopping-cart" >

    <table width="100%" id="table" class="table" border="0">

        <?php if ($cart = $this->cart->contents()): ?>

        <tr id="main_heading">
            <th>#</th>
            <th>Product Name</th>
            <th>Plate No.</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Total Amount</th>
        </tr>

        <?php
        // Create form and send all values in "update_cart" function.
        echo form_open('orders/update_cart');
        $grand_total = 0;
        $i = 1;

        foreach ($cart as $item):

            echo form_hidden('cart[' . $item['id'] . '][id]', $item['id']);
            echo form_hidden('cart[' . $item['id'] . '][rowid]', $item['rowid']);
            echo form_hidden('cart[' . $item['id'] . '][name]', $item['name']);
            echo form_hidden('cart[' . $item['id'] . '][price]', $item['price']);
            echo form_hidden('cart[' . $item['id'] . '][qty]', $item['qty']);
            echo form_hidden('cart[' . $item['id'] . '][options]', $item['options']);

        ?>

        <tr>

            <td><?php echo $i++; ?></td>

            <td><?php echo $item['name']; ?></td>

            <td><?php echo $item['options']['plate_no']; ?></td>

            <td>Ksh. <?php echo number_format($item['price'], 2); ?></td>

            <td><?php echo $item['qty']." "; if($item['name'] == "Subscription"){echo "Months"; }else{ echo $item['name']; } ?></td>

            <?php $grand_total = $grand_total + $item['subtotal']; ?>

            <td>Ksh. <?php echo number_format($item['subtotal'], 2) ?></td>

            <?php endforeach; ?>

        </tr>

        <tr>
            <td><b>Total: </b>Kshs. <?php echo number_format($grand_total, 2); ?></td>

            <td colspan="6" align="right">

            <input class ='btn btn-sm btn-success' type="submit" value="Update Cart">
            <?php echo form_close(); ?>

            </td>

        </tr>

        <?php endif; ?>

    </table>

</div>
