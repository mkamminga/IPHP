>> parent('layout::main.layout.php')

>> section('content')
    >> uses userGuard
    <div class="container row">        
        <div class="row black">
            <div class="small-4 large-6 columns">
                <h1 style="color: #ffcc00">Goldenfingers</h1>
            </div>
            <div class="small-2 large-2 columns">
                <span class="text-aligned-right secondary label">Welkom <?php print($userGuard->getUsername()); ?></span>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="large-3 columns">
                <div class="callout">
                    <h3>Menu</h3>
                    <hr />
                    <?php
                    $url = $this->service('url');
                    ?>
                    <ul class="menu vertical">
                        <li><a href="<?php print($url->route('Dashboard')); ?>">Dashboard</a></li>
                        <li><a href="<?php print($url->route('NavigationOverview')); ?>">Navigatie</a></li>
                        <li><a href="<?php print($url->route('OrdersOverview')); ?>">Orders</a></li>
                        <li><a href="<?php print($url->route('ProductsOverview')); ?>">Producten</a></li>
                        <li><a href="<?php print($url->route('CategoriesOverview')); ?>">Categorieën</a></li>
                    </ul>
                </div> 
            </div>
            
            <div class="large-9 columns">    
                <h2> << show('title', '')</h2>
                <div class="content">
                    << show('fcontent', '')
                </div>
            </div>    
        </div>

        <footer class="row">
            <div class="large-12 columns">
                <hr/>
                <div class="panel">
                    <div class="row">
                        <div class="large-2 small-6 columns">
                            <img src="/images/cthulu.jpg">
                        </div>

                        <div class="large-10 small-6 columns">
                            <strong>This Site Is Managed By</strong>

                            Our overlord Cthulu
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="large-6 columns">
                        <p>&copy; Copyright Cthulu productions  </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>    
<< section('content')

>> section('scripts')
<script src="/js/vendor/jquery.js"></script>
<script src="/js/foundation/app.js"></script>
<script>
$(document).foundation();
</script>
<< section('scripts')