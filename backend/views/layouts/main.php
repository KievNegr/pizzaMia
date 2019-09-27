<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\Alert;
use yii\widgets\Menu;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <div id="wrapper">
<?php $this->beginBody() ?>
<header>
    <div class="left-side">
        <h5>Привет, <?php echo Yii::$app->user->identity->username; ?></h5>
    </div>
    <?php echo Html::a(Html::img(Url::to('images/logo.png'), ['alt' => 'Logo']), Url::home(), ['class' => 'logo']); ?>
    <div class="right-side">
        <?php
            $avatar = 'default_avatar.png';
            if(!empty(Yii::$app->user->identity->image))
                $avatar = Yii::$app->user->identity->image;
        ?>
        <div class="logged" style="background-image: url('../../frontend/web/images/avatar/<?php echo $avatar . '?' . rand(); ?>');">
            <div class="show-profile">
                <?php
                    echo Html::a('На сайт', Yii::$app->request->getHostInfo(), ['class' => 'tomain', 'target' => '_blank']);
                    echo Html::beginForm(['/site/logout'], 'post') . Html::submitButton('Выйти', ['class' => 'logout']) . Html::endForm();
                ?>
            </div>
        </div>
    </div>
</header>
<div class="empty-space"></div>
<div class="wrapper">
    <div class="sidebar">
        <?php
            $visible = false;
            if(Yii::$app->user->identity->level == 1)
                $visible = true;

            echo Menu::widget([
                'options' => [
                    'tag' => 'nav',
                ],
                'items' => [
                        ['label' => 'Статистика', 'url' => ['site/index']],
                        ['label' => 'Администраторы', 'url' => ['user/index'], 'active' => in_array($this->context->route, ['user/index', 'user/create', 'user/edit'])],
                        ['label' => 'Заказы', 'url' => ['order/index']],
                        ['label' => 'Категории', 'url' => ['category/index'], 'active' => in_array($this->context->route, ['category/index', 'category/create', 'category/edit'])],
                        ['label' => 'Товары', 'url' => ['goods/index']],
                        ['label' => 'Опции товаров', 'url' => ['option/index']],
                        ['label' => 'Ингредиенты', 'url' => ['ingredient/index']],
                        ['label' => 'Страницы', 'url' => ['page/index']],
                        ['label' => 'Клиенты', 'url' => ['customer/index'], 'active' => in_array($this->context->route, ['customer/index', 'customer/info', 'customer/order'])],
                        ['label' => 'Настройки', 'url' => ['setting/index'], 'active' => in_array($this->context->route, ['setting/index', 'setting/promo', 'setting/order', 'setting/loyalty', 'setting/pay', 'setting/delivery', 'setting/sitemap', 'setting/clearing', 'setting/currency']), 'visible' => $visible],
                        
                        //['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                ],
            ]);
        ?>
    </div>
    <main>
        <?php echo $content ?>
    </main>
</div><!--Wrapper-->

<footer>
    <section style="text-align: center;">
        <img src="images/logo_footer.png" alt="Logo" class="footer-logo" />
        <p>&copy; 2019 Pizza Mia</p>
        <div class="social">
            <a href="#" target="_blank"><img src="images/instagram.png" alt="Instagram" /></a>
            <a href="#" target="_blank"><img src="images/facebook.png" alt="Facebook" /></a>
        </div>
    </section>

    <section>
        <h6>Наше меню</h6>
        <ul>
            <li><a href="#">Пицца</a></li>
            <li><a href="#">Напитки</a></li>
            <li><a href="#">Супы</a></li>
            <li><a href="#">Салаты</a></li>
            <li><a href="#">Десерты</a></li>
        </ul>
    </section>

    <section>
        <h6>Информация</h6>
        <ul>
            <li><a href="#">Доставка и оплата</a></li>
            <li><a href="#">Сотрудничество</a></li>
            <li><a href="#">Накопительная программа</a></li>
            <li><a href="#">Скидки и акции</a></li>
            <li><a href="#">Контакты</a></li>
        </ul>
    </section>

    <section>
        <h6>Мы на карте</h6>
        <div style="width: 100%; height: 10px;"></div>
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d503.7237921670494!2d30.48846557710992!3d50.48562609767842!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sru!2sua!4v1568883382598!5m2!1sru!2sua" width="90%" height="200" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
    </section>
</footer>

<?php $this->endBody() ?>
</div>
</body>
</html>
<?php $this->endPage() ?>
