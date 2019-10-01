<?php
	use yii\bootstrap4\ActiveForm;
	use yii\helpers\Html;
	use yii\helpers\Url;
?>
<div class="modal fade bd-example-modal-xl" id="editCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			
			<div class="modal-header">
				<?php echo Html::Tag('h5', 'Редактирование категории', ['class' => 'modal-title', 'id' => 'exampleModalLabel']); ?>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="gotImages editImages" style="justify-content: center;"></div>
                <div class="spinner-border text-info" role="status"></div>
                <?php
                    $imgForm = ActiveForm::begin(['options' => ['class' => 'sendEditImage']]);

                       echo $imgForm->field($categoryIcon, 'image', ['options' => ['class' => 'custom-file'], 'template' => '{input}{label}{error}'])->fileInput(['class' => 'custom-file-input addEditImage'])->label('Выбрать изображение', ['class' => 'custom-file-label', 'data-browse' => 'png, jpg']);

                    ActiveForm::end();
                ?>				
				<?php $form = ActiveForm::begin(['options' => ['id' => 'edit-category'], 'enableAjaxValidation' => true, 'validationUrl' => Url::to(['category/validateedit'])]); ?>
				<?php 
					echo $form->field($editModel, 'editId')->hiddenInput(['id' => 'setEditId'])->label(false);
					echo $form->field($editModel, 'editIcon')->hiddenInput(['class' => 'listImage'])->label(false);

					echo $form->field($editModel, 'editName')->textInput(
					[
						'class' => 'makeseo form-control', 
						'id' => 'edit-name'
					]); 
					
					echo $form->field($editModel, 'editNoCheckName')->hiddenInput(['id' => 'setEditNoCheckName'])->label(false);
					
					echo $form->field($editModel, 'editSeo')->textInput(
					[
						'class' => 'setseo form-control',
						'id' => 'edit-sef'
					]); 
					
					echo $form->field($editModel, 'editNoCheckSef')->hiddenInput(['id' => 'setEditNoCheckSef'])->label(false);
					
					echo $form->field($editModel, 'editText')->textarea(['id' => 'setEditText']);

					$listCategory[0] = 'Это родительская категория';

					foreach($parent as $category)
					{
						$listCategory[$category['id']] = $category['title'];
					}
					
					echo $form->field($editModel, 'editParent')->dropDownList($listCategory, ['id' => 'setEditParent']);

					echo $form->field($editModel, 'categoryMain', ['options' => ['class' => 'custom-switch']])->checkbox(['class' => 'categoryMain custom-control-input']);
				?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<?php echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'add-category-button']) ?>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>