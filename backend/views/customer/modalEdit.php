<?php
	use yii\bootstrap4\ActiveForm;
	use yii\helpers\Html;
	use yii\helpers\Url;
?>

<div class="modal fade editCustomer" id="editCustomer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			
			<div class="modal-header">
				<?php echo Html::Tag('h5', 'Редактирование клиента' , ['class' => 'modal-title', 'id' => 'exampleModalLabel']); ?>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p class="username"></p>	
                <?php $form = ActiveForm::begin(['options' => ['id' => 'edit-customer'], 'enableAjaxValidation' => true, 'validationUrl' => Url::to(['customer/validate'])]); ?>
				<?php 
					echo $form->field($editModel, 'userId')->hiddenInput(['class' => 'userId'])->label(false);
					echo $form->field($editModel, 'email')->textInput(['class' => 'form-control email']);
					echo $form->field($editModel, 'phone')->textInput(['class' => 'form-control phone phone_mask']);
					echo $form->field($editModel, 'anotherPhone')->textInput(['class' => 'form-control another_phone phone_mask']);
					echo $form->field($editModel, 'userLevel')->hiddenInput(['class' => 'userLevel'])->label(false);
					echo $form->field($editModel, 'noCheckPhone')->hiddenInput(['class' => 'phone'])->label(false);
					echo $form->field($editModel, 'noCheckAnotherPhone')->hiddenInput(['class' => 'another_phone'])->label(false);
					echo $form->field($editModel, 'noCheckEmail')->hiddenInput(['class' => 'email'])->label(false);
				?>
				<div class="userLevelList"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<?php echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>