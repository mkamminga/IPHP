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
                            >> partial('partials::product.overview.item.php')
                        <?php
                        endforeach
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<< section('fcontent')