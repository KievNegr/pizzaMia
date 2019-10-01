<?php
	use yii\helpers\Html;
	use yii\bootstrap4\ActiveForm;
?>
<div class="modal fade" id="restorePage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<?php $form = ActiveForm::begin(['options' => ['id' => 'restoreThisCategory']]);?>
				<?php echo $form->field($restoreModel, 'idPage')->hiddenInput(['id' => 'idRestorePage'])->label(false);?>
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Восстановление страницы</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>Восстановить страницу <span id="titleRestorePage"></span>?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
					<?php echo Html::submitButton('Восстановить', ['class' => 'btn btn-success']);?>
				</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>