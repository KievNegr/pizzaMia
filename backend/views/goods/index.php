<?php
	use yii\helpers\Html;
	$this->registerJsFile('@web/js/makeseolink.js', ['depends' => 'yii\web\JqueryAsset']);
	$this->registerJsFile('@web/js/goods.js', ['depends' => 'yii\web\JqueryAsset']);

	if(Yii::$app->user->identity->level == 1)
	{
		echo Html::button('Новый товар', ['class' => 'btn btn-success right clearFields', 'data-toggle' => 'modal', 'data-target' => '.newItem']);
	}
	
	echo Html::tag('h2', 'Список товаров');

	if(yii::$app->session->getFlash('success'))
	{
		yii\bootstrap4\Alert::begin(['options' => ['class' => 'alert alert-success']]);
			echo yii::$app->session->getFlash('success');
		yii\bootstrap4\Alert::end();
	}
?>

<?php if($goods): ?>
<table class="table table-hover table-bordered">
	<thead class="thead-dark">
		<tr>
			<th scope="col">#</th>
			<th csope="col">Название</th>
			<th scope="col">Категория</th>
			<th scope="col">Online заказ</th>
			<?php if(Yii::$app->user->identity->level == 1):?>
				<th scope="col">Управление</th>
			<?php endif; ?>
		</tr>
	</thead>
	<tbody>
		<?php $count = 1; ?>
		<?php foreach($goods as $item): ?>
			<?php if( $item['deleted'] == 1): ?>
				<tr class="table-danger" title="Товар скрыт на основном сайте">
			<?php else: ?>
				<tr>
			<?php endif; ?>
				<td><?php echo $count; ?></td>
				<td>
					<?php echo $item['title']; ?>
				</td>
				<td>
					<?php echo $item['category_name']; ?>
				</td>
				<td>
					<?php
						if($item['online_order'] == 1)
						{
							echo '<i class="fas fa-check text-success"></i>';
						}
					?>
				</td>
				<?php if(Yii::$app->user->identity->level == 1):?>
				<td>
					<?php
						echo Html::tag('i', null, ['class' => 'far fa-edit text-secondary pointer buttonEditItem', 'id' => 'idForEditItem-' . $item['id'], 'style' => 'margin: 0 10px 0 0;', 'data-toggle' => 'modal', 'data-target' => '.editItem', 'title' => 'Редактировать товар']);
						if( $item['deleted'] == 1 )
						{
							echo Html::tag('i', null, ['class' => 'fas fa-undo-alt text-success pointer buttonRestoreItem', 'id' => 'idForRestoreItem-' . $item['id'], 'data-toggle' => 'modal', 'data-target' => '#restoreItem', 'title' => 'Восстановить товар']);
						}
						else
						{
							echo Html::tag('i', null, ['class' => 'fas fa-trash text-danger pointer buttonDeleteItem', 'id' => 'idForDeleteItem-' . $item['id'], 'data-toggle' => 'modal', 'data-target' => '#DeleteItem', 'title' => 'Удалить товар']);
						}
					?>
				</td>
				<?php endif; ?>
				<?php
					if(Yii::$app->user->identity->level == 1)
					{
						echo Html::hiddenInput(null, $item['title'], ['class' => 'edit-title-' . $item['id']]);
						echo Html::hiddenInput(null, $item['sef'], ['class' => 'edit-sef-' . $item['id']]);
						echo Html::hiddenInput(null, $item['description'], ['class' => 'edit-description-' . $item['id']]);
						echo Html::hiddenInput(null, $item['text'], ['class' => 'edit-text-' . $item['id']]);
						echo Html::hiddenInput(null, $item['category'], ['class' => 'edit-category-' . $item['id']]);

						$values = null;
						foreach($item['options'] as $option)
						{
							$values .= '<div class="form-group itemprice-' . $option['optionName']['id'] . ' required validating">
											<label for="itemprice-' . $option['optionName']['id'] . '">Цена (' . $option['optionName']['title'] . ')</label>
											<input type="text" id="itemprice-' . $option['optionName']['id'] . '" class="form-control" name="EditForm[itemPrice][' . $option['optionName']['id'] . ']" aria-invalid="true" value="' . $option['price'] . '">

											<div class="invalid-feedback"></div>
										</div>';
						}
						echo Html::hiddenInput(null, $values, ['class' => 'edit-prices-' . $item['id']]);
						echo Html::hiddenInput(null, $item['ingredients'], ['class' => 'edit-ingredients-' . $item['id']]);
						echo Html::hiddenInput(null, $item['popular'], ['class' => 'edit-popular-' . $item['id']]);
						echo Html::hiddenInput(null, $item['new'], ['class' => 'edit-new-' . $item['id']]);
						echo Html::hiddenInput(null, $item['online_order'], ['class' => 'edit-online_order-' . $item['id']]);

						$listImage = null;
						$availableImage = null;
						$checkedImage = '';
						foreach($item['images'] as $image)
						{
							$listImage .= $image['name'] . ',';

							$size = getimagesize($pathGoodsImages . $image['name']);
							$class = substr($image['name'], 0, -4);
							$checked = '';

							if($image['main_pic'] == 1)
							{
								$checked = 'checked';
								$checkedImage = $image['name'];
							}

							$availableImage .= '<div class="image ' . $class . '" style="background-image: url(' . $pathGoodsImages . $image['name'] . '?);">
												<div class="imageRun" nameImage="' . $image['name'] . '">
													<i class="fas fa-crop-alt imageCrop" particularclass="' . $class . '" address="' . $pathGoodsImages . $image['name'] . '" imageWidth="' . $size[0] . '" imageHeight="' . $size[1] . '" title="Кадрировать изображение"></i>
													<i class="fas fa-trash-alt deleteImage" nameDelete="' . $class . '"></i>
													<input type="radio" name="newGoodsImages" value="' . $image['name'] . '" class="selectEditImageRadio" title="Установить по умолчанию" ' . $checked .'/>
												</div>
											</div>';
						}
						echo Html::hiddenInput(null, $listImage, ['class' => 'edit-image-' . $item['id']]);
						echo Html::hiddenInput(null, $availableImage, ['class' => 'edit-imageList-' . $item['id']]);
						echo Html::hiddenInput(null, $checkedImage, ['class' => 'edit-imageCheck-' . $item['id']]);
					}
				?>
			</tr>
		<?php $count++; ?>
		<?php endforeach; ?>
	</tbody>
</table>

<?php else: ?>
	<?php echo Html::tag('div', 'Товары еще не созданы', ['class' => 'alert alert-secondary', 'style' => 'margin-top: 10px;']);?>
<?php endif; ?>

<?php
	if(Yii::$app->user->identity->level == 1)
	{
		echo $this->render('modalNew', compact('newModel', 'ingredients', 'categories', 'newImagesModel', 'pathIngredientImages'));
		echo $this->render('modalEdit', compact('editModel', 'ingredients', 'categories', 'editImagesModel', 'pathIngredientImages'));
		echo $this->render('modalRemove', compact('deleteModel'));
		echo $this->render('modalRestore', compact('restoreModel'));
		echo $this->render('makeImage');
	}
?>