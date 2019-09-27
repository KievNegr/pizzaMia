<?php
	use yii\bootstrap4\ActiveForm;
	use yii\helpers\Html;
?>
<main>
    <div class="restore">
		<h2><?php echo $this->title; ?></h2>
		<?php if(Yii::$app->session->getFlash('success')): ?>
			<div class="alert alert-success" style="margin-top: 15px;" role="alert"><?php echo Yii::$app->session->getFlash('success'); ?></div>
		<?php elseif(Yii::$app->session->getFlash('error')): ?>
			<div class="alert alert-danger" style="margin-top: 15px;" role="alert"><?php echo Yii::$app->session->getFlash('error'); ?></div>
		<?php else: ?>
			<div class="restore-block">
				<p>Для восстановления пароля введите свой e-mail, с которым Вы регистрировались на сайте.</p>
				<?php 
					$form = ActiveForm::begin(['id' => 'request-password-reset-form']);

					echo $form->field($reset, 'email')->textInput()->label(false);
					echo Html::submitButton('Отправить', ['class' => 'btn btn-primary send-reset']);

					ActiveForm::end(); 
				?>
			</div>
		<?php endif; ?>
	</div>
</main>