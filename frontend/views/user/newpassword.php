<?php
	use yii\bootstrap4\ActiveForm;
	use yii\helpers\Html;
?>	

<main>
    <div class="restore">
		<h2><?php echo $this->title; ?></h2>

		<?php if(Yii::$app->session->getFlash('success')): ?>
			<div class="alert alert-success" style="margin-top: 15px;" role="alert"><?php echo Yii::$app->session->getFlash('success'); ?></div>
		<?php else: ?>
			<div class="restore-block">
				<p>Введите новый пароль для входа на сайт.</p>
				<?php 
					$form = ActiveForm::begin(['id' => 'reset-password-form']);

		    		echo $form->field($newPassword, 'password')->passwordInput()->label(false);
					echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary set-new-password']);

					ActiveForm::end();
				?>
			</div>
		<?php endif; ?>
	</div>
</main>