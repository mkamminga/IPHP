>> parent('layout::frontend.php')

>> section('title', '>> uses title {{title}}')
>> section('fcontent')
    >> uses product
    >> uses title
    <?php
    $url = $this->service('url');
    $id = $product->id;
    ?>
    <div class="row">
        <h1>Product: <?php print($product->name) ?></h1>
        <div class="large-12 columns">
            <div class="row">
                <div class="large-8 columns">
                    <div class="row" >
                        
                        <div style="min-height: 20em; width: 30em; background: url('<?php print(product_images_dir . '/'. $id . '/' . $product->small_image_link); ?>') center no-repeat;"></div>
                            <h3><?php print($product->name); ?></h3>
                            <p><?php print($product->detail); ?></p>
                            <hr />
                            <p>
                                <strong>&euro; <?php print(number_format($product->price, 2, ',', '.')); ?></strong> (incl. <?php print($product->vat->rate); ?>% btw)
                            </p>
                            <button type="button" class="small add-product-to-cart" data-id="<?php print($product->id); ?>">Voeg toe aan winkelmand!</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<< section('fcontent')