>> parent('layout::frontend.php')

>> section('title', '>> uses title {{title}}')
>> section('fcontent')
    >> uses category
    >> uses title
    >> uses products
    <?php
    $url = $this->service('url');
    ?>
    <div class="row">
        <h1>Products from: <?php print($category->retreive('name')) ?></h1>
        <div class="large-12 columns">
            <div class="row">
                <div class="large-8 columns">
                    <div class="row" data-equalizer>
                        <?php
                        $subCategoryId = $category->retreive('id');
                        $mainCategoryId = $category->retreive('Parent_id');
                        foreach ($products as $product):
                            $id = $product->id;
                            
                            $categoryUrl = $url->route('ProductItem', [
                                'category_id' => $mainCategoryId,
                                'sub_category_id' => $subCategoryId,
                                'product_id' => $id
                            ]);
                        ?>
                            <div class="large-4 small-6 columns" id="<?php print($id) ?>" data-equalizer-watch>
                                <a href="<?php print($categoryUrl) ?>">
                                    <div style="min-height: 10em; width: 10em; background: url('<?php print(product_images_dir . '/'. $id . '/' . $product->small_image_link); ?>') center no-repeat;"></div>

                                    <div class="panel">
                                        <h5><?php print($product->name); ?></h5>
                                    </div>
                                </a>
                            </div>
                        <?php
                        endforeach
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<< section('fcontent')