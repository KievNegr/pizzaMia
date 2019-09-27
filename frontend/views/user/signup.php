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
			<div class="register-block">
				<div class="classic-register">
					<?php 
						$form = ActiveForm::begin(['id' => 'form-signup']);

	                	echo $form->field($signup, 'username')->textInput();

	                	echo $form->field($signup, 'email');

	                	echo $form->field($signup, 'password')->passwordInput();
	                ?>

	                <div class="form-group">
	                    <?= Html::submitButton('Зарегистрироватся', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
	                </div>

	            	<?php ActiveForm::end(); ?>
	            </div>
	            <div class="modern-register">
	            	<p>Вы можете зарегистрироватся на сайте с помощью социальных сетей</p>
	            	<?= yii\authclient\widgets\AuthChoice::widget([
	                     'baseAuthUrl' => ['site/auth'],
	                     'popupMode' => false,
	                ]) ?>
	            </div>
			</div>
		<?php endif; ?>
	</div>
</main>