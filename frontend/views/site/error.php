<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $name;
?>
<div class="site-error">
	<?php echo Html::img(Url::to('@web/images/logo_footer.png'), ['alt' => 'Logo', 'class' => 'footer-logo']); ?>
    <h3><?= Html::encode($this->title) ?></h3>
    <p>Произошла ошибка.</p>
    <?php echo Html::a('На главную страницу', Yii::$app->request->getHostInfo(), ['class' => 'tomain', 'target' => '_blank']);?>
</div>
