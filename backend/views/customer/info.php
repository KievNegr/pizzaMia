<?php
	use yii\helpers\Url;
	use yii\helpers\Html;
?>
<div class="user_info">
	<div class="hello">
	    <?php
	        $avatar = 'default_avatar.png';
	        if(!empty($user->image))
	            $avatar = $user->image;
	    ?>                        
	    <img src="<?php echo Url::to('@avatar/' . $avatar); ?>" />
	    <div>
	        <p><?php echo $user->username; ?> / <span>Информация о клиенте</span></p>
	        <small>Контакты, данные о заказах, промокоды</small>
	    </div>
	</div>
	<?php 
		echo Html::a('<i class="fas fa-angle-left"></i> Назад к списку</a>', Url::to(['customer/index']), ['class' => 'btn btn-link btn-sm', 'role' => 'button', 'aria-pressed' => 'true']);
	?>
	<div class="customer">
		<h5>Контактные данные</h5>
		<div class="container">
			<div class="row">
				<div class="col">
					<label>E-mail:</label>
					<p class="text-info"><?php echo $user->email; ?></p>
					<label>E-mail для доставки:</label>
					<p class="text-info"><?php echo $user->order_email; ?></p>
				</div>
				<div class="col">
					<label>Телефон:</label>
					<p class="text-info"><?php echo $user->phone; ?></p>
					<label>Телефон:</label>
					<p class="text-info"><?php echo $user->another_phone; ?></p>
				</div>
				<div class="col">
					<label>Адрес доставки:</label>
					<p class="text-info"><?php echo $user->address; ?></p>
					<label>Дополнительная информация:</label>
					<p class="text-info"><?php echo $user->additional; ?></p>
				</div>
			</div>
		</div>
		<h5>Статистика</h5>
		<div class="container">
			<div class="row">
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
		<h5>Промокоды</h5>
		<?php
			$usedCoupons = Array(); //Массив купонов которые уже применялись в заказах
			foreach($orders as $order)//Если заказы были то перечисляем их
			{
				$usedCoupons[] = $order['coupon']; //Если в заказах нашлись купоны то добавляем в массив
			}
		?>
		<div class="container">
			<div class="row">
				<div class="col">
					<p class="h6">Активные</p>
					<?php
						foreach ($couponList as $coupon)//Перебираем все пользовательские купоны
						{
							if($coupon['expiration'] >= date('Y-m-d'))//Если они еще активны то
							{
								$dateExpiration = substr($coupon['expiration'], 8, 2) . '.' . substr($coupon['expiration'], 5, 2) . '.' . substr($coupon['expiration'], 0, 4); //Приводим дату в норм вид

								if(!in_array($coupon['id'], $usedCoupons)) //Если купон не юзаный то выводим его
								{
									echo '<p class="badge badge-primary" style="font-weight: 400;">' . $coupon['code'] . ', ' . $coupon['discount'] . '%, до ' . $dateExpiration . '</p>';
								}
								elseif($coupon['applying'] == 1) //Если купон юзаный но многоразовый то тоже выводим его
								{
									echo '<p class="badge badge-primary" style="font-weight: 400;">' . $coupon['code'] . ', ' . $coupon['discount'] . '%, до ' . $dateExpiration . ', многоразовый</p>';
								}
							}
						} 	
					?>
				</div>
				<div class="col">
					<p class="h6">Использованные</p>
					<?php
						foreach ($couponList as $coupon)//Перебираем все пользовательские купоны
						{
							$dateExpiration = substr($coupon['expiration'], 8, 2) . '.' . substr($coupon['expiration'], 5, 2) . '.' . substr($coupon['expiration'], 0, 4); //Приводим дату в норм вид

							if(in_array($coupon['id'], $usedCoupons)) //Если купон юзаный то выводим его
							{
								if($coupon['applying'] == 1) //Если купон юзаный но многоразовый то тоже выводим его
								{
									echo '<p class="badge badge-primary" style="font-weight: 400;">' . $coupon['code'] . ', ' . $coupon['discount'] . '%, до ' . $dateExpiration . ', многоразовый</p>';
								}
								else //Иначе просто выводим юзаный купон
								{
									echo '<p class="badge badge-success" style="font-weight: 400;">' . $coupon['code'] . ', ' . $coupon['discount'] . '%, до ' . $dateExpiration . '</p>';
								}
							}
						} 	
					?>
				</div>
				<div class="col">
					<p class="h6">Просроченные</p>
					<?php
						foreach ($couponList as $coupon)//Перебираем все пользовательские купоны
						{
							if($coupon['expiration'] < date('Y-m-d'))//Если они еще активны то
							{
								$dateExpiration = substr($coupon['expiration'], 8, 2) . '.' . substr($coupon['expiration'], 5, 2) . '.' . substr($coupon['expiration'], 0, 4); //Приводим дату в норм вид

								if($coupon['applying'] == 1) //Если купон юзаный но многоразовый то тоже выводим его
								{
									echo '<p class="badge badge-secondary" style="font-weight: 400;">' . $coupon['code'] . ', ' . $coupon['discount'] . '%, до ' . $dateExpiration . ', многоразовый</p>';
								}
								else //Иначе просто выводим юзаный купон
								{
									echo '<p class="badge badge-secondary" style="font-weight: 400;">' . $coupon['code'] . ', ' . $coupon['discount'] . '%, до ' . $dateExpiration . '</p>';
								}
							}
						} 	
					?>
				</div>
			</div>
		</div>
		<h5>Список заказов</h5>
		<?php
			$earnedMoney = 0;
			$expectedMoney = 0;
			$hopedMoney = 0;
			$wastedMoney = 0;

			foreach ($orderStatus as $status)
			{
				echo Html::tag('h6', $status['name'], ['class' => 'h6']);
				$count = 1;
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

						$price = '<span class="btn btn-sm btn-light">' . $allValue . ' ' . $currency['name'] . ' </span>';
						$money = $allValue;

						if(!empty($order['discount']))
						{
							$price = '<span class="btn btn-sm btn-success">' . $allValue / 100 * (100 - $order['discount']['discount']) . ' ' . $currency['name'] . ' Скидка ' . $order['discount']['discount'] . ' %</span>';
							$money = $allValue / 100 * (100 - $order['discount']['discount']);
						}

						if($order['coupon'] !=0)
						{
							foreach($couponList as $discount)
							{
								if($order['coupon'] == $discount['id'])
								{
									$orderCouponDiscount = $discount['discount'];
									$orderCouponCode = $discount['code'];
								}
							}

							$price = '<span class="btn btn-sm btn-success">' . $allValue / 100 * (100 - $orderCouponDiscount) .  ' ' . $currency['name'] . ' ' . $orderCouponCode . ' ' . $orderCouponDiscount . ' %</span>';
							$money = $allValue / 100 * (100 - $orderCouponDiscount);
						}

						if($status['cancel_promo'] == 1)
							$wastedMoney += $money;

						if($status['earned'] == 1)
							$earnedMoney += $money;

						if($status['expected'] == 1)
							$expectedMoney += $money;

						if($status['hoped'] == 1)
							$hopedMoney += $money;

						echo '<div class="user-order">';
							echo '<span class="badge badge-pill badge-danger">'. $count . '</span>';
							echo '<span>' . Html::a('№ ' . $order['id'], Url::to(['customer/order', 'id' => $order['id']])) . '</span>';
							echo '<span>' . $day . '.' . $month . '.' . $year . ' в ' . $time . '</span>';
							echo '<span>' . $price . '</span>';
						echo '</div>';

						$count++;
					}
				}
				if($count == 1)
					echo '<span class="badge badge-info" style="font-weight: 400; margin-bottom: 20px;">Заказы отсутствуют</span>';
			}
		?>

		<h5>Что по деньгам</h5>
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
					<p class="h6">Возможно заработаем</p>
					<p class="h1"><?php echo $hopedMoney . ' ' . $currency['name']; ?></p>
				</div>
				<div class="col" style="text-align: center;">
					<p class="h6">Не получили</p>
					<p class="h1"><?php echo $wastedMoney . ' ' . $currency['name']; ?></p>
				</div>
			</div>
		</div>
	</div>
</div>