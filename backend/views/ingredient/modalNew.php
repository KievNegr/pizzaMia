<?php
	use yii\bootstrap4\ActiveForm;
	use yii\helpers\Html;
	use yii\helpers\Url;
?>

<div class="modal fade bd-example-modal-xl" id="newIngredient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<?php echo Html::Tag('h5', 'Новый ингредиент', ['class' => 'modal-title', 'id' => 'exampleModalLabel']); ?>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="gotImages newImages" style="justify-content: center;"></div>
                <div class="spinner-border text-info" role="status"></div>
				<?php
                    $imgForm = ActiveForm::begin(['options' => ['class' => 'sendNewImage']]);

	                       echo $imgForm->field($ingredientImage, 'image', ['options' => ['class' => 'custom-file'], 'template' => '{input}{label}{error}'])->fileInput(['class' => 'custom-file-input addNewImage'])->label('Изображение ингредиента', ['class' => 'custom-file-label', 'data-browse' => 'png, jpg']);

	                    ActiveForm::end();
                ?>				
				<?php $form = ActiveForm::begin(['options' => ['id' => 'new-ingredient'], 'enableAjaxValidation' => true, 'validationUrl' => Url::to(['ingredient/validationnew'])]); ?>
				<?php 
					echo $form->field($newModel, 'nameIngredient')->textInput();
					echo $form->field($newModel, 'ingredientImage')->hiddenInput(['class' => 'listImage'])->label(false);
				?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<?php echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'add-ingredient-button']) ?>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>