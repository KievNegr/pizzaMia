<?php
	use yii\bootstrap4\ActiveForm;
	use yii\helpers\Html;
	use yii\helpers\Url;
?>

<div class="modal fade bd-example-modal-xl" id="editUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			
			<div class="modal-header">
				<?php echo Html::Tag('h5', 'Редактирование пользователя', ['class' => 'modal-title', 'id' => 'exampleModalLabel']); ?>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">		
				<div class="gotImages editImages" style="justify-content: center;"></div>
                <div class="spinner-border text-info" role="status"></div>
				<?php
                    $imgForm = ActiveForm::begin(['options' => ['class' => 'sendEditImage']]);

                       echo $imgForm->field($userAvatar, 'image', ['options' => ['class' => 'custom-file'], 'template' => '{input}{label}{error}'])->fileInput(['class' => 'custom-file-input addEditImage'])->label('Выбрать изображение', ['class' => 'custom-file-label', 'data-browse' => 'png, jpg']);

                    ActiveForm::end();
                ?>
                <?php $form = ActiveForm::begin(['options' => ['id' => 'formEditUser'], 'enableAjaxValidation' => true, 'validationUrl' => Url::to(['user/validate'])]); ?>
				<?php
					echo $form->field($editModel, 'avatar')->hiddenInput(['class' => 'listImage'])->label(false);

					echo $form->field($editModel, 'userName')->textInput(['class' => 'form-control userName']);

					echo $form->field($editModel, 'email')->textInput(['class' => 'form-control email']);
					echo $form->field($editModel, 'noCheckEmail')->hiddenInput(['class' => 'noCheckEmail'])->label(false);

					echo $form->field($editModel, 'phone')->textInput(['class' => 'form-control phone phone_mask']);
					echo $form->field($editModel, 'noCheckPhone')->hiddenInput(['class' => 'noCheckPhone'])->label(false);

					echo $form->field($editModel, 'anotherPhone')->textInput(['class' => 'form-control anotherPhone phone_mask']);
					echo $form->field($editModel, 'noCheckAnotherPhone')->hiddenInput(['class' => 'noCheckAnotherPhone'])->label(false);

					echo $form->field($editModel, 'password')->passwordInput();
					
					echo $form->field($editModel, 'userLevel')->hiddenInput(['class' => 'userLevel'])->label(false);

					echo $form->field($editModel, 'userId')->hiddenInput(['class' => 'userId'])->label(false);
				?>
				<div class="userLevelList"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<?php echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'editUserButton']) ?>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>