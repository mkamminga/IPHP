<div class="large-4 small-3 columns">
    <div class="callout" data-equalizer-watch>
        <h5><?php print($product->name); ?></h5>
        <a href="<?php print($productUrl) ?>">
            <img class="thumbnail" src="<?php print(product_images_dir . '/'. $id . '/' . $product->small_image_link); ?>" alt="<?php print($product->name); ?>">
        </a>
        <hr />
        <p>
            <strong>&euro; <?php print(number_format($product->price, 2, ',', '.')); ?></strong> (incl. <?php print($product->vat->rate); ?>% btw)
        </p>
        <button type="button" class="button small add-product-to-cart" data-id="<?php print($product->id); ?>">Voeg toe aan winkelwagen!</button>
    </div>
</div>