<?php
namespace backend\models\order;

use Yii;
use yii\base\Model;
use backend\components\validators\UserUserPhoneValidator;

class AddOrderForm extends Model
{
	public $orderPhone;
	public $orderAnotherPhone;
	public $noCheckOrderAnotherPhone;
	public $orderCustomer;
	public $orderCustomerId;
	public $orderEmail;
	public $orderAddress;
	public $orderAdditions;
	public $orderDelivery;
	public $orderPay;
	public $orderList;
	public $orderDiscount;
	public $orderStatus;
	public $couponId;
	public $orderDate; //Дата доставки
	public $idOrder;
	public $currency; //Валюта для email
	public $orderValue; //Стоимость заказа для email
	public $deliveryName; //Название доставки для email
	public $deliveryValue; //Стоимость доставки для email
	public $payName; //Название оплаты для email
	public $discountValue; //Скидка в % для email
	public $table; //список товаров для email

	public function attributeLabels()
	{
		return [
			'orderPhone' => 'Контактный номер телефона',
			'orderAnotherPhone' => 'Дополнительный номер телефона',
			'orderEmail' => 'Электронная почта',
			'orderCustomer' => 'Имя клиента',
			'orderAddress' => 'Адрес доставки',
			'orderAdditions' => 'Дополнительная информация',
			'orderDelivery' => 'Способ доставки',
			'orderPay' => 'Способ оплаты',
			'orderDiscount' => 'Предоставить скидку, %',
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
			['orderEmail', 'email'],
			['orderAddress', 'trim'],
			['orderAdditions', 'trim'],
			['orderDelivery', 'required'],
			['orderPay', 'required'],
			['orderList', 'required'],
			['orderDiscount', 'number', 'min' => 0, 'max' => 100],
			['orderStatus', 'required', 'message' => ''],
			['couponId', 'safe'],
			['orderDate', 'required', 'message' => 'Выберите время доставки'],
		];
	}

	public function save($currency)
	{		        
        $gotDiscount = NUll;
        if($this->couponId == 0)
		{
			if($this->orderDiscount != 0)
			{
				$discount = $this->createDiscount();
				$discount->save();
				$gotDiscount = $discount->id;
			}
		}

		$order = $this->createOrder($gotDiscount);

		$transaction = Order::getDb()->beginTransaction();

        if($order->save())
        {
            $this->orderList = substr($this->orderList, 0, -1);
			$this->orderList = explode('&', $this->orderList);
			
			$this->orderValue = 0;

			foreach($this->orderList as $list)
			{
				$item = explode(';', $list);
				$this->orderValue += $item[2] * $item[1];
				$this->table[] = $item;

				$orderList = $this->createOrderList($item, $order->id);
				if($orderList->save())
				{
					$orderSave = true;
				}
				else
				{
					$orderSave = false;
				}
			}

			if($orderSave == true)
			{
				$transaction->commit();

				$this->idOrder = $order->id;

				$this->currency = $currency;

				return true;
			}
        }
        $transaction->rollBack();
	}

	private function createDiscount()
	{
		return new Discount([
			'from_admin' => Yii::$app->user->identity->id,
			'to_user' => $this->orderCustomerId,
			'discount' => $this->orderDiscount,
		]);
	}

	private function createOrder($discount)
	{
		return new Order([
			'user_id' => $this->orderCustomerId,
			'user_name' => $this->orderCustomer,
			'user_phone' => $this->orderPhone,
			'user_another_phone' => $this->orderAnotherPhone,
			'user_email' => $this->orderEmail,
			'user_address' => $this->orderAddress,
			'user_comments' => $this->orderAdditions,
			'date' => date('Y-m-d H:i:s'),
			'delivery' => $this->orderDelivery,
			'pay' => $this->orderPay,
			'coupon' => $this->couponId,
			'discount' => $discount,
			'status' => $this->orderStatus,
			'manager' => Yii::$app->user->identity->id,
			'changed' => Yii::$app->user->identity->id,
			'deliveryTime' => $this->orderDate,
		]);
	}

	private function createOrderList($item, $orderId)
	{
		return new OrderList([
			'id_order' => $orderId,
			'id_product' => $item[0],
			'count' => $item[1],
			'price' => $item[2],
			'size' => $item[3],
		]);
	}

	public function sendEmail($customer)
	{
		if(!empty($this->orderEmail))
		{
			$message = Yii::$app->mailer->compose(['html' => 'admin-order-html'], ['customer' => $customer, 'fileLogo' => 'images/logo_footer.png']);
			$message->setTo($this->orderEmail);
			$message->setFrom(['order@pizzamia.com.ua' => 'PizzaMia заказ']);
			$message->setSubject('Заказ № ' . $this->idOrder);

			$message->send();

			return true;
		}

		return false;
	}

	public function sendTelegram($customer)
	{
		$value = $customer->orderValue * (100 - $customer->discountValue) / 100 + $customer->deliveryValue;

		$text = 'Новый заказ № ' . $this->idOrder . ' на имя ' . $this->orderCustomer . ' Телефон: ' . $this->orderPhone . '. Сумма заказа: ' . $value . ' ' . $customer->currency;
		
		Yii::$app->telegram->sendMessage([
		    'chat_id' => '-380199054',
		    'text' => $text,
		]);
	}

	public function sendSms($customer)
	{
		$value = $customer->orderValue * (100 - $customer->discountValue) / 100 + $customer->deliveryValue;

		$text = htmlspecialchars('Заказ № ' . $this->idOrder . ' На сумму ' . $value . ' ' . $customer->currency);
		$description = '';
		$start_time = 'AUTO';
		$end_time = 'AUTO';
		$rate = 1;
		$lifetime = 4;
		$source = 'Pizza Mia'; // Alfaname

		$recipient = str_replace('+', '', $this->orderPhone);
        $recipient = str_replace('(', '', $recipient);
        $recipient = str_replace(')', '', $recipient);
        $recipient = str_replace('-', '', $recipient);

		$user = '380632238628'; // òóò âàø ëîãèí â ìåæäóíàðîäíîì ôîðìàòå áåç çíàêà +. Ïðèìåð: 380501234567
		$password = 'orkgb4'; // Âàø ïàðîëü

		$myXML 	 = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		$myXML 	.= "<request>";
		$myXML 	.= "<operation>SENDSMS</operation>";
		$myXML 	.= '		<message start_time="'.$start_time.'" end_time="'.$end_time.'" lifetime="'.$lifetime.'" rate="'.$rate.'" desc="'.$description.'" source="'.$source.'">'."\n";
		$myXML 	.= "		<body>".$text."</body>";
		$myXML 	.= "		<recipient>".$recipient."</recipient>";
		$myXML 	.=  "</message>";
		$myXML 	.= "</request>";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERPWD , $user.':'.$password);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_URL, 'http://sms-fly.com/api/api.php');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml", "Accept: text/xml"));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $myXML);
		$response = curl_exec($ch);
		curl_close($ch);

		// âûâîä ðåçóëüòàòà â áðàóçåð äëÿ óäîáñòâà ÷òåíèÿ îáðàìëåí â textarea
		// echo '<textarea spellcheck="false" name="111" rows="25" cols="150">';
		// echo $response;
		// echo '</textarea>';
	}
}