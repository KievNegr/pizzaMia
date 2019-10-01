<?php
	use yii\helpers\Url;
	use yii\helpers\Html;
	echo Html::a('<i class="fas fa-angle-left"></i> Назад</a>', Url::to(['customer/info', 'id' => $order['user_id']]), ['class' => 'btn btn-link btn-sm', 'role' => 'button', 'aria-pressed' => 'true']);
	echo Html::tag('h5', 'Детальная информация по заказу №' . $order['id']);
?>
<div class="container show-order">
    <div class="row">
        <div class="col customer">
            <label>Имя:</label>
            <p class="text-info"><?php echo $order['user_name']; ?></p>
            <label>Контактный номер телефона:</label>
            <p class="text-info"><?php echo $order['user_phone']; ?></p>
            <label>Дополнительный номер телефона:</label>
            <?php 
                if(empty($order['user_another_phone']) || $order['user_another_phone'] == 0)
                {
                    echo '<p class="text-muted">Не указан</p>';
                }
                else
                {
                    echo '<p class="text-info">' . $order['user_another_phone'] . '</p>';
                }
            ?>
            <label>E-mail:</label>
            <?php 
                if(empty($order['user_email']) || $order['user_email'] == NULL)
                {
                    echo '<p class="text-muted">Не указан</p>';
                }
                else
                {
                    echo '<p class="text-info">' . $order['user_email'] . '</p>';
                }
            ?>
            <label>Адрес доставки:</label>
            <?php 
                if(empty($order['user_address']) || $order['user_address'] == NULL)
                {
                    echo '<p class="text-muted">Не указан</p>';
                }
                else
                {
                    echo '<p class="text-info">' . $order['user_address'] . '</p>';
                }
            ?>
            <label>Комментарий:</label>
            <?php 
                if(empty($order['user_comments']) || $order['user_comments'] == NULL)
                {
                    echo '<p class="text-muted">Не указан</p>';
                }
                else
                {
                    echo '<p class="text-info">' . $order['user_comments'] . '</p>';
                }
            ?>
        </div>
        
        <div class="col borders">
            <?php foreach ($order['orderlist'] as $orderlist): ?>
                <div class="list">
                    <div class="img" style="background-image: url(<?php echo Url::to('@thumbs/' . $orderlist['itemImage']['name']);?>);"></div>
                    <div class="info">
                        <?php echo Html::a($orderlist['item']['title'], Url::to(['goods/index', 'sef' => $orderlist['item']['sef']]), ['class' => 'text-primary', 'target' => '_blank']);?></a>
                        <div class="amount">
                            <span><?php echo $orderlist['price'] . ' ' . $currency['name']; ?></span>
                            <span><?php echo $orderlist['count'] . ' шт.'; ?></span>
                            <span><?php echo $orderlist['price'] * $orderlist['count'] . ' ' . $currency['name']; ?></span>
                        </div>
                        <?php if($order['orderStatus']['set_ratio'] == 1 && $orderlist['ratio'] == NULL): ?>
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
                    foreach ($order['orderlist'] as $orderlist)
                    {
                        $sum += $orderlist['price'] * $orderlist['count'];
                    }
                    echo $sum . ' ' . $currency['name'];
                ?>
            </p>

            <?php
                $discount = Array();

                if(!empty($order['coupon']))
                	$discount = $order['coupon'];

                if(empty($discount))
                {
                    if(!empty($order['discount']) || $order['discount'] != 0)
                    {
                        echo '<label>Скидка:</label>';
                        echo '<p class="text-info">' . $order['discount']['discount'] . ' %</p>';

                        echo '<label>Сумма заказа со скидкой ' . $order['discount']['discount'] . ' %:</label>';
                        echo '<p class="text-info">' . $sum * (100 - $order['discount']['discount']) / 100 . ' ' . $currency['name'] . '</p>';

                        $sum = $sum * (100 - $order['discount']['discount']) / 100;
                    }
                }
                else
                {
                    echo '<label>Примененный промокод:</label>';
                    echo '<p class="text-info">' . $discount['code'] . ' (' . $discount['discount'] . ' %)</p>';

                    echo '<label>Сумма заказа со скидкой ' . $discount['discount'] . ' %:</label>';
                    echo '<p class="text-info">' . $sum * (100 - $discount['discount']) / 100 . ' ' . $currency['name'] . '</p>';

                    $sum = $sum * (100 - $discount['discount']) / 100;
                }
            ?>
            <label>Оплата:</label>
            <p class="text-info">
                <?php
                    echo $order['pay']['name'];
                ?>
            </p>
            <label>Доставка:</label>
            <p class="text-info">
                <?php
                    echo $order['delivery']['name'];
                ?>
            </p>
            <label>Стоимость доставки:</label>
            <p class="text-info">
                <?php echo $order['delivery']['value'] . ' ' . $currency['name']; ?>
            </p>
            <label>К оплате:</label>
            <p class="text-success">
                <?php echo $sum + $order['delivery']['value'] . ' ' . $currency['name']; ?>
            </p>
            <label>Состояние заказа:</label>
            <p class="text-<?php echo substr($order['orderStatus']['color'], 4); ?>"><?php echo $order['orderStatus']['name']; ?></p>
        </div>
    </div>
</div>