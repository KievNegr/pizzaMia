<?php
	use yii\bootstrap4\ActiveForm;
	use yii\helpers\Html;
	use yii\helpers\Url;
?>

<div class="modal fade" id="newOption" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<?php echo Html::Tag('h5', 'Новая опция', ['class' => 'modal-title', 'id' => 'exampleModalLabel']); ?>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="gotImages newImages" style="justify-content: center;"></div>
				<div class="spinner-border text-info" role="status"></div>
					<?php
						$imgForm = ActiveForm::begin(['options' => ['class' => 'sendNewImage']]);

	                       echo $imgForm->field($optionImage, 'image', ['options' => ['class' => 'custom-file'], 'template' => '{input}{label}{error}'])->fileInput(['class' => 'custom-file-input addNewImage'])->label('Изображение опции', ['class' => 'custom-file-label', 'data-browse' => 'png, jpg']);

	                    ActiveForm::end();
                	 
						$form = ActiveForm::begin(['options' => ['id' => 'new-option']/*, 'enableAjaxValidation' => true, 'validationUrl' => Url::to(['option/validationnew'])*/]);

						echo $form->field($newModel, 'newTitle')->textInput();
						echo $form->field($newModel, 'newDescription')->textarea(['rows' => 4]);

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

						echo $form->field($newModel, 'newCategory')->dropDownList($listCategory, ['prompt' => 'Выбор категории...']);

						echo $form->field($newModel, 'newImage')->hiddenInput(['class' => 'listImage'])->label(false);
					?>			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<?php 
					echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'add-option-button']);
					ActiveForm::end();
				?>
			</div>
		</div>
	</div>
</div>