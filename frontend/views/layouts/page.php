<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap4\ActiveForm;
use common\widgets\Alert;
use yii\widgets\Menu;

AppAsset::register($this);
$orderModel = $this->params['orderModel'];
$signIn = $this->params['login'];

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="l7vcPde6uik9S7DxeNTMXZF5UFZmiCtuxJEQrL7XoO4" />
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->registerMetaTag(['name' => 'description', 'content' => Html::encode($this->params['description'])]);?>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div id="wrapper">  
        <header>
            <div class="sidebar-menu">
                <div class="header-menu">
                    <div class="slash"></div>
                    <div class="backslash"></div>
                    <div class="middle"></div>
                </div>
            </div>
            <div class="left-side">
                <ul>
                    <?php
                        echo Html::tag('li', Html::a('Главная', Url::home()));
                        foreach($this->params['pages'] as $page)
                        {
                            if($page['showheader'] == 1)
                            {
                                echo Html::tag('li', Html::a($page['title'], Url::to(['page/' . $page['sef']])));
                            }
                        }
                    ?>
                </ul>
            </div>
            <?php
                echo Html::a(Html::img(Url::to('@web/images/logo.png'), ['alt' => 'test']), Url::home(), ['class' => 'logo']);
                if(!Yii::$app->user->isGuest):
            ?>
                <div class="right-side">
                    <?php
                        $avatar = 'default_avatar.png';
                        if(!empty(Yii::$app->user->identity->image))
                            $avatar = Yii::$app->user->identity->image;
                    ?>
                    <div class="logged" style="background-image: url('<?php echo Url::to('@web/images/avatar/' . $avatar . '?' . rand()); ?>');">
                        <div class="show-profile">
                            <a href="<?php echo Url::to(['customer/index']);?>" class="link-show-profile">Настройки</a>
                            <a href="<?php echo Url::to(['myorder/index']);?>" class="link-show-profile">Мои заказы</a>
                            <?php
                                echo Html::beginForm(['/site/logout'], 'post') . Html::submitButton('Выйти', ['class' => 'logout']) . Html::endForm();
                            ?>
                        </div>
                    </div>
                    <?php if(isset($_SESSION['cart'])): ?>
                        <div class="checkout" data-toggle="modal" data-target=".compose-order">
                            <div class="count"><?php echo $_SESSION['cart']['qty']; ?></div>
                        </div>
                    <?php else: ?>
                        <div class="checkout" data-toggle="modal" data-target=".compose-order" style="display: none;">
                            <div class="count"></div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="right-side">
                    <div class="login">Войти</div>
                    <?php if(isset($_SESSION['cart'])): ?>
                        <div class="checkout" data-toggle="modal" data-target=".compose-order">
                            <div class="count"><?php echo $_SESSION['cart']['qty']; ?></div>
                        </div>
                    <?php else: ?>
                        <div class="checkout" data-toggle="modal" data-target=".compose-order" style="display: none;">
                            <div class="count"></div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </header>
        
        <div class="empty-space"></div>

        <div class="wrapper">
            <div class="sidebar sidebar-page">
                <p class="phone">(067)123-45-67</p>
                <p class="work">Пн - Пт с 9<sup>00</sup> до 18<sup>00</sup></p>
                <div class="logo-sidebar"></div>
                <?php
                    foreach($this->params['categories'] as $category)
                    {
                        $active = '';
                        if($category['parent'] == 0)
                        {
                            if($category['main'] == 1)
                                $active = '/';

                            if(isset($category['checked']))
                                $active = Url::to();
                            $items[] = [
                                    'label' => $category['title'], 
                                    'url' => ['category/index', 'sef' => $category['sef']],
                                    'template' => '<a href="{url}" style="background-image: url(' . Url::to('@web/images/category/'. $category['image']) . ');">{label}</a>',
                                    'active' => in_array(
                                                    Url::to(), [
                                                            Url::to(['category/index', 'sef' => $category['sef']]), 
                                                            $active
                                                        ]
                                                    ),
                                ];
                        }
                    }
                    echo Menu::widget([
                        'options' => [
                            'tag' => 'nav',
                        ],
                        'items' => $items,
                    ]);
                ?>

                <ul class="sidebar-main-menu">
                    <?php
                        echo Html::tag('li', Html::a('Главная', Url::home()));
                        foreach($this->params['pages'] as $page)
                        {
                            if($page['showheader'] == 1)
                            {
                                echo Html::tag('li', Html::a($page['title'], Url::to(['page/' . $page['sef']])));
                            }
                        }
                    ?>
                </ul>
            </div>
            <?php echo $content ?>

        </div><!--Wrapper-->

        <footer>
            <section style="text-align: center;">
                <?php echo Html::img(Url::to('@web/images/logo_footer.png'), ['alt' => 'Logo', 'class' => 'footer-logo']); ?>
                <p>&copy; <?php echo date('Y'); ?> Pizza Mia</p>
                <div class="social">
                    <?php
                        echo Html::a(Html::img(Url::to('@web/images/instagram.png'), ['alt' => 'Instagram']), Url::home(), ['target' => '_blank']);
                        echo Html::a(Html::img(Url::to('@web/images/facebook.png'), ['alt' => 'Facebook']), Url::home(), ['target' => '_blank']);
                    ?>
                </div>
            </section>

            <section>
                <h6>Наше меню</h6>
                <ul>
                    <?php
                        foreach($this->params['categories'] as $category)
                        {
                            if($category['parent'] == 0)
                            {
                                echo Html::tag('li', Html::a($category['title'], Url::to(['category/' . $category['sef']])));
                            }
                        }
                    ?>
                </ul>
            </section>

            <section>
                <h6>Информация</h6>
                <ul>
                    <?php 
                        foreach($this->params['pages'] as $page)
                        {
                            if($page['showfooter'] == 1)
                            {
                                echo Html::tag('li', Html::a($page['title'], Url::to(['page/' . $page['sef']])));
                            }
                        }
                    ?>
                </ul>
            </section>

            <section>
                <h6>Мы на карте</h6>
                <div style="width: 100%; height: 10px;"></div>
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d503.7237921670494!2d30.48846557710992!3d50.48562609767842!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sru!2sua!4v1568883382598!5m2!1sru!2sua" width="90%" height="200" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
            </section>
        </footer>

        <!--Additional crap-->

        <div class="fadeall"></div>

        <!--<div class="form-add-to-cart">
            <img src="images/size30.png" />
        </div>-->

        <!-- Large modal -->
        <div class="modal fade compose-order" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Оформление заказа</h3>
                        <button type="button" class="close closeOrderList" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php
                            $form = ActiveForm::begin(['options' => ['id' => 'make-order'], 'enableAjaxValidation' => true, 'validationUrl' => Url::to(['customer/ordervalidate'])]); 
                        ?>
                        <div class="container">
                            <div class="row">
                                <div class="col orderlist">
                                    <?php 
                                        if(isset($_SESSION['cart'])): 
                                            foreach($_SESSION['cart']['list'] as $list):
                                    ?>
                                        <div class="list list<?php echo $list['id_option']; ?>">
                                            <div class="img" style="background-image: url(<?php echo $list['product_image']; ?>);"></div>
                                            <div class="link">
                                                <a href="#"><?php echo $list['product_name']; ?></a>
                                                <p><?php echo $list['option_name']; ?></p>
                                            </div>
                                            <div class="amount">
                                                <div class="down" option="<?php echo $list['id_option']; ?>" product="<?php echo $list['id_product']; ?>">-</div>
                                                <div class="number"><?php echo $list['qty']; ?></div>
                                                <div class="up" option="<?php echo $list['id_option']; ?>" product="<?php echo $list['id_product']; ?>">+</div>
                                            </div>
                                            <div class="value">
                                                <span class="cost"><?php echo $list['price']; ?></span> 
                                                <span class="currency"><?php echo $this->params['currency']['name']; ?></span>
                                            </div>
                                        </div>
                                    <?php 
                                            endforeach;
                                        endif; 
                                    ?>
                                </div>
                                
                                <div class="col borders">
                                    <?php
                                        if(Yii::$app->user->isGuest)
                                        {
                                            $data = [
                                                'name' => '',
                                                'email' => '',
                                                'phone' => '',
                                                'anotherPhone' => '',
                                                'address' => '',
                                                'additional' => '',
                                            ];
                                        }
                                        else
                                        {
                                            $data = [
                                                'name' => Yii::$app->user->identity->username,
                                                'phone' => Yii::$app->user->identity->phone,
                                                'anotherPhone' => Yii::$app->user->identity->another_phone,
                                                'address' => Yii::$app->user->identity->address,
                                                'additional' => Yii::$app->user->identity->additional,
                                            ];

                                            if(!Yii::$app->user->identity->order_email)
                                            {
                                                $data['email'] = Yii::$app->user->identity->email;
                                            }
                                            else
                                            {
                                                $data['email'] = Yii::$app->user->identity->order_email;
                                            }
                                        }
                                        
                                        echo $form->field($orderModel, 'name')->textInput(['value' => $data['name'], 'class' => 'form-control form-control-sm']);
                                        echo $form->field($orderModel, 'email')->textInput(['value' => $data['email'], 'class' => 'form-control form-control-sm']);
                                        echo $form->field($orderModel, 'phone')->textInput(['value' => $data['phone'], 'class' => 'form-control form-control-sm mask-phone']);
                                        echo $form->field($orderModel, 'anotherPhone')->textInput(['value' => $data['anotherPhone'], 'class' => 'form-control form-control-sm mask-phone']);
                                        echo $form->field($orderModel, 'address')->textarea(['value' => $data['address'], 'class' => 'form-control form-control-sm']);
                                        echo $form->field($orderModel, 'additional')->textarea(['value' => $data['additional'], 'class' => 'form-control form-control-sm']);

                                    ?>
                                </div>
                                <div class="col radio">
                                    <?php

                                        echo $form->field($orderModel, 'orderDelivery')->radioList($this->params['deliveryList'], [
                                        'item' => function($index, $label, $name, $checked, $value) {
                                            
                                            $checked = '';
                                            if(isset($_SESSION['cart']) && $_SESSION['cart']['delivery']['id'] == $value)
                                                $checked = 'checked';

                                            $return = '<div class="custom-control custom-radio">';
                                            $return .= '<input type="radio" id="delivery' . $value . '" class="custom-control-input" name="' . $name . '" value="' . $value . '" ' . $checked . '>';
                                            $return .= '<label class="custom-control-label selectDelivery" iddelivery="' . $value . '" for="delivery' . $value . '">' . $label . '</label>';
                                            $return .= '<div class="invalid-feedback hide-feedback-' . $index . '"></div>';
                                            $return .= '</div>';
                                            return $return;
                                        }])->label(false);

                                        echo Html::a('<i class="fas fa-info-circle"></i> Информация о доставке', ['page/delivery'], ['class' => 'delivery-info', 'target' => '_blank']);

                                        echo $form->field($orderModel, 'orderDate')->hiddenInput(['placeholder ' => 'Дата доставки', 'class' => 'form-control datepicker-here form-control-sm', 'data-timepicker' => 'true'])->label(false);

                                        echo $form->field($orderModel, 'orderPay')->radioList($this->params['payList'])->label(false);

                                        if(!empty($this->params['couponList']))
                                            echo $form->field($orderModel, 'couponList')->radioList($this->params['couponList'], [
                                                'item' => function($index, $label, $name, $checked, $value) {
                                                    
                                                    $checked = '';
                                                    if(isset($_SESSION['cart']) && $_SESSION['cart']['discount']['id'] == $value)
                                                        $checked = 'checked';

                                                    $return = '<div class="custom-control custom-radio" ch="' . rand() . '">';
                                                    $return .= '<input type="radio" id="coupon' . $value . '" class="custom-control-input" name="' . $name . '" value="' . $value . '" ' . $checked . '>';
                                                    $return .= '<label class="custom-control-label selectCoupon" idcoupon="' . $value . '" for="coupon' . $value . '">' . $label . '</label>';
                                                    $return .= '</div>';
                                                    return $return;
                                                }])->label(false);

                                    ?>

                                    <h6>Заказ: 
                                        <span class="orderSum">
                                            <?php 
                                                if(isset($_SESSION['cart'])) 
                                                    echo $_SESSION['cart']['sum'] * (100 - $_SESSION['cart']['discount']['value']) / 100; ?>
                                        </span> 
                                        <?php echo $this->params['currency']['name']; ?>
                                    </h6>
                                    <span class="badge badge-danger" style="font-weight: 400;">Без скидки: 
                                        <span class="orderSumWithoutDiscount">
                                            <?php 
                                                if(isset($_SESSION['cart'])) 
                                                    echo $_SESSION['cart']['sum']; ?>
                                        </span> 
                                        <?php echo $this->params['currency']['name']; ?>
                                    </span>
                                    <h6>Доставка: 
                                        <span class="orderDelivery">
                                            <?php 
                                                if(isset($_SESSION['cart']))
                                                    echo $_SESSION['cart']['delivery']['value'];
                                            ?>
                                        </span> <?php echo $this->params['currency']['name']; ?>
                                    </h6>
                                    <h6 class="modal-order">Итого: 
                                        <span class="orderAll">
                                            <?php 
                                                if(isset($_SESSION['cart'])) 
                                                    echo $_SESSION['cart']['sum'] * (100 - $_SESSION['cart']['discount']['value']) / 100 + $_SESSION['cart']['delivery']['value'];
                                            ?>
                                        </span> <?php echo $this->params['currency']['name']; ?>
                                    </h6>
                                    <?php 
                                        echo Html::submitButton('Сделать заказ', ['class' => 'btn btn-success btn-block'/*, 'data-toggle' => 
                                            'modal', 'data-target' => '.compose-order1', 'data-dismiss' => 'modal'*/]);
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>

        <?php
            $fade = '';
            $login = '';
            if(Yii::$app->session->getFlash('user'))
            {
                $fade = 'style="display: block;"';
                $login = 'style="top: 100px;"';
            }
        ?>

        <div class="fadeall" <?php echo $fade; ?>></div>


        <?php if(Yii::$app->user->isGuest): ?>
        <!--Login form-->
        <div class="login-form" <?php echo $login; ?>>
            <div class="login-form-close">+</div>
            <div class="login-form-header">
                <h5>We believe in pizza</h5>
            </div>
            <div class="login-form-ways">
                <h6>Выберите способ авторизации</h6>
                <?php $form = ActiveForm::begin(['options' => ['id' => 'login-form']]); ?>

                    <?php echo $form->field($signIn, 'email')->textInput(['class' => 'form-control form-control-sm']) ?>

                    <?php echo $form->field($signIn, 'password')->passwordInput(['class' => 'form-control form-control-sm']) ?>

                    <?php //echo $form->field($signIn, 'rememberMe')->checkbox() ?>

                    <?php //echo  Html::a('Resend', ['site/resend-verification-email']) ?>
                    
                    <div class="button-login">
                        <?php echo Html::submitButton('Войти', ['class' => 'btn btn-primary btn-sm', 'name' => 'login-button']) ?>
                    
                        <?php echo  Html::a('Забыли пароль', ['user/resetpassword']) ?>

                        <?php echo  Html::a('Регистрация', ['user/signup']) ?>
                    </div>

                <?php ActiveForm::end(); ?>

                <p class="or">Или</p>

                <?= yii\authclient\widgets\AuthChoice::widget([
                     'baseAuthUrl' => ['site/auth'],
                     'popupMode' => false,
                ]) ?>

                <?php
                    if(Yii::$app->session->getFlash('user'))
                    {
                        echo '<div class="wrong-user">' . Yii::$app->session->getFlash('user') . '</div>';
                    }
                ?>
            </div>
        </div>
        <?php endif;?>

        <?php
            if(Yii::$app->session->getFlash('orderSuccess')):
                $this->registerJs('$(document).ready(function(){
                                    $(".ordered").modal("show");
                                });');
        ?>
        <div class="modal show fade ordered" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content ">
              <div class="modal-header">
                <h5 class="modal-title">Заказ успешно оформлен</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <label>Номер заказа:</label>
                            <p class="text-info"><?php echo Yii::$app->session->getFlash('orderId'); ?></p>
                            <label>Контактное лицо:</label>
                            <p class="text-info"><?php echo Yii::$app->session->getFlash('orderName'); ?></p>
                            <label>Номер телефона:</label>
                            <p class="text-info"><?php echo Yii::$app->session->getFlash('orderPhone'); ?></p>
                            <label>Дополнительный номер телефона:</label>
                            <?php if(empty(Yii::$app->session->getFlash('orderAnotherPhone'))): ?>
                                <p class="badge badge-light">
                                    Нет информации
                                </p>
                            <?php else: ?>
                                <p class="text-info">
                                    <?php echo Yii::$app->session->getFlash('orderAnotherPhone'); ?>
                                </p>
                            <?php endif; ?>
                            <label>Способ оплаты:</label>
                            <p class="text-info"><?php echo Yii::$app->session->getFlash('orderPay'); ?></p>
                            <label>Способ доставки:</label>
                            <p class="text-info"><?php echo Yii::$app->session->getFlash('orderDelivery'); ?></p>
                            <label>Адрес доставки:</label>
                            <?php if(empty(Yii::$app->session->getFlash('orderAddress'))): ?>
                                <p class="badge badge-light">
                                    Нет информации
                                </p>
                            <?php else: ?>
                                <p class="text-info">
                                    <?php echo Yii::$app->session->getFlash('orderAddress'); ?>
                                </p>
                            <?php endif; ?>
                            <label>Дополнительная информация:</label>
                            <?php if(empty(Yii::$app->session->getFlash('orderAdditional'))): ?>
                                <p class="badge badge-light">
                                    Нет информации
                                </p>
                            <?php else: ?>
                                <p class="text-info">
                                    <?php echo Yii::$app->session->getFlash('orderAdditional'); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="col">
                            <p>Информацию с номером заказа мы отправили вам с помощью SMS</p>
                            <?php if(!empty(Yii::$app->session->getFlash('orderEmail'))): ?>
                                <p>Полную информацию о заказе мы отправили вам на ваш e-mail (<?php echo Yii::$app->session->getFlash('orderEmail'); ?>).</p>
                            <?php endif; ?>
                            <p>Мы свяжемся с вами в ближайшее время.</p>
                        </div>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <div class="added-to-cart">
          Товар успешно добавлен в корзину.
        </div>

        <?php echo Html::hiddenInput('currencyValue', $this->params['currency']['name'], ['class' => 'default_currency']); ?>

<?php $this->endBody() ?>
</div>
</body>
</html>
<?php $this->endPage() ?>
