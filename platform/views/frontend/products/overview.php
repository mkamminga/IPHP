>> parent('layout::frontend.php')

>> section('title', '>> uses title {{title}}')
>> section('fcontent')
    >> uses category
    >> uses title
    >> uses products
<?php
$url = $this->service('url');
?>

<h1>Products from: <?php print($category->retreive('name')) ?></h1>

<div class="row" data-equalizer>
    <?php
    $subCategoryId = $category->retreive('id');
    $mainCategoryId = $category->retreive('Parent_id');
    $products = (array)$products;
    if (count($products) > 0):
        foreach ($products as $productModel):
            $product = $productModel->contents();
            $id = $product->id;
            
            $productUrl = $url->route('ProductItem', [
                'category_id' => $mainCategoryId,
                'sub_category_id' => $subCategoryId,
                'product_id' => $id
            ]);
    ?>
        >> partial('partials::product.overview.item.php')
    <?php
        endforeach;
    else:
      print($this->service('htmlMessages')->warning('Geen producten', 'Geen producten gevonden voor de categorie: '. $category->retreive('name') .'!'));
    endif;
    ?>
</div>
<< section('fcontent')