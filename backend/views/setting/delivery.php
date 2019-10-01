<?php
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\bootstrap4\ActiveForm;
	$this->registerJsFile('@web/js/delivery.js', ['depends' => 'yii\web\JqueryAsset']);

	echo Html::a('<i class="fas fa-angle-left"></i> Настройки</a>', Url::to(['setting/index']), ['class' => 'btn btn-link btn-sm', 'role' => 'button', 'aria-pressed' => 'true']);

	echo Html::button('Новый вариант доставки', ['class' => 'btn btn-success right', 'data-toggle' => 'modal', 'data-target' => '.newDelivery']);

	echo Html::tag('h2', 'Список вариантов доставки');

	if(yii::$app->session->getFlash('success'))
	{
		yii\bootstrap4\Alert::begin(['options' => ['class' => 'alert alert-success']]);
			echo yii::$app->session->getFlash('success');
		yii\bootstrap4\Alert::end();
	}
?>


<?php if($deliveryList): ?>
<table class="table table-hover table-bordered">
	<thead class="thead-dark">
		<tr>
			<th scope="col">#</th>
			<th csope="col">Название</th>
			<th csope="col">Стоимость</th>
			<th scope="col">Управление</th>
		</tr>
	</thead>
	<tbody>
		<?php $count = 1; ?>
		<?php foreach($deliveryList as $delivery): ?>
			<?php if( $delivery['deleted'] == 1): ?>
				<tr class="table-danger" title="Вариант доставки скрыт на основном сайте">
			<?php else: ?>
				<tr>
			<?php endif; ?>
				<td><?php echo $count; ?></td>
				<td>
					<?php echo $delivery['name']; ?>	
				</td>
				<td>
					<?php echo $delivery['value']; ?>	
				</td>	
				<td>
					<?php
						echo Html::tag('i', null, ['class' => 'far fa-edit text-secondary pointer buttonEditDelivery', 'id' => 'idForEditDelivery-' . $delivery['id'], 'style' => 'margin: 0 10px 0 0;', 'data-toggle' => 'modal', 'data-target' => '.editDelivery', 'title' => 'Редактировать вариант доставки']);
						if( $delivery['deleted'] == 1 )
						{
							echo Html::tag('i', null, ['class' => 'fas fa-undo-alt text-success pointer buttonRestoreDelivery', 'id' => 'idForRestoreDelivery-' . $delivery['id'], 'data-toggle' => 'modal', 'data-target' => '.restoreDelivery', 'title' => 'Восстановить  вариант доставки']);
						}
						else
						{
							echo Html::tag('i', null, ['class' => 'fas fa-trash text-danger pointer buttonDeleteDelivery', 'id' => 'idForDeleteDelivery-' . $delivery['id'], 'data-toggle' => 'modal', 'data-target' => '.removeDelivery', 'title' => 'Удалить  вариант доставки']);
						}
					?>
				</td>
				<?php
					echo Html::hiddenInput(null, $delivery['name'], ['class' => 'edit-name-' . $delivery['id']]);
					echo Html::hiddenInput(null, $delivery['value'], ['class' => 'edit-value-' . $delivery['id']]);
				?>
			</tr>
		<?php $count++; ?>
		<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
	<?php echo Html::tag('div', 'Варианты доставки еще не созданы', ['class' => 'alert alert-secondary', 'style' => 'margin-top: 10px;']);?>
<?php endif; ?>

<?php
	echo $this->render('newDelivery', compact('newModel'));
	echo $this->render('editDelivery', compact('editModel'));
	echo $this->render('deleteDelivery', compact('deleteModel'));
	echo $this->render('restoreDelivery', compact('restoreModel'));
?>