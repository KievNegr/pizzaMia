<?php
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\bootstrap4\ActiveForm;

	$this->registerJsFile('@web/js/makeseolink.js', ['depends' => 'yii\web\JqueryAsset']);
	$this->registerJsFile('@web/js/category.js', ['depends' => 'yii\web\JqueryAsset']);
	
	if(Yii::$app->user->identity->level == 1)
	{
		echo Html::button('Создать категорию', ['class' => 'btn btn-success right clearFields', 'data-toggle' => 'modal', 'data-target' => '#newCategory']);
	}
	
	echo Html::tag('h2', 'Список категорий');

	if(yii::$app->session->getFlash('success'))
	{
		yii\bootstrap4\Alert::begin(['options' => ['class' => 'alert alert-success']]);
			echo yii::$app->session->getFlash('success');
		yii\bootstrap4\Alert::end();
	}
?>

<?php if($allcategory): ?>
<table class="table table-hover table-bordered">
	<thead class="thead-dark">
		<tr>
			<th scope="col">#</th>
			<th csope="col">Название</th>
			<th csope="col">Отображение на главной</th>
			<?php if(Yii::$app->user->identity->level == 1):?>
				<th scope="col">Управление</th>
			<?php endif; ?>
		</tr>
	</thead>
	<tbody>
		<?php $count = 1; ?>
		<?php foreach($allcategory as $category): ?>
			<?php if( $category['deleted'] == 1): ?>
				<tr class="table-danger" title="Категория скрыта на основном сайте">
			<?php else: ?>
				<tr class="table-info">
			<?php endif; ?>
				<td><?php echo $count; ?></td>
				<?php if( $category['image'] ): ?>
					<td class="tdBackgroundWithImage" style="background-image: url(<?php echo $path . $category['image']; ?>?)">
				<?php else: ?>
					<td>
				<?php endif; ?>
					<?php echo $category['title']; ?>	
				</td>
				<td>
					<?php
						if($category['main'] == 1)
						{
							echo '<i class="fas fa-check text-success"></i>';
						}
					?>
				</td>
				<?php if(Yii::$app->user->identity->level == 1):?>
				<td>
					<?php
						echo Html::tag('i', null, ['class' => 'far fa-edit text-secondary pointer buttonEditCategory', 'id' => 'idForEditCategory-' . $category['id'], 'style' => 'margin: 0 10px 0 0;', 'data-toggle' => 'modal', 'data-target' => '#editCategory', 'title' => 'Редактировать категорию']);
						if( $category['deleted'] == 1 )
						{
							echo Html::tag('i', null, ['class' => 'fas fa-undo-alt text-success pointer buttonRestoreCategory', 'id' => 'idForRestoreCategory-' . $category['id'], 'data-toggle' => 'modal', 'data-target' => '#restoreCategory', 'title' => 'Восстановить категорию']);
						}
						else
						{
							if($category['main'] != 1)
							{
								echo Html::tag('i', null, ['class' => 'fas fa-trash text-danger pointer buttonDeleteCategory', 'id' => 'idForDeleteCategory-' . $category['id'], 'data-toggle' => 'modal', 'data-target' => '#deleteCategory', 'title' => 'Удалить категорию']);
							}
						}
					?>
				</td>
				<?php endif; ?>
				<?php
					if(Yii::$app->user->identity->level == 1)
					{
						echo Html::hiddenInput(null, $category['title'], ['class' => 'edit-title-' . $category['id']]);
						echo Html::hiddenInput(null, $category['sef'], ['class' => 'edit-sef-' . $category['id']]);
						echo Html::hiddenInput(null, $category['text'], ['class' => 'edit-text-' . $category['id']]);
						echo Html::hiddenInput(null, $category['parent'], ['class' => 'edit-parent-' . $category['id']]);
						echo Html::hiddenInput(null, $category['main'], ['class' => 'edit-main-' . $category['id']]);
						
						$size = getimagesize($path . $category['image']);
						$class = substr($category['image'], 0, -4);

						$data = '<div class="image ' . $class . '" style="background-image: url(' . $path . $category['image'] . '?);">
									<div class="imageRun" nameImage="' . $category['image'] . '">
										<i class="fas fa-crop-alt imageCrop" particularclass="' . $class . '" address="' . $path . $category['image'] . '" imageWidth="' . $size[0] . '" imageHeight="' . $size[1] . '"  title="Кадрировать изображение"></i>
									</div>
								</div>';

						echo Html::hiddenInput(null, $data, ['class' => 'edit-image-' . $category['id']]);
						echo Html::hiddenInput(null, $category['image'], ['class' => 'edit-name-image-' . $category['id']]);
					}
				?>
			</tr>
			<?php if(isset($category['child'])): ?>
				<?php foreach($category['child'] as $child): ?>
					<?php $count++; ?>
					<?php if( $category['deleted'] == 1 || $child['deleted'] == 1): ?>
						<tr class="table-danger" title="Категория скрыта на основном сайте">
					<?php else: ?>
						<tr>
					<?php endif; ?>
						<td><?php echo $count; ?></td>
						<?php if( $child['image'] ): ?>
							<td class="sub tdBackgroundWithImage" style="background-image: url(<?php echo $path . $child['image']; ?>)">
						<?php else: ?>
							<td class="sub">
						<?php endif; ?>
						<i class="fas fa-angle-right"></i>
						<?php echo $child['title']; ?></td>
						<td>
							<?php
								if($child['main'] == 1)
								{
									echo '<i class="fas fa-check text-success"></i>';
								}
							?>
						</td>
						<?php if(Yii::$app->user->identity->level == 1):?>
						<td>
							<?php
								echo Html::tag('i', null, ['class' => 'far fa-edit text-secondary pointer buttonEditCategory', 'id' => 'idForEditCategory-' . $child['id'], 'style' => 'margin: 0 10px 0 0;', 'data-toggle' => 'modal', 'data-target' => '#editCategory', 'title' => 'Редактировать категорию']);
								if( $category['deleted'] == 1 )
								{
									echo Html::tag('i', null, ['class' => 'far fa-question-circle text-info', 'title' => 'Сперва нужно восстановить родительскую категорию ' . $category['title']]);
								}
								elseif( $child['deleted'] == 1 )
								{
									echo Html::tag('i', null, ['class' => 'fas fa-undo-alt text-success pointer buttonRestoreCategory', 'id' => 'idForRestoreCategory-' . $child['id'], 'data-toggle' => 'modal', 'data-target' => '#restoreCategory', 'title' => 'Восстановить категорию']);
								}
								else
								{
									if($child['main'] != 1)
									{
										echo Html::tag('i', null, ['class' => 'fas fa-trash text-danger pointer buttonDeleteCategory', 'id' => 'idForDeleteCategory-' . $child['id'], 'data-toggle' => 'modal', 'data-target' => '#deleteCategory', 'title' => 'Удалить категорию']);
									}
								}
							?>
						</td>
						<?php endif; ?>
						<?php
							if(Yii::$app->user->identity->level == 1)
							{
								echo Html::hiddenInput(null, $child['title'], ['class' => 'edit-title-' . $child['id']]);
								echo Html::hiddenInput(null, $child['sef'], ['class' => 'edit-sef-' . $child['id']]);
								echo Html::hiddenInput(null, $child['text'], ['class' => 'edit-text-' . $child['id']]);
								echo Html::hiddenInput(null, $child['parent'], ['class' => 'edit-parent-' . $child['id']]);
								echo Html::hiddenInput(null, $child['main'], ['class' => 'edit-main-' . $child['id']]);

								$size = getimagesize($path . $child['image']);
								$class = substr($child['image'], 0, -4);

								$data = '<div class="image ' . $class . '" style="background-image: url(' . $path . $child['image'] . ');">
											<div class="imageRun" nameImage="' . $child['image'] . '">
												<i class="fas fa-crop-alt imageCrop" particularclass="' . $class . '" address="' . $path . $child['image'] . '" imageWidth="' . $size[0] . '" imageHeight="' . $size[1] . '"  title="Кадрировать изображение"></i>
											</div>
										</div>';

								echo Html::hiddenInput(null, $data, ['class' => 'edit-image-' . $child['id']]);
								echo Html::hiddenInput(null, $child['image'], ['class' => 'edit-name-image-' . $child['id']]);
							}
						?>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		<?php $count++; ?>
		<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
	<?php echo Html::tag('div', 'Категории еще не созданы', ['class' => 'alert alert-secondary', 'style' => 'margin-top: 10px;']);?>
<?php endif; ?>

<?php
	if(Yii::$app->user->identity->level == 1)
	{
		echo $this->render('modalNew', compact('model', 'parent', 'categoryIcon'));
		echo $this->render('modalEdit', compact('editModel', 'parent', 'categoryIcon'));
		echo $this->render('modalRemove', compact('removeModel'));
		echo $this->render('modalRestore', compact('restoreModel'));
		echo $this->render('makeIcon');
	}
?>