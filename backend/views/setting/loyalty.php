<?php
	use yii\helpers\Html;
	use yii\helpers\Url;
	$this->registerJsFile('@web/js/loyalty.js', ['depends' => 'yii\web\JqueryAsset']);

	echo Html::a('<i class="fas fa-angle-left"></i> Настройки</a>', Url::to(['setting/index']), ['class' => 'btn btn-link btn-sm', 'role' => 'button', 'aria-pressed' => 'true']);

	echo Html::button('Новая скидка', ['class' => 'btn btn-success right clearFields', 'data-toggle' => 'modal', 'data-target' => '.newLoyalty']);
	
	echo Html::tag('h2', 'Программа лояльности');

	if(Yii::$app->session->getFlash('success'))
	{
		yii\bootstrap4\Alert::begin(['options' => ['class' => 'alert alert-success']]);
			echo yii::$app->session->getFlash('success');
		yii\bootstrap4\Alert::end();
	}
?>

<?php if($loyaltyList): ?>
<table class="table table-hover table-bordered">
	<thead class="thead-dark">
		<tr>
			<th scope="col">#</th>
			<th csope="col">Сумма от</th>
			<th scope="col">Сумма до</th>
			<th scope="col">Размер скидки (%)</th>
			<th scope="col">Управление</th>
		</tr>
	</thead>
	<tbody>
		<?php $count = 1; ?>
		<?php foreach($loyaltyList as $loyalty): ?>
			<?php if( $loyalty['deleted'] == 1): ?>
				<tr class="table-danger" title="Скидка недоступна">
			<?php else: ?>
				<tr>
			<?php endif; ?>
				<td><?php echo $count; ?></td>
				<td>
					<?php echo $loyalty['from_sum']; ?>
				</td>
				<td>
					<?php echo $loyalty['to_sum']; ?>
				</td>
				<td>
					<?php echo $loyalty['discount']; ?>
				</td>
				<td>
					<?php
						echo Html::tag('i', null, ['class' => 'far fa-edit text-secondary pointer buttonEditLoyalty', 'id' => 'idForEditLoyalty-' . $loyalty['id'], 'style' => 'margin: 0 10px 0 0;', 'data-toggle' => 'modal', 'data-target' => '.editLoyalty', 'title' => 'Редактировать скидку']);
						if( $loyalty['deleted'] == 1 )
						{
							echo Html::tag('i', null, ['class' => 'fas fa-undo-alt text-success pointer buttonRestoreLoyalty', 'id' => 'idForRestoreLoyalty-' . $loyalty['id'], 'data-toggle' => 'modal', 'data-target' => '.restoreLoyalty', 'title' => 'Восстановить скидку']);
						}
						else
						{
							echo Html::tag('i', null, ['class' => 'fas fa-trash text-danger pointer buttonDeleteLoyalty', 'id' => 'idForDeleteLoyalty-' . $loyalty['id'], 'data-toggle' => 'modal', 'data-target' => '.deleteLoyalty', 'title' => 'Удалить скидку']);
						}
					?>
				</td>
				<?php
					echo Html::hiddenInput(null, $loyalty['from_sum'], ['class' => 'edit-from-' . $loyalty['id']]);
					echo Html::hiddenInput(null, $loyalty['to_sum'], ['class' => 'edit-to-' . $loyalty['id']]);
					echo Html::hiddenInput(null, $loyalty['discount'], ['class' => 'edit-value-' . $loyalty['id']]);
				?>
			</tr>
		<?php $count++; ?>
		<?php endforeach; ?>
	</tbody>
</table>

<?php else: ?>
	<?php echo Html::tag('div', 'Скидки еще не созданы', ['class' => 'alert alert-secondary', 'style' => 'margin-top: 10px;']);?>
<?php endif; ?>

<?php
	echo $this->render('newLoyalty', compact('newModel', 'color'));
	echo $this->render('editLoyalty', compact('editModel'));
	echo $this->render('deleteLoyalty', compact('deleteModel'));
	echo $this->render('restoreLoyalty', compact('restoreModel'));
?>