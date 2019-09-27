<?php
	use yii\helpers\Html;
	$this->registerJsFile('@web/js/ingredient.js', ['depends' => 'yii\web\JqueryAsset']);
	
	if(Yii::$app->user->identity->level == 1)
	{
		echo Html::button('Новый ингредиент', ['class' => 'btn btn-success right clearFields', 'data-toggle' => 'modal', 'data-target' => '#newIngredient']);
	}
	
	echo Html::tag('h2', 'Список ингредиентов');

	if(yii::$app->session->getFlash('success'))
	{
		yii\bootstrap4\Alert::begin(['options' => ['class' => 'alert alert-success']]);
			echo yii::$app->session->getFlash('success');
		yii\bootstrap4\Alert::end();
	}
?>

<?php if($ingredients): ?>
<table class="table table-hover table-bordered">
	<thead class="thead-dark">
		<tr>
			<th scope="col">#</th>
			<th csope="col">Название</th>
			<?php if(Yii::$app->user->identity->level == 1):?>
				<th scope="col">Управление</th>
			<?php endif; ?>
		</tr>
	</thead>
	<tbody>
		<?php $count = 1; ?>
		<?php foreach($ingredients as $ingredient): ?>
			<?php if( $ingredient['deleted'] == 1): ?>
				<tr class="table-danger" title="Ингредиент скрыт на основном сайте">
			<?php else: ?>
				<tr>
			<?php endif; ?>
				<td><?php echo $count; ?></td>
				<td class="tdBackgroundWithImage" style="background-image: url(<?php echo $path . $ingredient['image']; ?>)">
					<?php echo $ingredient['name']; ?>	
				</td>
				<?php if(Yii::$app->user->identity->level == 1):?>
				<td>
					<?php
						echo Html::tag('i', null, ['class' => 'far fa-edit text-secondary pointer buttonEditIngredient', 'id' => 'idForEditIngredient-' . $ingredient['id'], 'style' => 'margin: 0 10px 0 0;', 'data-toggle' => 'modal', 'data-target' => '#editIngredient', 'title' => 'Редактировать ингредиент']);
						if( $ingredient['deleted'] == 1 )
						{
							echo Html::tag('i', null, ['class' => 'fas fa-undo-alt text-success pointer buttonRestoreIngredient', 'id' => 'idForRestoreIngredient-' . $ingredient['id'], 'data-toggle' => 'modal', 'data-target' => '#restoreIngredient', 'title' => 'Восстановить ингредиент']);
						}
						else
						{
							echo Html::tag('i', null, ['class' => 'fas fa-trash text-danger pointer buttonDeleteIngredient', 'id' => 'idForDeleteIngredient-' . $ingredient['id'], 'data-toggle' => 'modal', 'data-target' => '#deleteIngredient', 'title' => 'Удалить ингредиент']);
						}
					?>
				</td>
				<?php endif; ?>
				<?php
					if(Yii::$app->user->identity->level == 1)
					{
						echo Html::hiddenInput(null, $ingredient['name'], ['class' => 'edit-name-' . $ingredient['id']]);

						$size = getimagesize($path . $ingredient['image']);
						$class = substr($ingredient['image'], 0, -4);

						$data = '<div class="image ' . $class . '" style="background-image: url(' . $path . $ingredient['image'] . '?);">
									<div class="imageRun" nameImage="' . $ingredient['image'] . '">
										<i class="fas fa-crop-alt imageCrop" particularclass="' . $class . '" address="' . $path . $ingredient['image'] . '" imageWidth="' . $size[0] . '" imageHeight="' . $size[1] . '"  title="Кадрировать изображение"></i>
									</div>
								</div>';

						echo Html::hiddenInput(null, $data, ['class' => 'edit-image-' . $ingredient['id']]);
						echo Html::hiddenInput(null, $ingredient['image'], ['class' => 'edit-name-image-' . $ingredient['id']]);
					}
				?>
			</tr>
		<?php $count++; ?>
		<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
	<?php echo Html::tag('div', 'Ингредиенты еще не созданы', ['class' => 'alert alert-secondary', 'style' => 'margin-top: 10px;']);?>
<?php endif; ?>

<?php
	if(Yii::$app->user->identity->level == 1)
	{
		echo $this->render('modalNew', compact('newModel', 'ingredientImage'));
		echo $this->render('modalEdit', compact('editModel', 'ingredientImage'));
		echo $this->render('modalRemove', compact('removeModel'));
		echo $this->render('modalRestore', compact('restoreModel'));
		echo $this->render('makeImage');
	}
?>