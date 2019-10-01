<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
?>
<main>
    <div class="header">
        <h4>Привет, <?php echo Yii::$app->user->identity->username; ?></h4>
        <div class="profile-links">
            <a href="<?php echo Url::to(['myorder/index']); ?>" class="active">Мои заказы</a>
            <a href="<?php echo Url::to(['customer/index']); ?>">Мои настройки</a>
        </div>
    </div>
    <div class="profile">
        <div class="hello">
            <?php
                $avatar = 'default_avatar.png';
                if(!empty(Yii::$app->user->identity->image))
                    $avatar = Yii::$app->user->identity->image;
            ?>                        
            <img src="<?php echo Url::to('@web/images/avatar/' . $avatar . '?' . rand()); ?>" />
            <div>
                <p><?php echo Yii::$app->user->identity->username; ?> / <?php echo Html::a('Список заказов', Url::to(['myorder/index'])); ?> / <span>Заказ №<?php echo $myOrder['id']; ?></span></p>
                <small><?php echo substr($myOrder['date'], 8, 2) . '.' . substr($myOrder['date'], 5, 2) . '.' . substr($myOrder['date'], 0, 4) . ' в ' . substr($myOrder['date'], 11, 8); ?></small>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col customer">
                    <label>Имя:</label>
                    <p class="text-info"><?php echo $myOrder['user_name']; ?></p>
                    <label>Контактный номер телефона:</label>
                    <p class="text-info"><?php echo $myOrder['user_phone']; ?></p>
                    <label>Дополнительный номер телефона:</label>
                    <?php 
                        if(empty($myOrder['user_another_phone']) || $myOrder['user_another_phone'] == 0)
                        {
                            echo '<p class="text-muted">Не указан</p>';
                        }
                        else
                        {
                            echo '<p class="text-info">' . $myOrder['user_another_phone'] . '</p>';
                        }
                    ?>
                    <label>E-mail:</label>
                    <?php 
                        if(empty($myOrder['user_email']) || $myOrder['user_email'] == NULL)
                        {
                            echo '<p class="text-muted">Не указан</p>';
                        }
                        else
                        {
                            echo '<p class="text-info">' . $myOrder['user_email'] . '</p>';
                        }
                    ?>
                    <label>Адрес доставки:</label>
                    <?php 
                        if(empty($myOrder['user_address']) || $myOrder['user_address'] == NULL)
                        {
                            echo '<p class="text-muted">Не указан</p>';
                        }
                        else
                        {
                            echo '<p class="text-info">' . $myOrder['user_address'] . '</p>';
                        }
                    ?>
                    <label>Комментарий:</label>
                    <?php 
                        if(empty($myOrder['user_comments']) || $myOrder['user_comments'] == NULL)
                        {
                            echo '<p class="text-muted">Не указан</p>';
                        }
                        else
                        {
                            echo '<p class="text-info">' . $myOrder['user_comments'] . '</p>';
                        }
                    ?>
                </div>
                
                <div class="col borders">
                    <?php foreach ($myOrder['orderlist'] as $orderlist): ?>
                        <div class="list">
                            <div class="img" style="background-image: url(<?php echo Url::to('@web/images/goods/thumbs/' . $orderlist['itemImage']['name']);?>);"></div>
                            <div class="info">
                                <?php echo Html::a($orderlist['item']['title'], Url::to(['goods/index', 'sef' => $orderlist['item']['sef']]), ['class' => 'text-primary', 'target' => '_blank']);?></a>
                                <div class="amount">
                                    <span><?php echo $orderlist['price'] . ' ' . $currency['name']; ?></span>
                                    <span><?php echo $orderlist['count'] . ' шт.'; ?></span>
                                    <span><?php echo $orderlist['price'] * $orderlist['count'] . ' ' . $currency['name']; ?></span>
                                </div>
                                <?php if($myOrder['orderStatus']['set_ratio'] == 1 && $orderlist['ratio'] == NULL): ?>
                                <div class="ratio-list ratiolist<?php echo $orderlist['id']; ?>">
                                    <div class="ratio" stars="5" item="<?php echo $orderlist['id']; ?>">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div class="ratio" stars="4" item="<?php echo $orderlist['id']; ?>">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div class="ratio" stars="3" item="<?php echo $orderlist['id']; ?>">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div class="ratio" stars="2" item="<?php echo $orderlist['id']; ?>">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div class="ratio" stars="1" item="<?php echo $orderlist['id']; ?>">
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                                <div class="set-ratio foritem<?php echo $orderlist['id']; ?>"></div>
                            <?php else: ?>
                                <div class="set-ratio">
                                    <?php
                                        for ($i = 0; $i < $orderlist['ratio']; $i++) {
                                            echo '<i class="fas fa-star"></i>';
                                        }
                                        for ($e = 0; $e < 5 - $i; $e++) {
                                            echo '<i class="far fa-star"></i>';
                                        }
                                    ?>
                                </div>
                            <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="col customer">
                    <label>Сумма заказа:</label>
                    <p class="text-info">
                        <?php
                            $sum = 0;
                            foreach ($myOrder['orderlist'] as $orderlist)
                            {
                                $sum += $orderlist['price'] * $orderlist['count'];
                            }
                            echo $sum . ' ' . $currency['name'];
                        ?>
                    </p>

                    <?php
                        $discount = Array();
                        foreach ($coupons as $coupon) {
                            if($myOrder['coupon'] == $coupon['id'])
                                $discount = $coupon;
                        }

                        if(empty($discount))
                        {
                            if(!empty($myOrder['discount']) || $myOrder['discount'] != 0)
                            {
                                echo '<label>Скидка:</label>';
                                echo '<p class="text-info">' . $myOrder['discount']['discount'] . ' %</p>';

                                echo '<label>Сумма заказа со скидкой ' . $myOrder['discount']['discount'] . ' %:</label>';
                                echo '<p class="text-info">' . $sum * (100 - $myOrder['discount']['discount']) / 100 . ' '. $currency['name'] . '</p>';

                                $sum = $sum * (100 - $myOrder['discount']['discount']) / 100;
                            }
                        }
                        else
                        {
                            echo '<label>Примененный промокод:</label>';
                            echo '<p class="text-info">' . $discount['code'] . ' (' . $discount['discount'] . ' %)</p>';

                            echo '<label>Сумма заказа со скидкой ' . $discount['discount'] . ' %:</label>';
                            echo '<p class="text-info">' . $sum * (100 - $discount['discount']) / 100 . ' '. $currency['name'] . '</p>';

                            $sum = $sum * (100 - $discount['discount']) / 100;
                        }
                    ?>
                    <label>Оплата:</label>
                    <p class="text-info">
                        <?php
                            foreach ($pay as $payWay)
                            {
                                if($payWay['id'] == $myOrder['pay'])
                                    $myPay = $payWay;
                            }
                            echo $payWay['name'];
                        ?>
                    </p>
                    <label>Доставка:</label>
                    <p class="text-info">
                        <?php
                            foreach ($delivery as $deliveryWay)
                            {
                                if($deliveryWay['id'] == $myOrder['delivery'])
                                    $myDelivery = $deliveryWay;
                            }
                            echo $myDelivery['name'];
                        ?>
                    </p>
                    <label>Стоимость доставки:</label>
                    <p class="text-info">
                        <?php echo $myDelivery['value'] . ' '. $currency['name']; ?>
                    </p>
                    <label>К оплате:</label>
                    <p class="text-success">
                        <?php echo $sum + $myDelivery['value'] . ' '. $currency['name']; ?>
                    </p>
                    <label>Состояние заказа:</label>
                    <p class="text-<?php echo substr($myOrder['orderStatus']['color'], 4); ?>"><?php echo $myOrder['orderStatus']['name']; ?></p>
                </div>
            </div>
        </div>
    </div>
</main>