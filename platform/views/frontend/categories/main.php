>> parent('layout::frontend.php')

>> section('title', 'Categories')
>> section('fcontent')
    >> uses categories
<?php
$url = $this->service('url');
?>
<h1>Categories</h1>

<div class="row" data-equalizer>
    <?php
    $categories = (array)$categories;
    if (count($categories) > 0):
        foreach ($categories as $category):
            $id = $category->id;
            $categoryUrl = $url->route('SubcategoriesOverview', [
                'category_id' => $id
            ]);
    ?>
    >> partial('partials::category.php')
    <?php
        endforeach;
    else:
        print($this->service('htmlMessages')->warning('Geen categorieën', 'Geen categorieën gevonden!'));
    endif;
    ?>
</div>
<< section('fcontent')