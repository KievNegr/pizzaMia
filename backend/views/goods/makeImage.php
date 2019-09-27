<?php
	$this->registerJsFile('@web/js/itemImages.js', ['depends' => 'yii\web\JqueryAsset']);
	$this->registerJsFile('@web/js/crop.js', ['depends' => 'yii\web\JqueryAsset']);
	$this->registerJsFile('@web/js/jquery-ui.js', ['depends' => 'yii\web\JqueryAsset']);
	$this->registerCssFile('@web/css/jquery-ui.css');

	$this->registerJs('
		$( function() {
	        $( "#selectedArea" ).draggable({ containment: "parent" });
	    });
    ');
?>

<div id="imageContainer">
	<i class="fas fa-times closeImageContainer"></i>
	<div id="selectedArea">
		<button type="button" class="btn btn-success btn-sm setImage">Ok</button>
	</div>
</div>

<input type="hidden" id="rate" />
<input type="hidden" id="typeOfImage" value="fixed" minWidth="980" minHeight="450" />
<div id="fadeContainer"></div>