<?php
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\bootstrap4\ActiveForm;

	$this->registerJsFile('@web/js/makeseolink.js', ['depends' => 'yii\web\JqueryAsset']);
    $this->registerJsFile('@web/js/page.js', ['depends' => 'yii\web\JqueryAsset']);

	if(Yii::$app->user->identity->level == 1)
	{
		echo Html::button('Создать страницу', ['class' => 'btn btn-success right clearFields', 'data-toggle' => 'modal', 'data-target' => '.newPageModalXl']);
	}

	echo Html::tag('h2', 'Список страниц');

	if(yii::$app->session->getFlash('success'))
	{
		yii\bootstrap4\Alert::begin(['options' => ['class' => 'alert alert-success']]);
			echo yii::$app->session->getFlash('success');
		yii\bootstrap4\Alert::end();
	}
?>

<?php if($pageList): ?>
<table class="table table-hover table-bordered">
	<thead class="thead-dark">
		<tr>
			<th scope="col">#</th>
			<th csope="col">Название</th>
			<th csope="col">Header</th>
			<th csope="col">Footer</th>
			<?php if(Yii::$app->user->identity->level == 1):?>
				<th scope="col">Управление</th>
			<?php endif; ?>
			<th csope="col">Сортировка</th>
		</tr>
	</thead>
	<tbody>
		<?php $count = 1; ?>
		<?php foreach($pageList as $page): ?>
			<?php if( $page['deleted'] == 1): ?>
				<tr class="table-danger" title="Страница скрыта на основном сайте">
			<?php else: ?>
				<tr>
			<?php endif; ?>
				<td><?php echo $count; ?></td>
				<td>
					<?php echo $page['title']; ?>	
				</td>
				<td>
					<?php
						if($page['showheader'] == 1)
						{
							echo '<i class="fas fa-check text-success"></i>';
						}
					?>	
				</td>
				<td>
					<?php
						if($page['showfooter'] == 1)
						{
							echo '<i class="fas fa-check text-success"></i>';
						}
					?>
				</td>
				<?php if(Yii::$app->user->identity->level == 1):?>	
				<td>
					<?php
						echo Html::tag('i', null, ['class' => 'far fa-edit text-secondary pointer buttonEditPage', 'id' => 'idForEditPage-' . $page['id'], 'style' => 'margin: 0 10px 0 0;', 'data-toggle' => 'modal', 'data-target' => '.editPageModalXl', 'title' => 'Редактировать страницу']);
						if( $page['deleted'] == 1 )
						{
							echo Html::tag('i', null, ['class' => 'fas fa-undo-alt text-success pointer buttonRestorePage', 'id' => 'idForRestorePage-' . $page['id'], 'data-toggle' => 'modal', 'data-target' => '#restorePage', 'title' => 'Восстановить страницу']);
						}
						else
						{
							echo Html::tag('i', null, ['class' => 'fas fa-trash text-danger pointer buttonDeletePage', 'id' => 'idForDeletePage-' . $page['id'], 'data-toggle' => 'modal', 'data-target' => '#deletePage', 'title' => 'Удалить страницу']);
						}
					?>
				</td>
				<td>
					<?php echo Html::textInput('sort', $page['sort'], ['class' => 'pageSort form-control form-control-sm', 'idPage' => $page['id'], 'style' => 'width: 50%;']); ?>	
				</td>
				<?php endif; ?>
				<?php
					if(Yii::$app->user->identity->level == 1)
					{
						echo Html::hiddenInput(null, $page['title'], ['class' => 'edit-title-' . $page['id']]);
						echo Html::hiddenInput(null, $page['sef'], ['class' => 'edit-sef-' . $page['id']]);
						echo Html::hiddenInput(null, $page['description'], ['class' => 'edit-description-' . $page['id']]);
						echo Html::hiddenInput(null, $page['text'], ['class' => 'edit-text-' . $page['id']]);
						echo Html::hiddenInput(null, $page['showheader'], ['class' => 'edit-showheader-' . $page['id']]);
						echo Html::hiddenInput(null, $page['showfooter'], ['class' => 'edit-showfooter-' . $page['id']]);

						$listImage = null;
						$availableImage = null;
						if($page['images'])
						{
							foreach($page['images'] as $image)
							{
								$listImage .= $image['name'] . ',';

								$size = getimagesize($path . $image['name']);
								$class = substr($image['name'], 0, -4);

	                            $availableImage .= '<div class="image ' . $class . '" style="background-image: url(' . $path . $image['name'] . ');">
												<div class="imageRun" nameImage="' . $image['name'] . '">
													<i class="fas fa-crop-alt imageCrop" particularclass="' . $class . '" address="' . $path . $image['name'] . '" imageWidth="' . $size[0] . '" imageHeight="' . $size[1] . '" title="Кадрировать изображение"></i>
													<i class="fas fa-code imageInsert" title="Вставить изображение в текст" address="' . Url::to('@page/' . $image['name']) . '"></i>
													<i class="fas fa-trash-alt deleteImage" nameDelete="' . $class . '"></i>
												</div>
											</div>';
							}
						}

						echo Html::hiddenInput(null, $availableImage, ['class' => 'edit-images-' . $page['id']]);
						echo Html::hiddenInput(null, $listImage, ['class' => 'edit-inserted-images-' . $page['id']]);
					}
				?>
			</tr>
		<?php $count++; ?>
		<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
	<?php echo Html::tag('div', 'Страницы еще не созданы', ['class' => 'alert alert-secondary', 'style' => 'margin-top: 10px;']);?>
<?php endif; ?>

<?php
	if(Yii::$app->user->identity->level == 1)
	{
		echo $this->render('modalNew', compact('newPageModel', 'pageImagesModel'));
		echo $this->render('modalEdit', compact('editPageModel', 'editPageImagesModel'));
		echo $this->render('modalRemove', compact('removeModel'));
		echo $this->render('modalRestore', compact('restoreModel'));
		echo $this->render('makeImage');
	}
?>