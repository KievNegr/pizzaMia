<?php
namespace backend\models\order;

use Yii;
use yii\base\Model;
use backend\components\validators\UserUserPhoneValidator; //Валидатор телефонов

class EditOrderForm extends Model
{
	public $orderId; //Id заказа
	public $orderPhone; //Номер телефона
	public $orderAnotherPhone; //Дополнительный номер телефона
	public $noCheckOrderAnotherPhone; //Поле сравнения дополнительных номеров телефона
	public $orderCustomer; //Имя покупателя
	public $orderCustomerId; //Id покупателя
	public $orderAddress; //Адрес доставки
	public $orderAdditions; //Дополнительная инфа
	public $orderDelivery; //Вариант доставки
	public $orderPay; //Вариант оплаты
	public $orderList; //Список товаров в заказе
	public $orderDiscount; //Размер скидки в %
	public $orderStatus; //Статус заказа
	public $couponId; //Id примененного купона
	public $orderDiscountId; //Id скидки
	public $orderDate;

	public function attributeLabels()
	{
		return [
			'orderPhone' => 'Контактный номер телефона',
			'orderAnotherPhone' => 'Дополнительный номер телефона',
			'orderCustomer' => 'Имя клиента',
			'orderAddress' => 'Адрес доставки',
			'orderAdditions' => 'Дополнительная информация',
			'orderDelivery' => 'Способ доставки',
			'orderPay' => 'Способ оплаты',
			//'orderDiscount' => 'Предоставить скидку, %', смотри в шаблоне editModal
		];
	}

	public function rules()
	{
		return [
			['orderPhone', 'required'],
			['orderAnotherPhone', UserUserPhoneValidator::className(), 'when' => function(){return $this->orderAnotherPhone != $this->noCheckOrderAnotherPhone;}],
			['noCheckOrderAnotherPhone', 'safe'],
			['orderCustomer', 'required'],
			['orderCustomerId', 'required'],
			['orderId', 'required'],
			['orderAddress', 'trim'],
			['orderAdditions', 'trim'],
			['orderDelivery', 'required'],
			['orderPay', 'required'],
			['orderList', 'required'],
			['orderDiscount', 'number', 'min' => 0, 'max' => 100],
			['orderDiscountId', 'required'],
			['orderStatus', 'required'],
			['couponId', 'safe'],
			['orderDate', 'required', 'message' => 'Выберите время доставки'],
		];
	}

	public function update()
	{		        
        $gotDiscount = NUll; //Скидка по дефолту отсутствует

        if($this->couponId == 0) //Если купон не заюзан то
		{
			if($this->orderDiscount != 0) //Если скидка добавлена то
			{
				if($this->orderDiscountId == 0) //Если Id скидки отсутствует то значит что скидон новый и создаем его
				{
					$discount = $this->createDiscount(); //Создаем скидон и получаем его обьект
					$discount->save(); //Сохраняем в БД
					$gotDiscount = $discount->id; //Получаем его ID
				}
				else //Если сам скидон уже был добавлен то
				{
					$gotDiscount = $this->updateDiscount(); //Просто обновляем его и получаем Id
				}
			}
			else //Иначе еси купон заюзался то
			{
				if($this->orderDiscountId != 0) //Если присутствовала скидка то удаляем ее
					$this->removeDiscount(); //Метод удаления

				$gotDiscount = NUll; //Убираем скидку
			}
		}

		$order = $this->updateOrder($gotDiscount); //Обновляем сам заказ

		$this->orderList = substr($this->orderList, 0, -1); //Получаем список товаров в заказе
		$this->orderList = explode('&', $this->orderList); //Разбиваем его на массив

		foreach($this->orderList as $list) //Пробегаемся по массиву
		{
			$item = explode(';', $list); //Разбиваем дополнительно на массив и имеем количество, цену и размер
			$orderList = $this->updateOrderList($item, $this->orderId); //Обновляем запись или добавляем новую
		}
		return true; //возвращаем успех в контроллер
	}

	private function createDiscount() //Создаем новый скидон
	{
		return new Discount([
			'from_admin' => Yii::$app->user->identity->id, //Админ, который его предоставил
			'to_user' => $this->orderCustomerId, //Клиент, который его получил
			'discount' => $this->orderDiscount, //Размер скидона в %
		]);
	}

	private function updateDiscount() //Обновляем скидон
	{
		$discount = [
			'from_admin' => Yii::$app->user->identity->id, //Админ, который его изменил
			'discount' => $this->orderDiscount, //Размер скидона в %
		];
		Discount::updateAll($discount, ['id' => $this->orderDiscountId]); //Обновляем запись в БД
		return $this->orderDiscountId; // Возвращаем его ID
	}

	private function removeDiscount()
	{
		$discount = Discount::findOne($this->orderDiscountId); //Ищем нужную скидку
		return $discount->delete(); //Удаляем ее и возвращаем результат (true, false)
	}

	private function updateOrder($discount)
	{
		$order = [
			'user_id' => $this->orderCustomerId, //Id клиента
			'user_name' => $this->orderCustomer, //Заказ на имя
			'user_phone' => $this->orderPhone, //Телефон клиента
			'user_another_phone' => $this->orderAnotherPhone, //Дополнительный телефон
			'user_address' => $this->orderAddress, //Адрес доставки
			'user_comments' => $this->orderAdditions, //Доп.инфа
			'date' => date('Y-m-d H:i:s'), //Дата обновления заказа
			'delivery' => $this->orderDelivery, //Способ доставки
			'pay' => $this->orderPay, //Способ оплаты
			'coupon' => $this->couponId, //Id купона
			'discount' => $discount, //Id скидона
			'status' => $this->orderStatus, //Статус заказа
			'changed' => Yii::$app->user->identity->id, //Менеджер, который изменил заказ
			'deliveryTime' =>$this->orderDate, //Дата доставки
		];

		$manager = Order::findOne($this->orderId); //Проверяем заказ на доступность

		if($manager['manager'] == 0) //Если заказ никто не взял то он свободен
			$order['manager'] = Yii::$app->user->identity->id; //Менеджер, который взял заказ

		return Order::updateAll($order, ['id' => $this->orderId]); //Обновляем запись и возвращаем обьект
	}

	private function updateOrderList($item, $orderId) //Обновляем список товаров
	{
		//Проверяем этот товар в базе в этом заказе
		$checkAvailable = OrderList::find()
							->where(['id_order' => $this->orderId, 'id_product' => $item[0], 'size' => $item[3]])
							->one();
		if($checkAvailable) //Если такой товар уже есть то
		{
			$orderList = [
				'count' => $item[1], //Обновляем количество
			];

			return OrderList::updateAll($orderList, ['id' => $checkAvailable->id]); //И обновляем запись
		}
		else //Если такой записи нет то
		{
			$orderList = new OrderList([
				'id_order' => $orderId, //Id заказа
				'id_product' => $item[0], //Id товара
				'count' => $item[1], //Количество
				'price' => $item[2], //Цена товара
				'size' => $item[3], //Размер (диаметр пиццы, обьем кофе итд итп)
			]);	

			return $orderList->save(); //Добавляем запись
		}		
	}
}