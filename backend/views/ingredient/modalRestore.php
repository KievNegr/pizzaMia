<?php
	use yii\helpers\Html;
	use yii\bootstrap4\ActiveForm;
?>
<div class="modal fade" id="restoreIngredient" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<?php $form = ActiveForm::begin(['options' => ['id' => 'restoreThisIngredient']]);?>
				<?php echo $form->field($restoreModel, 'idIngredient')->hiddenInput(['id' => 'idRestoreIngredient'])->label(false);?>
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Восстановление ингредиента</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>Восстановить ингредиент <span id="titleRestoreIngredient"></span>?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
					<?php echo Html::submitButton('Восстановить', ['class' => 'btn btn-success']);?>
				</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>