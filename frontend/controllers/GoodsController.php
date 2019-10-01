<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\Page;
use frontend\models\Category;
use frontend\models\Goods;
use frontend\models\Ingredient;
use frontend\models\order\OrderForm;
use frontend\models\order\Currency;
use frontend\models\order\Delivery;
use frontend\models\order\Pay;
use frontend\controllers\CouponList;
use frontend\models\order\OrderList;
use frontend\controllers\setOrder;
use common\models\LoginForm;
use yii\web\NotFoundHttpException;

class GoodsController extends Controller
{
	public function actionIndex($sef)
	{
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

        $pages = Page::find()->where(['deleted' => 0])->orderBy('page.sort ASC')->asArray()->all();
        $categories = Category::find()
                                ->where(['deleted' => 0])
                                ->asArray()
                                ->all();

        $product = Goods::find()
        					->where(['sef' => $sef])
        					->joinWith(['options' =>
                                                function($query){
                                                        $query->joinWith('optionname', true, 'LEFT JOIN');
                                                    }, 'images'], true, 'LEFT JOIN')
        					->asArray()
        					->one();

        if(!$product)
            throw new NotFoundHttpException();

        $ratioList = OrderList::find()->where(['id_product' => $product['id']])->asArray()->all();

        $count = 0;
        $star = 0;
        foreach ($ratioList as $stars) {
            $star += $stars['ratio'];
            if($stars['ratio'] != NULL || $stars['ratio'] != 0)
                $count++;
        }

        if($count != 0)
        {
            $ratio = round($star / $count);
        }
        else
        {
            $ratio = 0;
        }

        //Получаем список доступных купонов
        $getCoupons = CouponList::getCoupons(); //Достаем все купоны для пользователя

        //Купоны доступные для заказа
        $availableCoupons = $getCoupons['available'];
         
        $product['ingredients'] = explode(',', $product['ingredients']);
        $ingredients = Ingredient::find()->where(['in', 'id', $product['ingredients']])->asArray()->all();

        foreach($delivery as $item)
        {
            $deliveryList[$item['id']] = $item['name'] . ' (' . $item['value'] . ' ' . $currency['name'] . ')';
        }

        foreach($pay as $item)
        {
            $payList[$item['id']] = $item['name'];
        }

        foreach($categories as $category)
        {
            if($category['id'] == $product['category'])
            {
                $parent = $category['parent'];
            }
        }

        foreach($categories as $key => $category)
        {
            if($category['id'] == $parent)
            {
               $categories[$key]['checked'] = true;
            }
        }

		$this->view->params['pages'] = $pages;
        $this->view->params['categories'] = $categories;
        $this->view->params['orderModel'] = $orderModel;
        $this->view->params['currency'] = $currency;
        $this->view->params['deliveryList'] = $deliveryList;
        $this->view->params['payList'] = $payList;
        $this->view->params['couponList'] = $availableCoupons;
        $this->view->params['login'] = $login;

        $this->view->title = $product['title'] . '. Пиццерия Pizza Mia';
        $this->view->params['description'] = $product['title'] . '. Пиццерия Pizza Mia. Заказать вкусную пиццу';

        return $this->render('index', compact('product', 'ingredients', 'currency', 'ratio'));
	}
}