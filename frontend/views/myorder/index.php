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
                <p><?php echo Yii::$app->user->identity->username; ?> / <span>Мои заказы</span></p>
                <small>История ваших заказов, установка рейтинга товаров</small>
            </div>
        </div>
        <?php if($myOrder): ?>
        <table class="table table-hover resp-table">
            <thead class="thead-light">
                <tr>
                    <th scope="col">№</th>
                    <th scope="col">Номер заказа</th>
                    <th scope="col">Дата</th>
                    <th scope="col">Сумма</th>
                    <th scope="col">Состояние</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $count = 1;
                    foreach($myOrder as $order): 
                ?>
                <tr>
                    <td class="align-middle" scope="row"><?php echo $count; ?></td>
                    <td class="align-middle">
                        <?php 
                            echo Html::a($order['id'], Url::to(['myorder/show', 'id' => $order['id']]), ['class' => 'text-primary']);
                        ?>
                    </td>
                    <td class="align-middle"><?php echo substr($order['date'], 8, 2) . '.' . substr($order['date'], 5, 2) . '.' . substr($order['date'], 0, 4) . ' в ' . substr($order['date'], 11, 8); ?></td>
                    <td class="align-middle">
                        <?php
                            $sum = 0;
                            $discount = Array();
                            foreach($order['orderlist'] as $orderlist)
                            {
                                $sum += $orderlist['price'] * $orderlist['count'];
                            }

                            foreach ($coupons as $coupon) {
                                if($order['coupon'] == $coupon['id'])
                                    $discount = $coupon;
                            }

                            if(empty($discount))
                            {
                                if(empty($order['discount']) || $order['discount'] == 0)
                                {
                                    echo '<span class="btn btn-light btn-sm">' . $sum . ' ' . $currency['name'] . '</span>';
                                }
                                else
                                {
                                    echo '<span class="btn btn-danger btn-sm"><strike>' . $sum . ' ' . $currency['name'] . '</strike></span> <span class="btn btn-info btn-sm">' . $sum * (100 - $order['discount']['discount']) / 100 . ' ' . $currency['name'] . ' Скидка: (' . $order['discount']['discount'] . ' %)</span>';
                                }
                            }
                            else
                            {
                                echo '<span class="btn btn-danger btn-sm"><strike>' . $sum. ' ' . $currency['name'] . '</strike></span> <span class="btn btn-success btn-sm">' . $sum * (100 - $discount['discount']) / 100 . ' '. $currency['name'] . ' Промокод: ' . $discount['code'] . ' (' . $discount['discount'] . ' %)</span>';
                            }
                            
                        ?>
                    </td>
                    <td class="align-middle">
                        <?php 
                            echo '<span class="btn btn-sm ' . $order['orderStatus']['color'] . '">' . $order['orderStatus']['name'] . '</td>';
                        ?>
                    </td>
                </tr>
                <?php 
                    $count++;
                    endforeach; 
                ?>
            <tr>
            </tbody>
        </table>
        <?php else: ?>
            <div class="alert alert-secondary" style="margin-top: 10px;">У вас еще нет заказов.</div>
        <?php endif; ?>
    </div>
</main>