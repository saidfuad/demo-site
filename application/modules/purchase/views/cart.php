<div class="shopping-cart" >

    <table width="100%" id="table" class="table" border="0">

        <?php if ($cart = $this->cart->contents()): ?>

        <tr id="main_heading">
            <th>#</th>
            <th>Product Name</th>
            <th>Plate No.</th>
            <th>Price</th>
            <th>Duration</th>
            <th>Total Amount</th>
            <th>Remove</th>
        </tr>

        <?php
        // Create form and send all values in "update_cart" function.
        echo form_open('purchase/update_cart');
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

            <td><?php echo form_input('cart[' . $item['id'] . '][qty]', $item['qty'], 'maxlength="3" size="1" style="text-align: right"')." "; if($item['name'] == "Subscription"){echo "Months"; }else{ echo $item['name']; } ?></td>

            <?php $grand_total = $grand_total + $item['subtotal']; ?>

            <td>Ksh. <?php echo number_format($item['subtotal'], 2) ?></td>

            <td><?php
            // cancle image.
            $path = "<img src='http://localhost/codeigniter_cart/images/cart_cross.jpg' width='25px' height='20px'>";
            echo anchor('purchase/remove/' . $item['rowid'], $path); ?></td>

            <?php endforeach; ?>

        </tr>

        <tr>
            <td><b>Order Total: Kshs. <?php echo number_format($grand_total, 2); ?></b></td>

            <?php // "clear cart" button call javascript confirmation message ?>
            <td colspan="6" align="right">

            <?php //submit button. ?>
            <input class ='btn btn-sm btn-success' type="submit" value="Update Cart">
            <?php echo form_close(); ?>

            <!-- "Place order button" on click send "billing" controller -->
            <input class ='btn btn-sm btn-success' type="button" value="Place Order" onclick="window.location = 'purchase/checkout'"></td>

        </tr>

        <?php endif; ?>

    </table>

</div>
