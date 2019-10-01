<?php
	use yii\helpers\Html;
	$this->registerJsFile('@web/js/option.js', ['depends' => 'yii\web\JqueryAsset']); //Управление опциями (удаление, редактирование ...)
	
	if(Yii::$app->user->identity->level == 1)
	{
		echo Html::button('Новая опция', ['class' => 'btn btn-success right clearFields', 'data-toggle' => 'modal', 'data-target' => '#newOption']);
	}
	
	echo Html::tag('h2', 'Список опций');

	if(yii::$app->session->getFlash('success'))
	{
		yii\bootstrap4\Alert::begin(['options' => ['class' => 'alert alert-success']]);
			echo yii::$app->session->getFlash('success');
		yii\bootstrap4\Alert::end();
	}
?>

<?php if($options): ?>
	<?php $countCategory = 1; ?>
	<?php foreach($options as $category): ?>
		<?php if(!empty($category['options'])): ?>
		<?php $count = 1; ?>
		<table class="table table-hover table-bordered">
			<thead class="thead-light">
				<tr>
					<th scope="col" width="10%"><?php echo $countCategory; ?></th>
					<th class="tdBackgroundWithImage" style="background-image: url(<?php echo $pathCategory . $category['image']; ?>)"csope="col"><?php echo $category['title']; ?></th>
					<?php if(Yii::$app->user->identity->level == 1):?>
						<th scope="col" width="30%">Управление</th>
					<?php endif; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach($category['options'] as $option):?>
					<?php if( $option['deleted'] == 1): ?>
						<tr class="table-danger" title="Опция скрыта на основном сайте">
					<?php else: ?>
						<tr>
					<?php endif; ?>
					<td><?php echo $count; ?></td>
					<td class="tdBackgroundWithImage" style="background-image: url(<?php echo $path . $option['image']; ?>)">
						<?php echo $option['title']; ?>	
					</td>
					<?php if(Yii::$app->user->identity->level == 1):?>
					<td>
						<?php
							echo Html::tag('i', null, ['class' => 'far fa-edit text-secondary pointer buttonEditOption', 'id' => 'idForEditOption-' . $option['id'], 'style' => 'margin: 0 10px 0 0;', 'data-toggle' => 'modal', 'data-target' => '#editOption', 'title' => 'Редактировать опцию']);
							if( $option['deleted'] == 1 )
							{
								echo Html::tag('i', null, ['class' => 'fas fa-undo-alt text-success pointer buttonRestoreOption', 'id' => 'idForRestoreOption-' . $option['id'], 'data-toggle' => 'modal', 'data-target' => '#restoreOption', 'title' => 'Восстановить опцию']);
							}
							else
							{
								echo Html::tag('i', null, ['class' => 'fas fa-trash text-danger pointer buttonDeleteOption', 'id' => 'idForDeleteOption-' . $option['id'], 'data-toggle' => 'modal', 'data-target' => '#deleteOption', 'title' => 'Удалить опцию']);
							}
						?>
					</td>
					<?php endif; ?>
					<?php
						if(Yii::$app->user->identity->level == 1)
						{
							echo Html::hiddenInput(null, $option['title'], ['class' => 'edit-title-' . $option['id']]);
							echo Html::hiddenInput(null, $option['description'], ['class' => 'edit-description-' . $option['id']]);
							echo Html::hiddenInput(null, $option['id_category'], ['class' => 'edit-category-' . $option['id']]);

							$size = getimagesize($path . $option['image']);
							$class = substr($option['image'], 0, -4);

							$data = '<div class="image ' . $class . '" style="background-image: url(' . $path . $option['image'] . ');">
										<div class="imageRun" nameImage="' . $option['image'] . '">
											<i class="fas fa-crop-alt imageCrop" particularclass="' . $class . '" address="' . $path . $option['image'] . '" imageWidth="' . $size[0] . '" imageHeight="' . $size[1] . '"  title="Кадрировать изображение"></i>
											<i class="fas fa-code imageInsert" title="Вставить изображение в текст" address="' . $path . $option['image'] . '"></i>
										</div>
									</div>';
									
							echo Html::hiddenInput(null, $data, ['class' => 'edit-image-' . $option['id']]);
							echo Html::hiddenInput(null, $option['image'], ['class' => 'edit-name-image-' . $option['id']]);
						}
					?>
					</tr>
					<?php $count++; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
		<?php $countCategory++; ?>
	<?php endforeach; ?>
<?php else: ?>
	<?php echo Html::tag('div', 'Опции еще не созданы', ['class' => 'alert alert-secondary', 'style' => 'margin-top: 10px;']);?>
<?php endif; ?>

<?php
	if(Yii::$app->user->identity->level == 1)
	{
		echo $this->render('modalNew', compact('newModel', 'optionImage', 'options'));
		echo $this->render('modalEdit', compact('editModel', 'optionImage', 'options'));
		echo $this->render('modalRemove', compact('removeModel'));
		echo $this->render('modalRestore', compact('restoreModel'));
		echo $this->render('makeImage');
	}
?>