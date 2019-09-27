<?php
	use yii\helpers\Html;
	use yii\helpers\Url;
	$this->registerJsFile('@web/js/promo.js', ['depends' => 'yii\web\JqueryAsset']);

	echo Html::a('<i class="fas fa-angle-left"></i> Настройки</a>', Url::to(['setting/index']), ['class' => 'btn btn-link btn-sm', 'role' => 'button', 'aria-pressed' => 'true']);

	echo Html::button('Новый промокод', ['class' => 'btn btn-success right clearFields', 'data-toggle' => 'modal', 'data-target' => '.newPromo']);

	echo Html::tag('h2', 'Список промокодов');

	if(yii::$app->session->getFlash('success'))
	{
		yii\bootstrap4\Alert::begin(['options' => ['class' => 'alert alert-success']]);
			echo yii::$app->session->getFlash('success');
		yii\bootstrap4\Alert::end();
	}
?>

<?php if($promoList): ?>
<table class="table table-hover table-bordered">
	<thead class="thead-dark">
		<tr>
			<th scope="col">#</th>
			<th csope="col">Код</th>
			<th csope="col">Дата истечения</th>
			<th csope="col">Пользователь</th>
			<th csope="col">Скидка %</th>
			<th scope="col">Управление</th>
		</tr>
	</thead>
	<tbody>
		<?php $count = 1; ?>
		<?php foreach($promoList as $promo): ?>
			<?php if( $promo['deleted'] == 1): ?>
				<tr class="table-danger" title="Срок действия купона истек">
			<?php else: ?>
				<tr>
			<?php endif; ?>
				<td><?php echo $count; ?></td>
				<td>
					<?php echo $promo['code']; ?>
				</td>
				<td>
					<?php 
						echo substr($promo['expiration'], 8, 2) . '.' . substr($promo['expiration'], 5, 2) . '.' . substr($promo['expiration'], 0, 4);
					?>
				</td>
				<td>
					<?php 
						if(!empty($promo['foruser']))
						{
							echo $promo['foruser']['username'] . ' <span class="btn btn-sm btn-info">' . $promo['foruser']['phone'] . '</span>';
						}
						else
						{
							echo 'Для всех';
						}
					?>
				</td>
				<td><?php echo $promo['discount']; ?></td>
				<td>
					<?php
						echo Html::tag('i', null, ['class' => 'far fa-edit text-secondary pointer buttonEditPromo', 'id' => 'idForEditPromo-' . $promo['id'], 'style' => 'margin: 0 10px 0 0;', 'data-toggle' => 'modal', 'data-target' => '.editPromo', 'title' => 'Редактировать купон']);
						if( $promo['deleted'] == 1 )
						{
							echo Html::tag('i', null, ['class' => 'fas fa-undo-alt text-success pointer buttonRestorePromo', 'id' => 'idForRestoreOrder-' . $promo['id'], 'data-toggle' => 'modal', 'data-target' => '.restorePromo', 'title' => 'Восстановить купон']);
						}
						else
						{
							echo Html::tag('i', null, ['class' => 'fas fa-trash text-danger pointer buttonDeletePromo', 'id' => 'idForDeletePromo-' . $promo['id'], 'data-toggle' => 'modal', 'data-target' => '.removePromo', 'title' => 'Удалить купон']);
						}
					?>
				</td>
				<?php
					echo Html::hiddenInput(null, $promo['code'], ['class' => 'edit-code-' . $promo['id']]);
					echo Html::hiddenInput(null, substr($promo['expiration'], 8, 2) . '.' . substr($promo['expiration'], 5, 2) . '.' . substr($promo['expiration'], 0, 4), ['class' => 'edit-expiration-' . $promo['id']]);
					echo Html::hiddenInput(null, $promo['user'], ['class' => 'edit-user-' . $promo['id']]);
					echo Html::hiddenInput(null, $promo['discount'], ['class' => 'edit-discount-' . $promo['id']]);
					echo Html::hiddenInput(null, $promo['applying'], ['class' => 'edit-applying-' . $promo['id']]); 
					
					if(!empty($promo['foruser']))
					{
						$name = $promo['foruser']['username'];
					}
					else
					{
						$name = 'всех';
					}

					echo Html::hiddenInput(null, $name, ['class' => 'edit-name-' . $promo['id']]);
				?>
			</tr>
		<?php $count++; ?>
		<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
	<?php echo Html::tag('div', 'Промокоды еще не созданы', ['class' => 'alert alert-secondary', 'style' => 'margin-top: 10px;']);?>
<?php endif; ?>

<?php
	echo $this->render('newPromo', compact('newModel'));
	echo $this->render('editPromo', compact('editModel'));
	echo $this->render('deletePromo', compact('deleteModel'));
	echo $this->render('restorePromo', compact('restoreModel'));
?>