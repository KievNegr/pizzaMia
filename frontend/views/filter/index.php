<?php
	use yii\widgets\ActiveForm;
	use yii\widgets\ActiveField;
	use yii\helpers\Html;
?>

<?php $form = ActiveForm::begin(['options' => ['id' => 'testForm']]);?>
	
	<?php echo $form->field($model, 'name'); ?>
	<?php echo $form->field($model, 'email')->input('email'); ?>
	<?php echo $form->field($model, 'text')->textarea(); ?>
	<?php echo Html::submitButton('Send'); ?>

<?php ActiveForm::end(); ?>