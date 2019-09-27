<?php
	use yii\helpers\Html;
	use yii\helpers\Url;
	$this->registerJsFile('@web/js/pay.js', ['depends' => 'yii\web\JqueryAsset']);

	echo Html::a('<i class="fas fa-angle-left"></i> Настройки</a>', Url::to(['setting/index']), ['class' => 'btn btn-link btn-sm', 'role' => 'button', 'aria-pressed' => 'true']);

	echo Html::button('Новый вариант оплаты', ['class' => 'btn btn-success right clearFields', 'data-toggle' => 'modal', 'data-target' => '.newPay']);
	
	echo Html::tag('h2', 'Список вариантов оплаты');
 
	if(yii::$app->session->getFlash('success'))
	{
		yii\bootstrap4\Alert::begin(['options' => ['class' => 'alert alert-success']]);
			echo yii::$app->session->getFlash('success');
		yii\bootstrap4\Alert::end();
	}
?>


<?php if($payList): ?>
<table class="table table-hover table-bordered">
	<thead class="thead-dark">
		<tr>
			<th scope="col">#</th>
			<th csope="col">Название</th>
			<th csope="col">Оплата картой</th>
			<th scope="col">Управление</th>
		</tr>
	</thead>
	<tbody>
		<?php $count = 1; ?>
		<?php foreach($payList as $pay): ?>
			<?php if( $pay['deleted'] == 1): ?>
				<tr class="table-danger" title="Вариант оплаты скрыт на основном сайте">
			<?php else: ?>
				<tr>
			<?php endif; ?>
				<td><?php echo $count; ?></td>
				<td>
					<?php echo $pay['name']; ?>	
				</td>
				<td>
					<?php
						if($pay['card'] == 1)
						{
							echo '<i class="fas fa-check text-success"></i>';
						}
					?>	
				</td>	
				<td>
					<?php
						echo Html::tag('i', null, ['class' => 'far fa-edit text-secondary pointer buttonEditPay', 'id' => 'idForEditPay-' . $pay['id'], 'style' => 'margin: 0 10px 0 0;', 'data-toggle' => 'modal', 'data-target' => '.editPay', 'title' => 'Редактировать вариант оплаты']);
						if( $pay['deleted'] == 1 )
						{
							echo Html::tag('i', null, ['class' => 'fas fa-undo-alt text-success pointer buttonRestorePay', 'id' => 'idForRestorePay-' . $pay['id'], 'data-toggle' => 'modal', 'data-target' => '.restorePay', 'title' => 'Восстановить  вариант оплаты']);
						}
						else
						{
							echo Html::tag('i', null, ['class' => 'fas fa-trash text-danger pointer buttonDeletePay', 'id' => 'idForDeletePay-' . $pay['id'], 'data-toggle' => 'modal', 'data-target' => '.removePay', 'title' => 'Удалить  вариант оплаты']);
						}
					?>
				</td>
				<?php
					echo Html::hiddenInput(null, $pay['name'], ['class' => 'edit-name-' . $pay['id']]);
					echo Html::hiddenInput(null, $pay['card'], ['class' => 'edit-card-' . $pay['id']]);
				?>
			</tr>
		<?php $count++; ?>
		<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
	<?php echo Html::tag('div', 'Варианты оплаты еще не созданы', ['class' => 'alert alert-secondary', 'style' => 'margin-top: 10px;']);?>
<?php endif; ?>

<?php
	echo $this->render('newPay', compact('newModel'));
	echo $this->render('editPay', compact('editModel'));
	echo $this->render('deletePay', compact('deleteModel'));
	echo $this->render('restorePay', compact('restoreModel'));
?>