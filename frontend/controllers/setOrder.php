<?php
namespace frontend\controllers;

use Yii;
use frontend\models\order\LiqPay;

class setOrder {

	public static function create($orderModel, $delivery, $pay, $currency)
	{
		//Информируем шаблон, что нужно открыть окно успешно созданного заказа
        Yii::$app->session->setFlash('orderSuccess', 'Заказ успешно создан');

        //Передаем в окно ID заказа
        Yii::$app->session->setFlash('orderId', $orderModel->idOrder);

        //Передаем в окно имя клиента
        Yii::$app->session->setFlash('orderName', $orderModel->name);

        //Передаем в окно телефон клиента
        Yii::$app->session->setFlash('orderPhone', $orderModel->phone);

        //Передаем в окно дополнительный телефон клиента
        Yii::$app->session->setFlash('orderAnotherPhone', $orderModel->anotherPhone);

        //Ищем название способа доставки
        foreach($delivery as $orderDelivery)
        {
            if($orderDelivery['id'] == $orderModel->orderDelivery) //Если вариант доставки найден

                //Устанавлиеваем название способа доставки для информации в email
                $orderModel->orderDelivery = $orderDelivery['name'];

                //Передаем в окно способ доставки
                Yii::$app->session->setFlash('orderDelivery', $orderModel->orderDelivery);
        }

        //Передаем в окно адрес доставки
        Yii::$app->session->setFlash('orderAddress', $orderModel->address);

        //Передаем в окно дополнительную информацию
        Yii::$app->session->setFlash('orderAdditional', $orderModel->additional);

        //Передаем в окно дату доставки
        Yii::$app->session->setFlash('orderDate', $orderModel->orderDate);

        //Передаем в окно email клиента
        Yii::$app->session->setFlash('orderEmail', $orderModel->email);

        //Устанавливаем название валюты для отправки почты
        $orderModel->currency = $currency['name'];

        //Ищем название способа оплаты
        foreach($pay as $orderPay)
        {
            if($orderPay['id'] == $orderModel->orderPay) //Если вариант оплаты найден
            {
                //Устанавлиеваем название способа оплаты для информации в email
                $orderModel->orderPay = $orderPay['name'];

                //Если метод оплаты выбран оплата картой через интернет то
                if($orderPay['card'] == 1)
                {
                    $liqpay = new LiqPay('i93556656368', 'jkiBXvK7rNDoN7dmJ4eRJeo02mNo8btsIxeWtdio');

                    $html = $liqpay->cnb_form(array(
                        'action'         => 'pay',
                        'amount'         => $_SESSION['cart']['sum'] * (100 - $_SESSION['cart']['discount']['value']) / 100,
                        'currency'       => 'UAH',
                        'description'    => 'Оплата заказа на сайте Pizza Mia',
                        'order_id'       => $orderModel->idOrder,
                        'version'        => '3',
                        //'result_url'     => Yii::$app->request->absoluteUrl,
                        ));

                    Yii::$app->session->setFlash('payCard', $html);
                }
            }
            
            //Передаем в окно способ оплаты
            Yii::$app->session->setFlash('orderPay', $orderModel->orderPay);
        }

        //Передаем сумму к оплате
        Yii::$app->session->setFlash('paySum', $_SESSION['cart']['sum'] * (100 - $_SESSION['cart']['discount']['value']) / 100);
	}

}