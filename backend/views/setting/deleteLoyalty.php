<?php
	use yii\helpers\Html;
	use yii\bootstrap4\ActiveForm;
?>
<div class="modal fade deleteLoyalty" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<?php 
				$form = ActiveForm::begin(['options' => ['id' => 'removeLoyalty']]);
					echo $form->field($deleteModel, 'loyaltyId')->hiddenInput(['class' => 'deleteIdLoyalty'])->label(false);
			?>
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Удаление скидки</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>Удалить скидку <span id="titleDeleteLoyalty"></span>? После утвердительного ответа, скидка перестанет отображатся на основном сайте, а в панеле управления она будет обозначен красным цветом и ее можно будет восстановить в любой момент.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
					<?php echo Html::submitButton('Удалить', ['class' => 'btn btn-danger']);?>
				</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>