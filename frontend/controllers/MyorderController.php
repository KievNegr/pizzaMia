<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\Page;
use frontend\models\Category;
use yii\bootstrap4\ActiveForm;
use yii\web\Response;
use frontend\models\order\OrderForm;
use frontend\models\order\Currency;
use frontend\models\order\Delivery;
use frontend\models\order\Pay;
use frontend\models\order\Coupons;
use frontend\models\order\Order;
use frontend\controllers\CouponList;
use frontend\models\order\OrderList;
use frontend\models\order\OrderStatus;
use frontend\controllers\setOrder;
use common\models\LoginForm;

class MyorderController extends Controller
{
    public function actionIndex()
	{
		if(Yii::$app->user->isGuest)
            return $this->goHome();

        //Классическая форма входа на сайт 
        $login = new LoginForm();
        if ($login->load(Yii::$app->request->post()) && $login->login()) {
            //return $this->goBack();
        } else {
            $login->password = '';
        }

        $delivery = Delivery::find()->where(['deleted' => 0])->asArray()->all(); //Массив с вариантами доставки
        $pay = Pay::find()->where(['deleted' => 0])->asArray()->all(); //Массив с вариантами оплаты
        $currency = Currency::find()->where(['default_view' => 1])->asArray()->one(); //Текущая валюта сайта

        $orderModel = new OrderForm; //Обьект нового заказа
        if($orderModel->load(Yii::$app->request->post())) //Если заказ был создан
        {
            if($orderModel->save($currency['name'])) //Если заказ со списком товаров успешно добавлен в базу orders и orderlist
            {
                $session = Yii::$app->session; //Открываем сессию
                
                //Формируем сообщение об успешном создании заказа
                setOrder::create($orderModel, $delivery, $pay, $currency);

                //Отправляем email c заказом
                $orderModel->sendEmail($orderModel);

                $session->remove('cart'); //Очищаем корзину
                
                return $this->refresh(); //Перегружаем страницу
            }
        }

        //Получаем список доступных купонов
        $getCoupons = CouponList::getCoupons(); //Достаем все купоны для пользователя

        //Купоны доступные для заказа
        $availableCoupons = $getCoupons['available'];

        //Купоны для отображения в списке заказов
        $coupons = $getCoupons['all'];
        
        $pages = Page::find()->where(['deleted' => 0])->orderBy('page.sort ASC')->asArray()->all();
        $categories = Category::find()
                                ->where(['deleted' => 0, 'parent' => 0])
                                ->asArray()
                                ->all();
    
        foreach($delivery as $item)
        {
            $deliveryList[$item['id']] = $item['name'] . ' (' . $item['value'] . ' ' . $currency['name'] . ')';
        }

        foreach($pay as $item)
        {
            $payList[$item['id']] = $item['name'];
        }

        $myOrder = Order::find()
                        ->where(['user_id' => Yii::$app->user->identity->id])
                        ->orderBy('orders.id DESC')
                        ->joinWith(['orderlist' => function(yii\db\ActiveQuery $query)
                                            {
                                                $query->joinWith(['optiongoods' => function(yii\db\ActiveQuery $query)
                                                                    {
                                                                        $query->joinWith('optionName', true, 'LEFT JOIN');
                                                                    }, 'item', 'itemImage'], true, 'LEFT JOIN');
                                            }], true, 'LEFT JOIN')
                        ->joinWith('orderStatus', true, 'LEFT JOIN')
                        ->joinWith('discount', true, 'LEFT JOIN')
                        ->asArray()
                        ->all();

		$this->view->params['pages'] = $pages;
        $this->view->params['categories'] = $categories;
        $this->view->params['orderModel'] = $orderModel;
        $this->view->params['currency'] = $currency;
        $this->view->params['deliveryList'] = $deliveryList;
        $this->view->params['payList'] = $payList;
        $this->view->params['couponList'] = $availableCoupons;
        $this->view->params['login'] = $login;
        
        $this->view->title = 'Мои заказы. Пиццерия Pizza Mia';
        $this->view->params['description'] = 'Мои заказы. Заказать вкусную пиццу';

        return $this->render('index', compact('myOrder', 'currency', 'coupons'));
	}

