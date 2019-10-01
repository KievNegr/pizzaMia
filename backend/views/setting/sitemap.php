<?php
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\bootstrap4\ActiveForm;
	
	echo Html::a('<i class="fas fa-angle-left"></i> Настройки</a>', Url::to(['setting/index']), ['class' => 'btn btn-link btn-sm', 'role' => 'button', 'aria-pressed' => 'true']);

	$form = ActiveForm::begin(['action' => Url::to(['setting/sitemap'])]);
		echo Html::submitButton('Создать Sitemap', ['class' => 'btn btn-success right', 'name' => 'btn-sitemap']);
	ActiveForm::end();

	echo Html::tag('h2', 'Управление картой сайта');

	if(yii::$app->session->getFlash('success'))
	{
		yii\bootstrap4\Alert::begin(['options' => ['class' => 'alert alert-success']]);
			echo yii::$app->session->getFlash('success');
		yii\bootstrap4\Alert::end();
	}
?>
<div class="sitemap">
	<div class="container">
		<div class="row">
			<div class="col" style="text-align: center;">
				<p class="h6">Sitemap.xml</p>
				<p class="h1">
					<?php 
						if($mainExist == 1)
						{
							echo '<i class="fas fa-check text-success"></i>';
						}
						else
						{
							echo '<i class="fas fa-times text-danger"></i>';
						}
					?>
				</p>
			</div>
			<div class="col" style="text-align: center;">
				<p class="h6">Sitemap-categories.xml</p>
				<p class="h1">
					<?php 
						if($categoryExist == 1)
						{
							echo '<i class="fas fa-check text-success"></i>';
						}
						else
						{
							echo '<i class="fas fa-times text-danger"></i>';
						}
					?>
				</p>
			</div>
			<div class="col" style="text-align: center;">
				<p class="h6">Sitemap-goods.xml</p>
				<p class="h1">
					<?php 
						if($goodsExist == 1)
						{
							echo '<i class="fas fa-check text-success"></i>';
						}
						else
						{
							echo '<i class="fas fa-times text-danger"></i>';
						}
					?>
				</p>
			</div>
			<div class="col" style="text-align: center;">
				<p class="h6">Sitemap-pages.xml</p>
				<p class="h1">
					<?php 
						if($pageExist == 1)
						{
							echo '<i class="fas fa-check text-success"></i>';
						}
						else
						{
							echo '<i class="fas fa-times text-danger"></i>';
						}
					?>
				</p>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col" style="text-align: center;">
				<p class="h6">Категорий</p>
				<p class="h1">
					<?php echo count($categories);?>
				</p>
			</div>
			<div class="col" style="text-align: center;">
				<p class="h6">Товаров</p>
				<p class="h1">
					<?php echo count($goods);?>
				</p>
			</div>
			<div class="col" style="text-align: center;">
				<p class="h6">Страниц</p>
				<p class="h1">
					<?php echo count($pages);?>
				</p>
			</div>
		</div>
	</div>
</div>