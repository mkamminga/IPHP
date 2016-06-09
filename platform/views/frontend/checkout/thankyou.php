>> parent('layout::frontend.php')

>> section('title', 'Bestelgegevens')
>> section('fcontent')
    >> uses shoppingcart
    <div class="row">
        <div class="large-12 columns">
            <h1>Dank u voor uw bestelling!</h1>
            <p>Hieronder ziet een overzicht van de door u bestelde product(en).</p>

            <table class="t-shoppingcart">
                <thead>
                    <tr>
                        <th>Afbeelding</th>
                        <th>Product</th>
                        <th>Aantal</th>
                        <th>Prijs</th>
                        <th>Subtotaal</th>
                    </tr>
                </thead>

                <tbody class="tb-cart">
                <?php
                $items = $shoppingcart->getItems();
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
                            <?php print($item->getQuantity()); ?>
                        </td>

                        <td>
                            &euro; <?php print(number_format($product->price, 2, ',', '.')); ?>
                        </td>

                        <td>  &euro; <?php print(number_format($item->total(), 2, ',', '.')); ?></td>
                    </tr>
                <?php
                endforeach;
                ?>
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right"><strong>Totalprijs</strong></td>
                        <td colspan="1">&euro; <?php print(number_format($shoppingcart->getTotal(), 2, ',', '.')); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
<< section('fcontent')