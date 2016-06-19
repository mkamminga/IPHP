>> parent('layout::frontend.php')

>> section('title', 'Zoek resultaten')
>> section('fcontent')
    >> uses products
<?php
$products = (array)$products;
$url = $this->service('url');
$form = $this->service('form');
$input = $this->service('input');
?>
<h1>Producten</h1>
<div class="row">
    <form action="" method="get">
        <div class='large-6 rows'>
            <label>Zoek op: 
                <?php print($form->text('q', $input->escaped('q'), ['required' => 'required'])); ?>
            </label>
        </div>

        <div class='large-6 rows'>
            <button type="submit" role="button" aria-label="submit form" class="button">Zoek</button>
        </div>
    </form>
</div>
<hr />

<div class="row" data-equalizer>
<?php
$products = (array)$products;

if (count($products) > 0):
    foreach ($products as $productModel):
        $product = $productModel->contents();
        $id = $product->id;
        
        $productUrl = $url->route('ProductItem', [
            'category_id' => $product->category->Parent_id,
            'sub_category_id' => $product->category->id,
            'product_id' => $id
        ]);
?>
    >> partial('partials::product.overview.item.php')
<?php
    endforeach;
else:
    print($this->service('htmlMessages')->warning('Geen resultaten gevonden', 'Geen resultaten gevonden voor de zoekterm: '. $input->escaped('q') .'!'));
endif;
?>
</div>
<< section('fcontent')