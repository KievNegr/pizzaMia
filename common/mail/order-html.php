<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

?>
<header>
	<img src="<?php echo $message->embed($fileLogo); ?>" />
	<ul>
		<li><?php echo Html::a('Заказать пиццу', Yii::$app->request->hostInfo, ['target' => '_blank']); ?></li>
		<li><?php echo Html::a('Мои заказы', Yii::$app->request->hostInfo . '/myorder.html', ['target' => '_blank']); ?></li>
		<li><?php echo Html::a('Контакты', Yii::$app->request->hostInfo . '/page/contact.html', ['target' => '_blank']); ?></li>
	</ul>
</header>
<h3><?php echo $customer->name; ?>, спасибо за ваш заказ!</h3>
<p>Ваша заявка принята. Мы свяжемся с вами в ближайшее время для подтверждения заказа №<?php echo $customer->idOrder; ?>.</p>
<p>Информацию о заказе вы можете узнать в своем <?php echo Html::a('личном кабинете', Yii::$app->request->hostInfo . '/myorder/show/' . $customer->idOrder . '.html', ['target' => '_blank']); ?>.</p>
<h4>Заказ №<?php echo $customer->idOrder; ?></h4>
<table>
	<tr>
		<th>Название</th>
		<th>Количество</th>
		<th>Сумма</th>
	</tr>
	<?php foreach($_SESSION['cart']['list'] as $list): ?>
	<tr>
		<td><?php echo $list['product_name']; ?></td>
		<td><?php echo $list['qty']; ?></td>
		<td><?php echo $list['price'] * $list['qty']; ?></td>
	</tr>
	<?php endforeach; ?>
</table>
<div class="info">
	<label>Номер заказа:</label>
	<p class="text-info"><?php echo $customer->idOrder; ?></p>
	<label>Контактное лицо:</label>
	<p class="text-info"><?php echo $customer->name; ?></p>
	<label>Номер телефона:</label>
	<p class="text-info"><?php echo $customer->phone; ?></p>
	<label>Дополнительный номер телефона:</label>
	<p class="text-info"><?php echo $customer->anotherPhone; ?></p>
	<label>Способ оплаты:</label>
	<p class="text-info"><?php echo $customer->orderPay; ?></p>
	<label>Способ доставки:</label>
	<p class="text-info"><?php echo $customer->orderDelivery; ?></p>
	<label>Адрес доставки:</label>
	<p class="text-info"><?php echo $customer->address; ?></p>
	<label>Время доставки:</label>
	<p class="text-info"><?php echo $customer->orderDate; ?></p>
</div>
<h4>Скидка: <?php echo $_SESSION['cart']['discount']['value']; ?>%</h4>
<h4>Стоимость доставки: <?php echo $_SESSION['cart']['delivery']['value'] . ' ' . $customer->currency; ?></h4>
<h3>Сумма к оплате: <?php echo $_SESSION['cart']['sum'] * (100 - $_SESSION['cart']['discount']['value']) / 100 + $_SESSION['cart']['delivery']['value'] . ' ' . $customer->currency; ?></h3>