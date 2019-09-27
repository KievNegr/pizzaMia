<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\LoginForm;
use common\models\Auth;
use frontend\components\AuthHandler;
use common\models\User;
use frontend\models\Page;
use frontend\models\Category;
use frontend\models\order\OrderForm;
use frontend\models\order\Currency;
use frontend\models\order\Delivery;
use frontend\models\order\Pay;
use frontend\controllers\CouponList;
use frontend\controllers\setOrder;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;

class UserController extends Controller
{
	public function actionResetpassword()
	{
		if(!Yii::$app->user->isGuest)
		{
			return $this->goHome();
		}

		$login = new LoginForm();
        if ($login->load(Yii::$app->request->post()) && $login->login()) {
            return $this->goBack();
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

        $pages = Page::find()->where(['deleted' => 0])->orderBy('page.sort ASC')->asArray()->all();
        $categories = Category::find()
                                ->where(['deleted' => 0, 'parent' => 0])
                                ->asArray()
                                ->all();

        $listGoods = Category::find()
                                ->where(['main' => 1])
                                ->joinWith(['goods' => 
                                                function($query){
                                                    $query->joinWith(['options' =>
                                                                        function($query){
                                                                                $query->joinWith('optionname', true, 'LEFT JOIN');
                                                                            }, 'image'], true, 'LEFT JOIN');
                                                }], 
                                            true, 'LEFT JOIN')
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

        $this->view->title = 'Восстановление пароля';
        $this->view->params['description'] = 'Восстановление пароля';

        $reset = new PasswordResetRequestForm();
        if ($reset->load(Yii::$app->request->post()) && $reset->validate()) {
            if ($reset->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Проверьте свою електронную почту и следуйте дальнейшим инструкциям. Если по каким-то причинам Вы не видите нашего письма, пожалуйста проверьте папку "спам"');
            } else {
                Yii::$app->session->setFlash('error', 'Нам не удалось отправить Вам письмо.');
            }
        }

		return $this->render('reset', compact('listGoods', 'currency', 'reset'));
	}

	public function actionNewpassword($token = null)
    {
        if(!Yii::$app->user->isGuest)
		{
			return $this->goHome();
		}

		$login = new LoginForm();
        if ($login->load(Yii::$app->request->post()) && $login->login()) {
            return $this->goBack();
        } else {
            $login->password = '';
        }

		$delivery = Delivery::find()->asArray()->all(); //Массив с вариантами доставки
        $pay = Pay::find()->asArray()->all(); //Массив с вариантами оплаты
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

        $pages = Page::find()->where(['deleted' => 0])->orderBy('page.sort ASC')->asArray()->all();
        $categories = Category::find()
                                ->where(['deleted' => 0, 'parent' => 0])
                                ->asArray()
                                ->all();

        $listGoods = Category::find()
                                ->where(['main' => 1])
                                ->joinWith(['goods' => 
                                                function($query){
                                                    $query->joinWith(['options' =>
                                                                        function($query){
                                                                                $query->joinWith('optionname', true, 'LEFT JOIN');
                                                                            }, 'image'], true, 'LEFT JOIN');
                                                }], 
                                            true, 'LEFT JOIN')
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

        $this->view->title = 'Восстановление пароля';
        $this->view->params['description'] = 'Восстановление пароля';

        try {
            $newPassword = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
            //return $this->goHome();
        }

        if ($newPassword->load(Yii::$app->request->post()) && $newPassword->validate() && $newPassword->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Новый пароль был сохранен.');

            //return $this->goHome();
        }

        return $this->render('newpassword', compact('listGoods', 'currency', 'newPassword'));
    }

    public function actionSignup()
    {
        if(!Yii::$app->user->isGuest)
		{
			return $this->goHome();
		}

		$login = new LoginForm();
        if ($login->load(Yii::$app->request->post()) && $login->login()) {
            return $this->goBack();
        } else {
            $login->password = '';
        }

		$delivery = Delivery::find()->asArray()->all(); //Массив с вариантами доставки
        $pay = Pay::find()->asArray()->all(); //Массив с вариантами оплаты
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

        $pages = Page::find()->where(['deleted' => 0])->orderBy('page.sort ASC')->asArray()->all();
        $categories = Category::find()
                                ->where(['deleted' => 0, 'parent' => 0])
                                ->asArray()
                                ->all();

        $listGoods = Category::find()
                                ->where(['main' => 1])
                                ->joinWith(['goods' => 
                                                function($query){
                                                    $query->joinWith(['options' =>
                                                                        function($query){
                                                                                $query->joinWith('optionname', true, 'LEFT JOIN');
                                                                            }, 'image'], true, 'LEFT JOIN');
                                                }], 
                                            true, 'LEFT JOIN')
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

        $this->view->title = 'Регистрация';
        $this->view->params['description'] = 'Регистрация';

        $signup = new SignupForm();
        if ($signup->load(Yii::$app->request->post()) && $signup->signup()) {
            Yii::$app->session->setFlash('success-register', 'Вы успешно зарегистрировались, используйте свой email и пароль для входа на сайт.');
            return $this->goHome();
        }

        return $this->render('signup', compact('listGoods', 'currency', 'signup'));
    }
}