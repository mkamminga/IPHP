>> parent('layout::frontend.php')

>> section('title', 'Categories')
>> section('fcontent')
    >> uses categories
    <?php
    $url = $this->service('url');
    ?>
    <div class="row">
        <h1>Categories</h1>
        <div class="large-12 columns">
            <div class="row">
                <div class="large-8 columns">
                    <div class="row" data-equalizer>

                        <?php
                        foreach ($categories as $category):
                            $id = $category->id;
                            $categoryUrl = $url->route('SubcategoriesOverview', [
                                'category_id' => $id
                            ]);
                        ?>
                            <div class="large-4 small-6 columns" id="<?php print($id) ?>" data-equalizer-watch>
                                <a href="<?php print($categoryUrl) ?>">
                                    <div style="min-height: 10em; width: 10em; background: url('<?php print(categories_images_dir . '/'. $category->id . '/' . $category->thumb); ?>') center no-repeat;"></div>

                                    <div class="panel">
                                        <h5><?php print($category->name); ?></h5>
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