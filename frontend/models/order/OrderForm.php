<?php

namespace frontend\models\order;

use Yii;
use yii\base\Model;
use frontend\models\User;
use frontend\components\validators\UserUserPhoneValidator;
use frontend\components\validators\UserUserEmailValidator;

class OrderForm extends Model
{
	public $name;
	public $email;
	public $phone;
	public $anotherPhone;
	public $address;
	public $additional;
	public $orderDelivery;
	public $orderPay;
	public $couponList;
	public $idOrder;
	public $currency;
	public $orderDate; //Дата доставки

	public function attributeLabels()
	{
		return [
			'name' => 'Ваше имя',
			'email' => 'Электронная почта',
			'phone' => 'Контактный номер телефона',
			'anotherPhone' => 'Дополнительный номер телефона',
			'address' => 'Адрес доставки',
			'additional' => 'Дополнительная информация',
			'orderDelivery' => 'Выбор доставки',
			'orderPay' => 'Выбор оплаты',
		];
	}

	public function rules()
	{
		return [
			//[['name', 'phone', 'orderDelivery', 'orderPay'], 'required'],
			['name', 'required', 'message' => 'Как к вам обращатся?'],
			['email', 'email'],
			['email', UserUserEmailValidator::className(), 'when' => function(){
																		if(!Yii::$app->user->isGuest)
																			return $this->email != Yii::$app->user->identity->email;
																	}],
			['phone', 'required', 'message' => 'Номер телефона нужен для связи с вами'],
			['anotherPhone', 'safe'],

			['phone', UserUserPhoneValidator::className(), 'when' => function(){
																		if(!Yii::$app->user->isGuest)
																			return $this->phone != Yii::$app->user->identity->phone;
																	}],
			['anotherPhone', UserUserPhoneValidator::className(), 'when' => function(){
																				if(!Yii::$app->user->isGuest)
																					return $this->anotherPhone != Yii::$app->user->identity->another_phone;
																			}],
			['address', 'safe'],
			['additional', 'safe'],
			['orderDelivery', 'required', 'message' => 'Выберите вариант Доставки'],
			['orderPay', 'required', 'message' => 'Выберите вариант оплаты'],
			['couponList', 'safe'],
			['orderDate', 'required', 'message' => 'Выберите время доставки']
		];
	}

	public function save($currency)
	{
		if(!Yii::$app->user->isGuest)
			$this->checkProfile();

		$order = $this->createOrder();

		$transaction = Order::getDb()->beginTransaction();

        if($order->save())
        {
	        foreach($_SESSION['cart']['list'] as $list)
			{
				$orderList = $this->createOrderList($list, $order->id);
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

				//Информируем администратора о новом заказе через телегу
				$this->sendTelegram($currency);

				$this->sendSms($currency);

				return true;
			}
        }
        $transaction->rollBack();

	}

	private function createOrder()
	{
		$userId = 0;
		if(!Yii::$app->user->isGuest)
			$userId = Yii::$app->user->identity->id;

		return new Order([
			'user_id' => $userId,
			'user_name' => $this->name,
			'user_email' => $this->email,
			'user_phone' => $this->phone,
			'user_another_phone' => $this->anotherPhone,
			'user_address' => $this->address,
			'user_comments' => $this->additional,
			'date' => date('Y-m-d H:i:s'),
			'delivery' => $this->orderDelivery,
			'pay' => $this->orderPay,
			'coupon' => $this->couponList,
			'deliveryTime' => $this->orderDate,
		]);
	}

	private function createOrderList($item, $orderId)
	{
		return new OrderList([
			'id_order' => $orderId,
			'id_product' => $item['id_product'],
			'count' => $item['qty'],
			'price' => $item['price'],
			'size' => $item['id_option'],
		]);
	}

	private function checkProfile()
	{
		$needToAdd = false;

		if(Yii::$app->user->identity->phone == NULL)
		{
			$needToAdd = true;
			$data['phone'] = $this->phone;
		}

		if(Yii::$app->user->identity->another_phone == NULL)
		{
			$needToAdd = true;
			$data['another_phone'] = $this->anotherPhone;
		}

		if(Yii::$app->user->identity->address == NULL)
		{
			$needToAdd = true;
			$data['address'] = $this->address;
		}

		if(Yii::$app->user->identity->additional == NULL)
		{
			$needToAdd = true;
			$data['additional'] = $this->additional;
		}

		if(Yii::$app->user->identity->order_email == NULL)
		{
			$needToAdd = true;
			$data['order_email'] = $this->email;
		}

		if($needToAdd == true)
		{
			User::updateAll($data, ['id' => Yii::$app->user->identity->id]);
		}
	}

	public function sendEmail($customer)
	{
		if(!empty($this->email))
		{
			$message = Yii::$app->mailer->compose(['html' => 'order-html'], ['customer' => $customer, 'fileLogo' => 'images/logo_footer.png']);
			$message->setTo($this->email);
			$message->setFrom(['order@pizzamia.com.ua' => 'PizzaMia заказ']);
			$message->setSubject('Заказ № ' . $this->idOrder);

			$message->send();

			return true;
		}

		return false;
	}

	private function sendTelegram($currency)
	{
		$value = $_SESSION['cart']['sum'] * (100 - $_SESSION['cart']['discount']['value']) / 100 + $_SESSION['cart']['delivery']['value'];

		$text = 'Новый заказ № ' . $this->idOrder . ' на имя ' . $this->name . ' Телефон: ' . $this->phone . '. Сумма заказа: ' . $value . ' ' . $currency;
		
		Yii::$app->telegram->sendMessage([
		    'chat_id' => '-380199054',
		    'text' => $text,
		]);
	}

	private function sendSms($currency)
	{
		$value = $_SESSION['cart']['sum'] * (100 - $_SESSION['cart']['discount']['value']) / 100 + $_SESSION['cart']['delivery']['value'];

		$text = htmlspecialchars('Заказ № ' . $this->idOrder . ' На сумму ' . $value . ' ' . $currency);
		$description = '';
		$start_time = 'AUTO';
		$end_time = 'AUTO';
		$rate = 1;
		$lifetime = 4;
		$source = 'Pizza Mia'; // Alfaname

		$recipient = str_replace('+', '', $this->phone);
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