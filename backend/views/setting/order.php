<?php
	use yii\helpers\Html;
	use yii\helpers\Url;
	$this->registerJsFile('@web/js/order.js', ['depends' => 'yii\web\JqueryAsset']);
	
	echo Html::a('<i class="fas fa-angle-left"></i> Настройки</a>', Url::to(['setting/index']), ['class' => 'btn btn-link btn-sm', 'role' => 'button', 'aria-pressed' => 'true']);

	echo Html::button('Новое состояние заказа', ['class' => 'btn btn-success right clearFields', 'data-toggle' => 'modal', 'data-target' => '.newOrder']);

	echo Html::tag('h2', 'Список состояния заказов');
	
	if(yii::$app->session->getFlash('success'))
	{
		yii\bootstrap4\Alert::begin(['options' => ['class' => 'alert alert-success']]);
			echo yii::$app->session->getFlash('success');
		yii\bootstrap4\Alert::end();
	}
?>


<?php if($orderList): ?>
<table class="table table-hover table-bordered">
	<thead class="thead-dark">
		<tr>
			<th scope="col">#</th>
			<th csope="col">Название</th>
			<th csope="col">Цвет</th>
			<th csope="col">Отмена скидки</th>
			<th csope="col">Установка рейтинга</th>
			<th csope="col">Заработанные деньги</th>
			<th csope="col">Деньги ожидаются</th>
			<th csope="col">Деньги новых заказов</th>
			<th scope="col">Управление</th>
		</tr>
	</thead>
	<tbody>
		<?php $count = 1; ?>
		<?php foreach($orderList as $order): ?>
			<?php if( $order['deleted'] == 1): ?>
				<tr class="table-danger" title="Состояние заказа скрыто">
			<?php else: ?>
				<tr>
			<?php endif; ?>
				<td><?php echo $count; ?></td>
				<td>
					<?php echo $order['name']; ?>
				</td>
				<td class="<?php echo $order['color']; ?>"><?php echo $order['color']; ?></td>	
				<td>
					<?php
						if($order['cancel_promo'] == 1)
						{
							echo '<i class="fas fa-check text-success"></i>';
						}
					?>
				</td>
				<td>
					<?php
						if($order['set_ratio'] == 1)
						{
							echo '<i class="fas fa-check text-success"></i>';
						}
					?>
				</td>
				<td>
					<?php
						if($order['earned'] == 1)
						{
							echo '<i class="fas fa-check text-success"></i>';
						}
					?>
				</td>
				<td>
					<?php
						if($order['expected'] == 1)
						{
							echo '<i class="fas fa-check text-success"></i>';
						}
					?>
				</td>
				<td>
					<?php
						if($order['hoped'] == 1)
						{
							echo '<i class="fas fa-check text-success"></i>';
						}
					?>
				</td>
				<td>
					<?php
						echo Html::tag('i', null, ['class' => 'far fa-edit text-secondary pointer buttonEditOrder', 'id' => 'idForEditOrder-' . $order['id'], 'style' => 'margin: 0 10px 0 0;', 'data-toggle' => 'modal', 'data-target' => '.editOrder', 'title' => 'Редактировать состояние заказа']);
						if( $order['deleted'] == 1 )
						{
							echo Html::tag('i', null, ['class' => 'fas fa-undo-alt text-success pointer buttonRestoreOrder', 'id' => 'idForRestoreOrder-' . $order['id'], 'data-toggle' => 'modal', 'data-target' => '.restorOrder', 'title' => 'Восстановить состояние заказа']);
						}
						else
						{
							echo Html::tag('i', null, ['class' => 'fas fa-trash text-danger pointer buttonDeleteOrder', 'id' => 'idForDeleteOrder-' . $order['id'], 'data-toggle' => 'modal', 'data-target' => '.removeOrder', 'title' => 'Удалить состояние заказа']);
						}
					?>
				</td>
				<?php
					echo Html::hiddenInput(null, $order['name'], ['class' => 'edit-name-' . $order['id']]);
					echo Html::hiddenInput(null, $order['color'], ['class' => 'edit-color-' . $order['id']]);
					echo Html::hiddenInput(null, $order['cancel_promo'], ['class' => 'edit-cancel_promo-' . $order['id']]);
					echo Html::hiddenInput(null, $order['set_ratio'], ['class' => 'edit-ratio-' . $order['id']]);
					echo Html::hiddenInput(null, $order['earned'], ['class' => 'edit-earned-' . $order['id']]);
					echo Html::hiddenInput(null, $order['expected'], ['class' => 'edit-expected-' . $order['id']]);
					echo Html::hiddenInput(null, $order['hoped'], ['class' => 'edit-hoped-' . $order['id']]);
				?>
			</tr>
		<?php $count++; ?>
		<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
	<?php echo Html::tag('div', 'Состояние заказов еще не созданы', ['class' => 'alert alert-secondary', 'style' => 'margin-top: 10px;']);?>
<?php endif; ?>

<?php
	echo $this->render('newOrder', compact('newModel', 'color'));
	echo $this->render('editOrder', compact('editModel', 'color'));
	echo $this->render('deleteOrder', compact('deleteModel'));
	echo $this->render('restoreOrder', compact('restoreModel'));
?>