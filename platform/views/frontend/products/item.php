>> parent('layout::frontend.php')

>> section('title', '>> uses title {{title}}')
>> section('fcontent')
    >> uses product
    >> uses title
<?php
$url = $this->service('url');
$id = $product->id;
?>
<h1>Product: <?php print($product->name) ?></h1>

<img class="thumbnail" src="<?php print(product_images_dir . '/'. $id . '/' . $product->main_image_link); ?>">
<h3><?php print($product->name); ?></h3>
<p><?php print($product->detail); ?></p>
<hr />
<p>
    <strong>&euro; <?php print(number_format($product->price, 2, ',', '.')); ?></strong> (incl. <?php print($product->vat->rate); ?>% btw)
</p>
<button type="button" class="button add-product-to-cart" data-id="<?php print($product->id); ?>">Voeg toe aan winkelmand!</button>
<< section('fcontent')