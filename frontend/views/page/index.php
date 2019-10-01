<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
?>
<main class="page">
	<?php
		echo Html::tag('div', Html::tag('h1', $page['title']), ['class' => 'header']);
		echo $page['text'];
	?>
</main>