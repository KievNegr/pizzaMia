<?php
	use yii\helpers\Html;

	$this->registerJsFile('@web/js/searchForOrder.js', ['depends' => 'yii\web\JqueryAsset']);
	
	echo Html::button('Новый заказ', ['class' => 'btn btn-success right clearFields', 'data-toggle' => 'modal', 'data-target' => '.newOrder']);
	echo Html::tag('h2', 'Список заказов');

	if(yii::$app->session->getFlash('success'))
	{
		yii\bootstrap4\Alert::begin(['options' => ['class' => 'alert alert-success']]);
			echo yii::$app->session->getFlash('success');
		yii\bootstrap4\Alert::end();
	}
?>

<?php if($orders): ?>
<div class="custom-control custom-switch" style="margin: 10px 0 10px 0; padding-left: 2.25rem;">
  <input type="checkbox" class="custom-control-input" id="myOrders">
  <label class="custom-control-label" for="myOrders">Отображать мои заказы</label>
</div>
<?php 
	$count = 1;
?>
<table class="table table-hover table-bordered">
	<thead class="thead-dark">
		<tr>
			<th scope="col">#</th>
			<th scope="col">№ заказа</th>
			<th csope="col">Дата заказа</th>
			<th scope="col">Сумма заказа</th>
			<th scope="col">Клиент</th>
			<th scope="col">№ телефона</th>
			<th scope="col">Статус заказа</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td></td>
			<td><?php echo Html::textInput('orderId', '', ['class' => 'searchById form-control form-control-sm']); ?></td>
			<td><?php echo Html::textInput('orderId', '', ['class' => 'form-control form-control-sm', 'disabled' => 'disabled']); ?></td>
			<td><?php echo Html::textInput('orderId', '', ['class' => 'form-control form-control-sm', 'disabled' => 'disabled']); ?></td>
			<td><?php echo Html::textInput('orderCustomer', '', ['class' => 'searchByCustomer form-control form-control-sm']); ?></td>
			<td><?php echo Html::textInput('orderPhone', '', ['class' => 'searchByPhone form-control form-control-sm']); ?></td>
			<td><?php echo Html::textInput('orderId', '', ['class' => 'form-control form-control-sm', 'disabled' => 'disabled']); ?></td>
		</tr>
		<?php foreach($orders as $order): ?>
			<?php
				$phone = str_replace('-', '', substr($order['user_phone'], 4));
				$phone = str_replace(')', '', $phone);
				$name = mb_strtolower(str_replace(' ', '', $order['user_name']));
			?>
			<tr 
				orderId="order-<?php echo $order['id']; ?>"
				orderCustomer="customer-<?php echo $name; ?>"
				orderPhone="<?php echo $phone; ?>"
				class="searching listOrder"
				<?php if($order['manager'] == Yii::$app->user->identity->id): ?>
					style="background-color: #fff493;"
					title="Мой заказ"
					myOrder="show"
				<?php endif; ?>
				<?php if($order['manager'] != 0): ?>
					style="background-color: #bdbdbd;"
					title="Заказ уже в работе"
				<?php endif; ?>
			>
				<td
				<?php if($order['changed'] != Yii::$app->user->identity->id && $order['manager'] == Yii::$app->user->identity->id): ?>
					style="background-color: #ff5722;"
					title="Ктото обновлял заказ"
				<?php endif; ?>
				>
					<?php echo $count; ?>
				</td>
				<td>
					<?php 
						echo Html::tag('span', '№ ' . $order['id'], ['class' => 'text-primary pointer buttonEditOrder', 'id' => 'idForEditOrder-' . $order['id'], 'data-toggle' => 'modal', 'data-target' => '.editOrder', 'title' => 'Просмотр и редактирование заказа']);
					?>
				</td>
				<td>
					<?php
						$day = substr($order['date'], 8, 2);
						$month = substr($order['date'], 5, 2);
						$year = substr($order['date'], 0, 4);
						$time = substr($order['date'], 11);
						echo $day . '.' . $month . '.' . $year . ' в ' . $time;
					?>
				</td>
				<td>
					<?php
						$allValue = 0;
						foreach ($order['orderlist'] as $value) 
						{
							$allValue += $value['price'] * $value['count'];
						}

						$price = '<span class="btn btn-sm btn-light">' . $allValue . ' ' . $currency['name'] . '</span>';

						if(!empty($order['discount']))
						{
							$price = '<span class="btn btn-sm btn-success">' . $allValue / 100 * (100 - $order['discount']['discount']) . ' ' . $currency['name'] . ' Скидка ' . $order['discount']['discount'] . ' %</span>';
							$allValue = $allValue / 100 * (100 - $order['discount']['discount']);
						}

						if($order['coupon'] !=0)
						{
							foreach($order['coupons'] as $discount)
							{
								if($order['coupon'] == $discount['id'])
								{
									$orderCouponDiscount = $discount['discount'];
									$orderCouponCode = $discount['code'];
								}
							}

							$price = '<span class="btn btn-sm btn-success">' . $allValue / 100 * (100 - $orderCouponDiscount) .  ' ' . $currency['name'] . ' ' . $orderCouponCode . ' ' . $orderCouponDiscount . ' %</span>';
							$allValue = $allValue / 100 * (100 - $orderCouponDiscount);
						}

						$payForOrder = $order['delivery']['value'] + $allValue; //Полная стоимость с доставкой
						
						echo $price;
					?>
				</td>
				<td>
					<?php echo $order['user_name']; ?>
				</td>
				<td>
					<?php echo $order['user_phone']; ?>
				</td>
				<td>
					<?php 
						foreach($allOrderStatus as $status)
						{
							if($status['id'] == $order['status'])
								echo '<span class="btn btn-sm ' . $status['color'] . '">' . $status['name'] . '</span>';
						}
					?>
				</td>
				<?php
					echo Html::hiddenInput(null, $order['user_id'], ['class' => 'edit-customerId-' . $order['id']]);
					echo Html::hiddenInput(null, $order['user_phone'], ['class' => 'edit-phone-' . $order['id']]);
					echo Html::hiddenInput(null, $order['user_another_phone'], ['class' => 'edit-another_phone-' . $order['id']]);
					echo Html::hiddenInput(null, $order['user_name'], ['class' => 'edit-user_name-' . $order['id']]);
					echo Html::hiddenInput(null, $order['user_address'], ['class' => 'edit-adsress-' . $order['id']]);
					echo Html::hiddenInput(null, $order['user_comments'], ['class' => 'edit-user_comments-' . $order['id']]);
					echo Html::hiddenInput(null, $order['delivery']['id'], ['class' => 'edit-delivery-' . $order['id']]);
					echo Html::hiddenInput(null, $order['pay']['id'], ['class' => 'edit-pay-' . $order['id']]);
					
					$dataOrderList = '';
					foreach($order['orderlist'] as $list)
					{
						foreach ($goodsList as $itemName) 
						{
							if($itemName['id'] == $list['id_product'])
								$nameProduct = $itemName['title'];
						}
						$dataOrderList .= '<div class="editList editList' . $list['size'] . '" idItem="' . $list['id_product'] . '" idOption="' . $list['size'] . '">
							<img src="' .$pathToItemImage . $list['itemImage']['name'] . '" />
							<div class="link">
								<a href="#">' . $nameProduct . '</a>
								<p>' . $list['optiongoods']['optionName']['title'] . '</p>
							</div>
							<div class="amount">
								<div class="editDown" forOption="downItem' . $list['size'] . '">-</div>
								<div class="editNumber editAmountItem' . $list['size'] . '">' . $list['count'] . '</div>
								<div class="editUp" forOption="upItem' . $list['size'] . '">+</div>
							</div>
							<div class="editValue editSetPriceItem' . $list['size'] . '">' . $list['count'] * $list['price'] . '</div>
							<input type="hidden" value="' . $list['price'] . '" class="editPriceItem editPriceItem' . $list['size'] . '" />
							<span class="currency"> ' . $currency['name'] . '</span>
						</div>';
					}
					echo Html::hiddenInput(null, $dataOrderList, ['class' => 'edit-order_list-' . $order['id']]);
					echo Html::hiddenInput(null, $allValue, ['class' => 'edit-all_order_price-' . $order['id']]);
					echo Html::hiddenInput(null, $payForOrder, ['class' => 'edit-pay_order_price-' . $order['id']]);
					echo Html::hiddenInput(null, $order['delivery']['value'], ['class' => 'edit-delivery_order_price-' . $order['id']]);
					
					$discount = 0;
					$discountId = 0;
					$orderDiscountName = '';
					if(!empty($order['discount']))
					{
						$discount = $order['discount']['discount'];
						$discountId = $order['discount']['id'];
						$orderDiscountName = ' (Предоставил ' . $order['discount']['manager']['username'] . ')';
					}
					echo Html::hiddenInput(null, $discount, ['class' => 'edit-discount-' . $order['id']]);
					echo Html::hiddenInput(null, $discountId, ['class' => 'edit-discount_id-' . $order['id']]);
					echo Html::hiddenInput(null, $orderDiscountName, ['class' => 'edit-discount_name-' . $order['id']]);

					$availableCoupon = ''; //Список доступных купонов в виде HTML
					$cupounDiscount = 0; //Размер скидки %

					//Если у клиента есть купоны то
					if(!empty($order['coupons']))
					{	
						//Пробегаемся по его списку купонов
						foreach($order['coupons'] as $couponList)
						{
							$checked = ''; //Купон, который выбран в заказе
							$applying = ''; //Купон многоразовый
							$disabled = 'disabled'; //Купон, который просрочен

							//Если купон не просрочен то его можно юзать
							if($couponList['expiration'] >= date('Y-m-d'))	
							{
								$disabled = '';
							}

							//Если купон в заказе используется то
							if(!empty($order['coupon']))
							{
								//Ищем использованный купон
								if($order['coupon'] == $couponList['id'])
								{
									$checked = 'checked'; //Отмечаем его в списке
									$cupounDiscount = $couponList['discount']; //Прописуем % скидона
									$disabled = ''; //Даже если используемый купон просрочен он должен быть активным
								}
							}

							//Если купон многоразовый то отмечаем это в списке
							if($couponList['applying'] == 1)
								$applying = 'многоразовая';
							
							$dateExpiration = substr($couponList['expiration'], 8, 2) . '.' . substr($couponList['expiration'], 5, 2) . '.' . substr($couponList['expiration'], 0, 4);

							$availableCoupon .= '<div class="custom-control editCoupon custom-radio">
													<input type="radio" discount="' . $couponList['discount'] . '" editCouponId="' . $couponList['id'] . '" id="editCustomRadio' . $couponList['id'] . '" name="editCustomRadio" class="custom-control-input" '. $checked . ' ' . $disabled . '>
													<label class="custom-control-label select' . $couponList['id'] .'" for="editCustomRadio' . $couponList['id'] . '">' . $couponList['code'] . ' (до ' . $dateExpiration . ', ' . $applying . ' скидка ' . $couponList['discount'] . ' %)</label>
												</div>';
						}

						$availableCoupon .= '<div class="custom-control editCoupon custom-radio">
												<input type="radio" discount="0" editCouponId="0" id="editCustomRadioRefuse" name="editCustomRadio" class="custom-control-input notUse">
												<label class="custom-control-label select0" for="editCustomRadioRefuse">Не использовать</label>
											</div>';
					}

					$coupon = 0;
					if(!empty($order['coupon']))
					{
						$coupon = $order['coupon'];

					}
					echo Html::hiddenInput(null, $order['deliveryTime'], ['class' => 'edit-order_date-' . $order['id']]);
					echo Html::hiddenInput(null, $cupounDiscount, ['class' => 'edit-coupon_discount-' . $order['id']]);
					echo Html::hiddenInput(null, $coupon, ['class' => 'edit-coupon-' . $order['id']]);
					echo Html::hiddenInput(null, $availableCoupon, ['class' => 'edit-coupon_list-' . $order['id']]);

					echo Html::hiddenInput(null, $order['status'], ['class' => 'edit-order_status-' . $order['id']]);
				?>
			</tr>
		<?php $count++; ?>
		<?php endforeach; ?>
	</tbody>
</table>
<?php else: ?>
	<?php echo Html::tag('div', 'Заказы еще не созданы', ['class' => 'alert alert-secondary', 'style' => 'margin-top: 10px;']);?>
<?php endif; ?>

<?php
	echo Html::hiddenInput('currencyValue', $currency['name'], ['class' => 'default_currency']);
	echo $this->render('newModal', compact('newModel', 'deliveryList', 'payList', 'listCategories', 'goodsList', 'pathToItemImage', 'pathToItemOption', 'orderStatus', 'currency'));
	echo $this->render('editModal', compact('editModel', 'deliveryList', 'payList', 'orderStatus', 'currency'));
    echo $this->render('newOrderGoods', compact('listCategories', 'goodsList', 'pathToItemImage', 'pathToItemOption'));
    echo $this->render('editOrderGoods', compact('listCategories', 'goodsList', 'pathToItemImage', 'pathToItemOption'));
?>