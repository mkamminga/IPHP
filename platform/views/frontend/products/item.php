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
                        <div class="large-4 small-6 columns">
                            <div style="min-height: 10em; width: 10em; background: url('<?php print(product_images_dir . '/'. $id . '/' . $product->small_image_link); ?>') center no-repeat;"></div>

                            <div class="panel">
                                <h5><?php print($product->name); ?></h5>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
<< section('fcontent')