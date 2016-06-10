>> parent('layout::frontend.php')

>> section('title', '>> uses title {{title}}')
>> section('fcontent')
    >> uses categories
    >> uses mainCategory
    >> uses title
<?php
$url = $this->service('url');
?>

<h1>Category: <?php print($mainCategory->retreive('name')) ?></h1>
<div class="row" data-equalizer>
    <?php
    $mainCategoryId = $mainCategory->retreive('id');

    $categories = (array)$categories;
    if (count($categories) > 0):
        foreach ($categories as $category):
            $id = $category->id;
            
            $categoryUrl = $url->route('CategoryProducts', [
                'category_id' => $mainCategoryId,
                'sub_category_id' => $id
            ]);
    ?>
        >> partial('partials::category.php')
    <?php
        endforeach;
    else:
        print($this->service('htmlMessages')->warning('Geen subcategorieën', 'Geen subcategorieën gevonden voor hoofdcategorie: '. $mainCategory->retreive('id') .'!'));
    endif;
    ?>
</div>
<< section('fcontent')