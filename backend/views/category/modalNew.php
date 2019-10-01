<?php
	use yii\bootstrap4\ActiveForm;
	use yii\helpers\Html;
	use yii\helpers\Url;
?>

<div class="modal fade bd-example-modal-xl" id="newCategory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<?php echo Html::Tag('h5', 'Новая категория', ['class' => 'modal-title', 'id' => 'exampleModalLabel']); ?>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="gotImages newImages" style="justify-content: center;"></div>
                <div class="spinner-border text-info" role="status"></div>
				<?php
                    $imgForm = ActiveForm::begin(['options' => ['class' => 'sendNewImage']]);

                       echo $imgForm->field($categoryIcon, 'image', ['options' => ['class' => 'custom-file'], 'template' => '{input}{label}{error}'])->fileInput(['class' => 'custom-file-input addNewImage'])->label('Выбрать изображение', ['class' => 'custom-file-label', 'data-browse' => 'png, jpg']);

                    ActiveForm::end();
                ?>				
				<?php $form = ActiveForm::begin(['options' => ['id' => 'new-category'], 'enableAjaxValidation' => true, 'validationUrl' => Url::to(['category/validatenew'])]); ?>
				<?php 
					echo $form->field($model, 'categoryIcon')->hiddenInput(['class' => 'listImage'])->label(false);
					echo $form->field($model, 'categoryName')->textInput(['class' => 'makeseo form-control']);
					echo $form->field($model, 'categorySeo')->textInput(['class' => 'setseo form-control']);
					echo $form->field($model, 'categoryText')->textarea();

					$listCategory[0] = 'Это родительская категория';

					foreach($parent as $category)
					{
						$listCategory[$category['id']] = $category['title'];
					}
				
					echo $form->field($model, 'categoryParent')->dropDownList($listCategory);

					echo $form->field($model, 'categoryMain', ['options' => ['class' => 'custom-switch']])->checkbox();
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