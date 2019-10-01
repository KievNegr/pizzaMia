<?php
	use yii\helpers\Html;
	use yii\bootstrap4\ActiveForm;
?>

<div class="modal fade" id="restoreUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<?php $form = ActiveForm::begin(['options' => ['id' => 'restoreAdmin']]);?>
				<?php echo $form->field($restoreModel, 'idUser')->hiddenInput(['id' => 'idRestoreUser'])->label(false);?>
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Восстановление пользователя</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>Восстановить пользователя <span id="nameRestoreUser"></span>?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
					<?php echo Html::submitButton('Восстановить', ['class' => 'btn btn-success']);?>
				</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>