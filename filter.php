<?php

$_GET['ids'] = "";

    foreach ($array1 as $key => $value){
        $categories_dup[] = $array1[$key]['g:product_type']['@cdata'];
    }
	$categories = array_unique($categories_dup, SORT_REGULAR);
?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Filter | Vezoa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.8/themes/default/style.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.8/jstree.min.js"></script>
    <script type="text/javascript">
        $(function () {

            var jsondata = [
                <?php
                    foreach ($categories as $categoria){
                    echo"
                    { 'id': '$categoria', 'parent': '#', 'text': '$categoria', state : { selected : true } },";
                }
               ?>
               <?php
                    foreach ($array1 as $table){
                    $title = $table['title']['@cdata'];
                    $product_type = $table['g:product_type']['@cdata'];
                    $id = $table['g:id'];

                    echo"
                    { 'id': '$id', 'parent': '$product_type', 'text': '$title - $id' },";
                }
               ?>
            ];

            createJSTree(jsondata);
			
			$('.btnGetAllTopLevelCheckedItems').click(function(){
				var checked_ids = []; 
				var selectedNodes = $('#SimpleJSTree').jstree("get_top_checked", true);
				$.each(selectedNodes, function() {
					checked_ids.push(this.id);
				});
				// You can assign checked_ids to a hidden field of a form before submitting to the server
				$('#idshow').text("Waiting");
			});
			
			$('.btnGetAllBottomLevelCheckedItems').click(function(){
				var checked_ids = []; 
				var selectedNodes = $('#SimpleJSTree').jstree("get_bottom_checked", true);
				$.each(selectedNodes, function() {
					checked_ids.push(this.id);
				});
				// You can assign checked_ids to a hidden field of a form before submitting to the server
                //$('#idshow').text("");
                
                document.sampleForm.total.value = checked_ids;
                document.forms["sampleForm"].submit()
			});
        });

        function createJSTree(jsondata) {
            $('#SimpleJSTree').jstree({
                "core": {
                    "check_callback": true,
                    'data': jsondata,
                    expand_selected_onload : false                    
                },
				"checkbox" : {
					"keep_selected_style" : false
				},
                "plugins": ["checkbox"]
            });
        }
    </script>

</head>
<body>
    <form id="sampleForm" name="sampleForm" method="post" action="generator.php">
        <input type="hidden" name="total" id="total" value="">
        <input class="btnGetAllBottomLevelCheckedItems" value="Generate Document" type="button" />
    </form>
    <div id="SimpleJSTree"></div>
</body> 
</html>