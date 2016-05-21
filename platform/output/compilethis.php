<!doctype html>
<html>
<head>
	<title><?php $title=$__view->getInjectedVar("title"); ?><?php print($title); ?></title>	
	<meta charset="UTF-8">
</head>
<body>
<div id="main">
		<?php $breadcrumbs=$__view->getInjectedVar("breadcrumbs"); ?>
			<div class="breadcrumbs">
		<?php
		if (isset($breadcrumbs) && is_array($breadcrumbs)) {
			foreach ($breadcrumbs as $breadcrumb) {
				print(' <a href="'. $breadcrumb->getUrl() .'">'. $breadcrumb->getTitle() . '</a> ');
			}
		}
		?>
		</div>
			<?php $data=$__view->getInjectedVar("data"); ?>
		
		<h1>Master lqyout</h1>
	<div class="content">
	<p>Content</p>
	<?php
	var_dump($data);
	?>
</div>
		
		<script type="text/javascript">var a = 1;</script>
		<script type="text/javascript">var b = 2;</script>
	</div>

</body>
</html>