<?php

	use yii\helpers\Html;
	use yii\helpers\Url;

	$this->registerJsFile('@web/js/user.js', ['depends' => 'yii\web\JqueryAsset']); //Управление пользователем (удаление, редактирование ...)
?>

<h2>Администраторы</h2>
<?php 
	if(yii::$app->session->getFlash('success'))
	{
		yii\bootstrap4\Alert::begin(['options' => ['class' => 'alert alert-success']]);
			echo yii::$app->session->getFlash('success');
		yii\bootstrap4\Alert::end();
	}
?>
<table class="table table-hover table-bordered">
	<thead class="thead-dark">
		<tr>
			<th scope="col">#</th>
			<th scope="col">Фото</th>
			<th scope="col">Имя</th>
			<th scope="col">Телефон</th>
			<th scope="col">E-mail</th>
			<th scope="col">Уровень доступа</th>
			<?php if(Yii::$app->user->identity->level == 1):?>
			<th scope="col">Управление</th>
			<?php endif; ?>
		</tr>
	</thead>
	<tbody>
		<?php 
			$count = 1;
			foreach($users as $user):
		?>
		<?php if( $user['status'] == 9): ?>
				<tr class="table-danger" title="Пользователь неактивен">
			<?php else: ?>
				<tr>
			<?php endif; ?>
			<th scope="row" class="align-middle"><?php echo $count; ?></th>
			<td class="align-middle">
				<?php
					if(empty($user['image']))
						$user['image'] = 'default_avatar.png';

					echo Html::img($path . $user['image'] . '?', ['class' => 'tableAvatar']); 
				?>
			</td>
			<td class="align-middle"><?php echo $user['username']; ?></td>
			<td class="align-middle">
				<?php 
					echo $user['phone'];
					if(!empty($user['another_phone']))
						echo '<br />' . $user['another_phone'];
				?>
			</td>
			<td class="align-middle"><?php echo $user['email']; ?></td>
			<td class="align-middle">
				<?php
					foreach ($levels as $level) {
						if($level['id'] == $user['level'])
							echo $level['level_name'];
					}
				?>
			</td>
			<?php if(Yii::$app->user->identity->level == 1):?>
			<td class="align-middle">
				<?php
					echo Html::tag('i', null, ['class' => 'far fa-edit text-secondary pointer buttonEditUser', 'id' => 'idForEditUser-' . $user['id'], 'style' => 'margin: 0 10px 0 0;', 'data-toggle' => 'modal', 'data-target' => '#editUser', 'title' => 'Редактировать пользователя']);

					if( $user['status'] == 9 )
					{
						echo Html::tag('i', null, ['class' => 'fas fa-undo-alt text-success pointer buttonRestoreUser', 'id' => 'idForRestoreUser-' . $user['id'], 'data-toggle' => 'modal', 'data-target' => '#restoreUser', 'title' => 'Восстановить пользователя']);
					}
					else
					{
						echo Html::tag('i', null, ['class' => 'fas fa-trash text-danger pointer buttonRemoveUser', 'id' => 'idForDeleteUser-' . $user['id'], 'data-toggle' => 'modal', 'data-target' => '#deleteUser', 'title' => 'Удалить пользователя']);
					}
				?>	
			</td>
			<?php endif; ?>
			<?php
				if(Yii::$app->user->identity->level == 1)
				{
					echo Html::hiddenInput(null, $user['phone'], ['class' => 'editPhone-' . $user['id']]);
					echo Html::hiddenInput(null, $user['another_phone'], ['class' => 'editAnotherPhone-' . $user['id']]);
					echo Html::hiddenInput(null, $user['username'], ['class' => 'editUserName-' . $user['id']]);
					echo Html::hiddenInput(null, $user['email'], ['class' => 'editEmail-' . $user['id']]);

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

					$size = getimagesize($path . $user['image']);
					$class = substr($user['image'], 0, -4);

					$data = '<div class="image ' . $class . '" style="background-image: url(' . $path . $user['image'] . '?' . ');">';

					if($user['image'] != 'default_avatar.png')
					{
						$data .= '<div class="imageRun" nameImage="' . $user['image'] . '">
										<i class="fas fa-crop-alt imageCrop" particularclass="' . $class . '" address="' . $path . $user['image'] . '" imageWidth="' . $size[0] . '" imageHeight="' . $size[1] . '"  title="Кадрировать изображение"></i>
										<i class="fas fa-trash-alt deleteImage" userid="' . $user['id'] . '"></i>
									</div>';
					}
					$data .= '</div>';

					echo Html::hiddenInput(null, $data, ['class' => 'editImage-' . $user['id']]);
					echo Html::hiddenInput(null, $user['image'], ['class' => 'editNameImage-' . $user['id']]);
				}
			?>
		</tr>
		<?php 
			$count++;
			endforeach;
		?>
	</tbody>
</table>

<?php
	if(Yii::$app->user->identity->level == 1)
	{
		echo $this->render('modalEdit', compact('editModel', 'levels', 'userAvatar'));
		echo $this->render('modalRemove', compact('removeModel'));
		echo $this->render('modalRestore', compact('restoreModel'));
		echo $this->render('makeAvatar');
	}
?>