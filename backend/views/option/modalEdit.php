<?php
	use yii\bootstrap4\ActiveForm;
	use yii\helpers\Html;
	use yii\helpers\Url;
?>

<div class="modal fade" id="editOption" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<?php echo Html::Tag('h5', 'Редактирование опции', ['class' => 'modal-title', 'id' => 'exampleModalLabel']); ?>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="gotImages editImages" style="justify-content: center;"></div>
				<div class="spinner-border text-info" role="status"></div>
					<?php
						$imgForm = ActiveForm::begin(['options' => ['class' => 'sendEditImage']]);

	                       echo $imgForm->field($optionImage, 'image', ['options' => ['class' => 'custom-file'], 'template' => '{input}{label}{error}'])->fileInput(['class' => 'custom-file-input addEditImage'])->label('Изображение опции', ['class' => 'custom-file-label', 'data-browse' => 'png, jpg']);

	                    ActiveForm::end();
                	 
						$form = ActiveForm::begin(['options' => ['id' => 'edit-option']/*, 'enableAjaxValidation' => true, 'validationUrl' => Url::to(['option/validationnew'])*/]);

						echo $form->field($editModel, 'editOptionId')->hiddenInput(['id' => 'setEditUserId'])->label(false);
						echo $form->field($editModel, 'editTitle')->textInput(['id' => 'setEditOptionTitle']);
						echo $form->field($editModel, 'editDescription')->textarea(['rows' => 4, 'id' => 'setEditOptionDescription']);

						$listCategory = Array();
                        foreach($options as $category)
                        {
                            if($category['parent'] == 0)
                            {
                                foreach($options as $child)
                                {
                                    if($child['parent'] == $category['id'])
                                    {
                                        $listCategory[$category['title']][$child['id']] = $child['title'];
                                    }
                                }
                            }
                        }

						echo $form->field($editModel, 'editCategory')->dropDownList($listCategory, ['prompt' => 'Выбор категории...', 'id' => 'setEditOptionCategory']);

						echo $form->field($editModel, 'editImage')->hiddenInput(['class' => 'listImage'])->label(false);
					?>			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<?php 
					echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'edit-option-button']);
					ActiveForm::end();
				?>
			</div>
		</div>
	</div>
</div>