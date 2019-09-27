<?php
	use yii\helpers\Html;
	use yii\helpers\Url;
	$this->registerJsFile('@web/js/customer.js', ['depends' => 'yii\web\JqueryAsset']);
?>

<?php echo Html::tag('h2', 'Список клиентов'); ?>

<?php 
	if(yii::$app->session->getFlash('success'))
	{
		yii\bootstrap4\Alert::begin(['options' => ['class' => 'alert alert-success']]);
			echo yii::$app->session->getFlash('success');
		yii\bootstrap4\Alert::end();
	}
?>

<?php if($userList): ?>
<table class="table table-hover table-bordered">
	<thead class="thead-dark">
		<tr>
			<th scope="col">#</th>
			<th csope="col">Имя</th>
			<th scope="col">Телефон</th>
			<th scope="col">E-mail</th>
			<th scope="col">Уровень доступа</th>
			<?php if(Yii::$app->user->identity->level == 1): ?>
				<th scope="col">Управление</th>
			<?php endif; ?>
		</tr>
	</thead>
	<tbody>
		<?php $count = 1; ?>
		<?php foreach($userList as $user): ?>
				<tr>
				<td><?php echo $count; ?></td>
				<td>
					<?php echo Html::a($user['username'], Url::to(['info', 'id' => $user['id']])); ?>	
				</td>	
				<td>
					<?php echo $user['phone'] . '<br />' . $user['another_phone']; ?>	
				</td>
				<td>
					<?php echo $user['email']; ?>	
				</td>
				<td>
					<?php
						foreach ($levels as $level) {
							if($level['id'] == $user['level'])
								echo $level['level_name'];
						}
					?>
				</td>
				<?php if(Yii::$app->user->identity->level == 1): ?>
					<td>
						<?php
							echo Html::tag('i', null, ['class' => 'far fa-edit text-secondary pointer buttonEditCustomer', 'id' => 'idForEditCustomer-' . $user['id'], 'style' => 'margin: 0 10px 0 0;', 'data-toggle' => 'modal', 'data-target' => '.editCustomer', 'title' => 'Редактировать клиента']);
						?>
					</td>
				<?php endif; ?>
				<?php
					if(Yii::$app->user->identity->level == 1)
					{

						echo Html::hiddenInput(null, $user['username'], ['class' => 'edit-username-' . $user['id']]);
						echo Html::hiddenInput(null, $user['phone'], ['class' => 'edit-phone-' . $user['id']]);
						echo Html::hiddenInput(null, $user['another_phone'], ['class' => 'edit-another_phone-' . $user['id']]);
						echo Html::hiddenInput(null, $user['email'], ['class' => 'edit-email-' . $user['id']]);
						echo Html::hiddenInput(null, $user['level'], ['class' => 'edit-level-' . $user['id']]);

						$levelList = '<div class="btn-group btn-group-toggle" data-toggle="buttons" role="radiogroup" aria-required="true">';
						foreach ($levels as $level) {
							$active = '';
							if($level['id'] == $user['level'])
								$active = 'active';
							$levelList .= '<label class="btn btn-info form-check-label setLevel ' . $active . '" level="' . $level['id'] . '">
												<input type="radio" name="userlevel" value="' . $level['id'] . '" class="form-check-label">' . $level['level_name'] . '
											</label>';
						}
						$levelList .= '</div>';
							
						echo Html::hiddenInput(null, $user['level'], ['class' => 'editLevel-' . $user['id']]);
						echo Html::hiddenInput(null, $levelList, ['class' => 'editLevelList-' . $user['id']]);
					}
				?>
			</tr>
		<?php $count++; ?>
		<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
	<?php echo Html::tag('div', 'Зарегистрированых пользователей еще нет', ['class' => 'alert alert-secondary', 'style' => 'margin-top: 10px;']);?>
<?php endif; ?>

<?php
	if(Yii::$app->user->identity->level == 1)
		echo $this->render('modalEdit', compact('editModel'));
?>