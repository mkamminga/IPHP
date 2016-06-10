<div class="large-4 small-3 columns">
    <div class="callout" data-equalizer-watch>
        <h5><?php print($category->name); ?></h5>
        <a href="<?php print($categoryUrl) ?>">
            <img class="thumbnail" src="<?php print(categories_images_dir . '/'. $category->id . '/' . $category->thumb); ?>" alt="<?php print($category->name); ?>">
        </a>
    </div>
</div>