<?php
	use yii\helpers\Html;
	use yii\helpers\Url;
	$this->registerJsFile('@web/js/currency.js', ['depends' => 'yii\web\JqueryAsset']);

	echo Html::a('<i class="fas fa-angle-left"></i> Настройки</a>', Url::to(['setting/index']), ['class' => 'btn btn-link btn-sm', 'role' => 'button', 'aria-pressed' => 'true']);

	echo Html::button('Новая валюта', ['class' => 'btn btn-success right', 'data-toggle' => 'modal', 'data-target' => '.newCurrency']);

	echo Html::tag('h2', 'Список валюты');

	if(yii::$app->session->getFlash('success'))
	{
		yii\bootstrap4\Alert::begin(['options' => ['class' => 'alert alert-success']]);
			echo yii::$app->session->getFlash('success');
		yii\bootstrap4\Alert::end();
	}
?>


<?php if($currencyList): ?>
<table class="table table-hover table-bordered">
	<thead class="thead-dark">
		<tr>
			<th scope="col">#</th>
			<th csope="col">Название</th>
			<th csope="col">Отображать на сайте</th>
			<th scope="col">Управление</th>
		</tr>
	</thead>
	<tbody>
		<?php $count = 1; ?>
		<?php foreach($currencyList as $currency): ?>
			<?php if( $currency['deleted'] == 1): ?>
				<tr class="table-danger" title="Валюта скрыта на основном сайте">
			<?php else: ?>
				<tr>
			<?php endif; ?>
				<td><?php echo $count; ?></td>
				<td>
					<?php echo $currency['name']; ?>	
				</td>
				<td>
					<?php
						if($currency['default_view'] == 1)
						{
							echo '<i class="fas fa-check text-success"></i>';
						}
					?>
				</td>	
				<td>
					<?php
						echo Html::tag('i', null, ['class' => 'far fa-edit text-secondary pointer buttonEditCurrency', 'id' => 'idForEditCurrency-' . $currency['id'], 'style' => 'margin: 0 10px 0 0;', 'data-toggle' => 'modal', 'data-target' => '.editCurrency', 'title' => 'Редактировать валюту']);
						if( $currency['deleted'] == 1 )
						{
							echo Html::tag('i', null, ['class' => 'fas fa-undo-alt text-success pointer buttonRestoreCurrency', 'id' => 'idForRestoreCurrency-' . $currency['id'], 'data-toggle' => 'modal', 'data-target' => '.restoreCurrency', 'title' => 'Восстановить валюту']);
						}
						else
						{
							echo Html::tag('i', null, ['class' => 'fas fa-trash text-danger pointer buttonDeleteCurrency', 'id' => 'idForDeleteCurrency-' . $currency['id'], 'data-toggle' => 'modal', 'data-target' => '.removeCurrency', 'title' => 'Удалить валюту']);
						}
					?>
				</td>
				<?php
					echo Html::hiddenInput(null, $currency['name'], ['class' => 'edit-name-' . $currency['id']]);
					echo Html::hiddenInput(null, $currency['default_view'], ['class' => 'edit-default_view-' . $currency['id']]);
				?>
			</tr>
		<?php $count++; ?>
		<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
	<?php echo Html::tag('div', 'Валюты еще не созданы', ['class' => 'alert alert-secondary', 'style' => 'margin-top: 10px;']);?>
<?php endif; ?>

<?php
	echo $this->render('newCurrency', compact('newModel'));
	echo $this->render('editCurrency', compact('editModel'));
	echo $this->render('deleteCurrency', compact('deleteModel'));
	echo $this->render('restoreCurrency', compact('restoreModel'));
?>