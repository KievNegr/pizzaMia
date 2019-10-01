<?php
	use yii\helpers\Html;
	use yii\helpers\Url;
	use yii\helpers\BaseFileHelper;
	$this->registerJsFile('@web/js/clearing.js', ['depends' => 'yii\web\JqueryAsset']);

	echo Html::a('<i class="fas fa-angle-left"></i> Настройки</a>', Url::to(['setting/index']), ['class' => 'btn btn-link btn-sm', 'role' => 'button', 'aria-pressed' => 'true']);

	echo Html::tag('h2', 'Очистка и оптимизация');
?>

<table class="table">
  <thead>
    <tr>
      <th scope="col">Раздел сайта</th>
      <th scope="col">Изображений в базе</th>
      <th scope="col">Изображений в папке</th>
      <th scope="col">Управление</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th>Категории</th>
      <td><?php echo $db = count($categoryDB); ?></td>
      <td class="category-fl"><?php echo $fl = count(BaseFileHelper::findFiles(Url::to('@frontend/web/images/category'))); ?></td>
      <td class="optimization category-good">
      	<?php
      		if($fl > $db)
      		{
      			echo Html::button('Оптимизировать', ['class' => 'optimize-category btn btn-warning btn-sm']);
      		}
      		else
      		{
      			echo Html::tag('span', 'Все в порядке', ['class' => 'btn btn-success btn-sm']);
      		}
      	?>
      	<div class="spinner-border text-info for-category" role="status"></div>
      </td>
    </tr>
    <tr>
      <th>Страницы</th>
      <td><?php echo $db = count($pageDB); ?></td>
      <td class="page-fl"><?php echo $fl = count(BaseFileHelper::findFiles(Url::to('@frontend/web/images/page'))); ?></td>
      <td class="optimization page-good">
      	<?php
      		if($fl > $db)
      		{
      			echo Html::button('Оптимизировать', ['class' => 'optimize-page btn btn-warning btn-sm']);
      		}
      		else
      		{
      			echo Html::tag('span', 'Все в порядке', ['class' => 'btn btn-success btn-sm']);
      		}
      	?>
      	<div class="spinner-border text-info for-page" role="status"></div>
      </td>
    </tr>
    <tr>
      <th>Опции</th>
      <td><?php echo $db = count($optionDB); ?></td>
      <td class="option-fl"><?php echo $fl = count(BaseFileHelper::findFiles(Url::to('@frontend/web/images/option'))); ?></td>
      <td class="optimization option-good">
      	<?php
      		if($fl > $db)
      		{
      			echo Html::button('Оптимизировать', ['class' => 'optimize-option btn btn-warning btn-sm']);
      		}
      		else
      		{
      			echo Html::tag('span', 'Все в порядке', ['class' => 'btn btn-success btn-sm']);
      		}
      	?>
      	<div class="spinner-border text-info for-option" role="status"></div>
      </td>
    </tr>
    <tr>
      <th>Ингредиенты</th>
      <td><?php echo $db = count($ingredientDB); ?></td>
      <td class="ingredient-fl"><?php echo $fl = count(BaseFileHelper::findFiles(Url::to('@frontend/web/images/ingredient'))); ?></td>
      <td class="optimization ingredient-good">
      	<?php
      		if($fl > $db)
      		{
      			echo Html::button('Оптимизировать', ['class' => 'optimize-ingredient btn btn-warning btn-sm']);
      		}
      		else
      		{
      			echo Html::tag('span', 'Все в порядке', ['class' => 'btn btn-success btn-sm']);
      		}
      	?>
      	<div class="spinner-border text-info for-ingredient" role="status"></div>
      </td>
    </tr>
    <tr>
      <th>Аватарки</th>
      <td><?php echo $db = count($avatarDB); ?></td>
      <td class="avatar-fl"><?php echo $fl = count(BaseFileHelper::findFiles(Url::to('@frontend/web/images/avatar'))); ?></td>
      <td class="optimization avatar-good">
      	<?php
      		if($fl > $db)
      		{
      			echo Html::button('Оптимизировать', ['class' => 'optimize-avatar btn btn-warning btn-sm']);
      		}
      		else
      		{
      			echo Html::tag('span', 'Все в порядке', ['class' => 'btn btn-success btn-sm']);
      		}
      	?>
      	<div class="spinner-border text-info for-avatar" role="status"></div>
      </td>
    </tr>
    <tr>
      <th>Товары</th>
      <td><?php echo $db = count($goodsDB); ?></td>
      <td class="goods-fl"><?php echo $fl = count(BaseFileHelper::findFiles(Url::to('@frontend/web/images/goods/thumbs'))); ?></td>
      <td class="optimization goods-good">
      	<?php
      		if($fl > $db)
      		{
      			echo Html::button('Оптимизировать', ['class' => 'optimize-goods btn btn-warning btn-sm']);
      		}
      		else
      		{
      			echo Html::tag('span', 'Все в порядке', ['class' => 'btn btn-success btn-sm']);
      		}
      	?>
      	<div class="spinner-border text-info for-goods" role="status"></div>
      </td>
    </tr>
  </tbody>
</table>