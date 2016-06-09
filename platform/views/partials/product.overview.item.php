<div class="large-4 small-6 columns" id="<?php print($id) ?>" data-equalizer-watch>    
    <div style="min-height: 10em; width: 10em; background: url('<?php print(product_images_dir . '/'. $id . '/' . $product->small_image_link); ?>') center no-repeat;"></div>

    <div class="panel">
        <a href="<?php print($categoryUrl) ?>"><h5><?php print($product->name); ?></h5></a>
        <button type="button" class="tiny add-product-to-cart" data-id="<?php print($product->id); ?>">Voeg toe aan winkelwagen!</button>
    </div>
</div>