    public function actionShow($id = 0)
    {   
        if($id == 0 || Yii::$app->user->isGuest)
            return $this->goHome();

        //Классическая форма входа на сайт 
        $login = new LoginForm();
        if ($login->load(Yii::$app->request->post()) && $login->login()) {
            //return $this->goBack();
        } else {
            $login->password = '';
        }
        
        $myOrder = Order::find()
                        ->where(['user_id' => Yii::$app->user->identity->id, 'orders.id' => $id])
                        ->joinWith(['orderlist' => function(yii\db\ActiveQuery $query)
                                            {
                                                $query->joinWith(['optiongoods' => function(yii\db\ActiveQuery $query)
                                                                    {
                                                                        $query->joinWith('optionName', true, 'LEFT JOIN');
                                                                    }, 'item', 'itemImage'], true, 'LEFT JOIN');
                                            }], true, 'LEFT JOIN')
                        ->joinWith('orderStatus', true, 'LEFT JOIN')
                        ->joinWith('discount', true, 'LEFT JOIN')
                        ->asArray()
                        ->one();
        if(!$myOrder)
            return $this->redirect(['myorder/']);
        
        $delivery = Delivery::find()->asArray()->all(); //Массив с вариантами доставки
        $pay = Pay::find()->asArray()->all(); //Массив с вариантами оплаты
        $currency = Currency::find()->where(['default_view' => 1])->asArray()->one(); //Текущая валюта сайта

        $orderModel = new OrderForm;
        if($orderModel->load(Yii::$app->request->post())) //Если заказ был создан
        {
            if($orderModel->save($currency['name'])) //Если заказ со списком товаров успешно добавлен в базу orders и orderlist
            {
                $session = Yii::$app->session; //Открываем сессию
                
                //Формируем сообщение об успешном создании заказа
                setOrder::create($orderModel, $delivery, $pay, $currency);

                //Отправляем email c заказом
                $orderModel->sendEmail($orderModel);

                $session->remove('cart'); //Очищаем корзину
                
                return $this->refresh(); //Перегружаем страницу
            }
        }

        //Получаем список доступных купонов
        $getCoupons = CouponList::getCoupons(); //Достаем все купоны для пользователя

        //Купоны доступные для заказа
        $availableCoupons = $getCoupons['available'];

        //Купоны для отображения в списке заказов
        $coupons = $getCoupons['all'];

        $pages = Page::find()->where(['deleted' => 0])->orderBy('page.sort ASC')->asArray()->all();
        $categories = Category::find()
                                ->where(['deleted' => 0, 'parent' => 0])
                                ->asArray()
                                ->all();
    
        foreach($delivery as $item)
        {
            $deliveryList[$item['id']] = $item['name'] . ' (' . $item['value'] . ' ' . $currency['name'] . ')';
        }

        foreach($pay as $item)
        {
            $payList[$item['id']] = $item['name'];
        }

        

        $this->view->params['pages'] = $pages;
        $this->view->params['categories'] = $categories;
        $this->view->params['orderModel'] = $orderModel;
        $this->view->params['currency'] = $currency;
        $this->view->params['deliveryList'] = $deliveryList;
        $this->view->params['payList'] = $payList;
        $this->view->params['couponList'] = $availableCoupons;
        $this->view->params['login'] = $login;
        
        $this->view->title = 'Заказ №' . $myOrder['id'] . '. Пиццерия Pizza Mia';
        $this->view->params['description'] = 'Заказ №' . $myOrder['id'] . '. Заказать вкусную пиццу';

        return $this->render('show', compact('myOrder', 'currency', 'coupons', 'delivery', 'pay'));

    }

    public function actionSetratio()
    {
        if(Yii::$app->request->isAjax)
        {
            if(Yii::$app->user->isGuest) return false;

            $stars = Yii::$app->request->post('stars');
            $item = Yii::$app->request->post('item');
            $user = Yii::$app->user->identity->id;

            if($stars < 1 || $stars > 5) return false;

            $orderlist = OrderList::findOne($item);

            if(!$orderlist) return false;

            $order = Order::find()
                            ->where(['id' => $orderlist['id_order'], 'user_id' => $user])
                            ->one();

            if(!$order) return false;

            $status = OrderStatus::find()->where(['set_ratio' => 1])->one();

            if($order->status != $status->id) return false;

            OrderList::updateAll(['ratio' => $stars], ['id' => $item]);

            return json_encode(['stars' => $stars, 'item' => $item]);
        }
    }
}