>> uses shoppingcart
<h2 class="h2-state">Cart</h2>
<?php
$items = $shoppingcart->getItems();
if (count($items) > 0):
?>
<form method="post" action="" onsubmit="return false">
    <table class="t-shoppingcart">
        <thead>
        <tr>
            <th>Image</th>
            <th>Product</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Subtotal</th>
            <th>Acties</th>
        </tr>
        </thead>

        <tbody class="tb-cart">
        <?php
        
        foreach($items as $item):
            $product = $item->getProduct()->contents();
        ?>
            <tr data-id="<?php print($product->id); ?>" class="cart-row-item">
                <td>
                    <a class="th" href="#"><img style="max-height: 80px; max-width: 80px;" src="<?php print(product_images_dir . '/'. $product->id . '/' . $product->small_image_link); ?>"></a>
                </td>

                <td>
                    <?php print($product->name); ?>
                </td>

                <td class="td-quantity">
                    <input type="text" name="quantityof_<?php print($product->id); ?>" data-id="<?php print($product->id); ?>" value="<?php print($item->getQuantity()); ?>" class="cart-item-quantity small" style="width: 3em;" />
                </td>

                <td>
                    &euro; <?php print(number_format($product->price, 2, ',', '.')); ?>
                </td>

                <td>  &euro; <?php print(number_format($item->total(), 2, ',', '.')); ?></td>

                <td>
                    <a href="#"><i class="fi-x remove-item" data-id="<?php print($product->id) ?>"></i></a>
                </td>
                
            </tr>
        <?php
        endforeach;
        ?>
        </tbody>

        <tfoot>
            <tr>
                <td colspan="4" class="text-right"><strong>Totalprice</strong></td>
                <td colspan="2">&euro; <?php print(number_format($shoppingcart->getTotal(), 2, ',', '.')); ?></td>
            </tr>
        </tfoot>
    </table>
</form>
<?php
else:
    print($this->service('htmlMessages')->warning('Geen producten', 'Geen producten toegevoegd aan uw winkelmand!'));
endif;
?>