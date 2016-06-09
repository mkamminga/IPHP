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
    <div class="row">
        <h1>Producten</h1>
        <div class="large-12 columns">
            <div class="row">
                <form action="" method="get">
                    <div class='large-6 rows'>
                        <label>Zoek op: 
                            <?php print($form->text('q', $input->escaped('q'))); ?>
                        </label>
                    </div>

                    <div class='large-6 rows'>
                        <button type="submit" role="button" aria-label="submit form" class="button">Zoek</button>
                    </div>
                </form>

                <hr />

                <div class="large-8 columns">
                    <div class="row" data-equalizer>
                        <?php
                        foreach ($products as $productModel):
                            $product = $productModel->contents();
                            $id = $product->id;
                            
                            $categoryUrl = $url->route('ProductItem', [
                                'category_id' => $product->category->Parent_id,
                                'sub_category_id' => $product->category->id,
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