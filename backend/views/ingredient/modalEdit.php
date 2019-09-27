<?php
	use yii\bootstrap4\ActiveForm;
	use yii\helpers\Html;
	use yii\helpers\Url;
?>

<div class="modal fade bd-example-modal-xl" id="editIngredient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			
			<div class="modal-header">
				<?php echo Html::Tag('h5', 'Редактирование ингредиента', ['class' => 'modal-title', 'id' => 'exampleModalLabel']); ?>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">		
				<div class="gotImages editImages" style="justify-content: center;"></div>
                <div class="spinner-border text-info" role="status"></div>
				<?php
                    $imgForm = ActiveForm::begin(['options' => ['class' => 'sendEditImage']]);

                       echo $imgForm->field($ingredientImage, 'image', ['options' => ['class' => 'custom-file'], 'template' => '{input}{label}{error}'])->fileInput(['class' => 'custom-file-input addEditImage'])->label('Выбрать изображение', ['class' => 'custom-file-label', 'data-browse' => 'png, jpg']);

                    ActiveForm::end();
                ?>
                <?php $form = ActiveForm::begin(['options' => ['id' => 'edit-ingredient'], 'enableAjaxValidation' => true, 'validationUrl' => Url::to(['ingredient/validationedit'])]); ?>
				<?php 
					echo $form->field($editModel, 'ingredientEditId')->hiddenInput(['id' => 'editIngredientId'])->label(false);
					echo $form->field($editModel, 'nameEditIngredient')->textInput(['id' => 'editIngredientName']);
					echo $form->field($editModel, 'nameNoCheckEditIngredient')->hiddenInput(['id' => 'nameNoCheckEditIngredient'])->label(false);
					echo $form->field($editModel, 'ingredientEditImage')->hiddenInput(['class' => 'listImage'])->label(false);
				?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<?php echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'edit-ingredient-button']) ?>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>