>> parent('cms::layout::backend.php')
>> section('scripts')
	>> parent
	<script>
	$(document).ready(function () {
		$("#main-category").change(function () {
			var selectedValue = $(this).val();
			
			if (selectedValue != '') {
				var url = "<?php print($url->route("ProductsSubCategories", ["id" => -1])); ?>";
				url = url.replace(/-1/g, selectedValue);
				var select = $("#sub-category");
				$.get(url, {}, function (data) {
					if (data) {
						select.html("");
						//Add all options (subcategories) to the select
						$.each(data, function (i, row) {
							select.append('<option value="'+ row["id"] +'">'+ row["name"] +'</option>'+ "\n");
						});
						
					}
				}, 'json');
			}
		});
	});	
	</script>
<< section('scripts')