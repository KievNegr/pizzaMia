<?php

use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\YiiAsset;

	echo Html::tag('h2', 'Статистика сайта');
?>
<div class="statistic">
	<h5>Заказы</h5>
	<div class="container">
		<div class="row">
			<div class="col" style="text-align: center;">
				<p class="h6">Все заказы</p>
				<p class="h1"><?php echo count($orders); ?></p>
			</div>
			<?php foreach ($orderStatus as $status): ?>
			<div class="col" style="text-align: center;">
				<p class="h6"><?php echo $status['name']; ?></p>
				<?php
					$count = 0;
					foreach($orders as $order)
					{
						if($order['status'] == $status['id'])
							$count++;
					}
				?>
				<p class="h1"><?php echo $count; ?></p>
			</div>
			<?php endforeach; ?>
		</div>
	</div>

	<h5>Что по деньгам</h5>
	<?php
		$earnedMoney = 0;
		$expectedMoney = 0;
		$hopedMoney = 0;
		$wastedMoney = 0;

		foreach($orderStatus as $status)
		{
			$money = 0;
			foreach($orders as $order)
			{
				if($order['status'] == $status['id'])
				{
					$day = substr($order['date'], 8, 2);
					$month = substr($order['date'], 5, 2);
					$year = substr($order['date'], 0, 4);
					$time = substr($order['date'], 11);

					$allValue = 0;

					foreach($order['orderlist'] as $value)
					{
						$allValue += $value['price'] * $value['count'];
					}

					$money = $allValue;

					if(!empty($order['discount']) || $order['discount'] != 0)
					{
						$money = $allValue / 100 * (100 - $order['discount']['discount']);
					}

					if($order['coupon'] != 0 || !empty($order['coupon']))
					{
						$money = $allValue / 100 * (100 - $order['coupon']['discount']);
					}

					if($status['cancel_promo'] == 1)
						$wastedMoney += $money;

					if($status['earned'] == 1)
						$earnedMoney += $money;

					if($status['expected'] == 1)
						$expectedMoney += $money;

					if($status['hoped'] == 1)
						$hopedMoney += $money;
				}
			}
		}
	?>
	<div class="container">
		<div class="row">
			<div class="col" style="text-align: center;">
				<p class="h6">Заработали</p>
				
				<p class="h1"><?php echo $earnedMoney . ' ' . $currency['name']; ?></p>
			</div>
			<div class="col" style="text-align: center;">
				<p class="h6">Ожидаем</p>
				<p class="h1"><?php echo $expectedMoney . ' ' . $currency['name']; ?></p>
			</div>
			<div class="col" style="text-align: center;">
				<p class="h6">В планах</p>
				<p class="h1"><?php echo $hopedMoney . ' ' . $currency['name']; ?></p>
			</div>
			<div class="col" style="text-align: center;">
				<p class="h6">Не заработали</p>
				<p class="h1"><?php echo $wastedMoney . ' ' . $currency['name']; ?></p>
			</div>
		</div>
	</div>

	<h5>Пользователи</h5>
	<div class="container">
		<div class="row">
			<div class="col" style="text-align: center;">
				<p class="h6">Все</p>
				<p class="h1"><?php echo count($users); ?></p>
			</div>
			<?php foreach ($userLevel as $level): ?>
			<div class="col" style="text-align: center;">
				<p class="h6"><?php echo $level['level_name']; ?></p>
				<?php
					$count = 0;
					foreach($users as $user)
					{
						if($user['level'] == $level['id'])
							$count++;
					}
				?>
				<p class="h1"><?php echo $count; ?></p>
			</div>
			<?php endforeach; ?>
		</div>
	</div>

	<h5>Витрина</h5>
	<div class="container">
		<div class="row">
			<div class="col" style="text-align: center;">
				<p class="h6">Категорий</p>
				<p class="h1"><?php echo $categoryCount; ?></p>
			</div>
			<div class="col" style="text-align: center;">
				<p class="h6">Товаров</p>
				<p class="h1"><?php echo $goodsCount; ?></p>
			</div>
		</div>
	</div>
</div>