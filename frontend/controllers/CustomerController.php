<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\Page;
use frontend\models\Category;
use frontend\models\Customer;
use yii\bootstrap4\ActiveForm;
use yii\web\Response;
use frontend\models\order\OrderForm;
use frontend\models\order\Currency;
use frontend\models\order\Delivery;
use frontend\models\order\Pay;
use frontend\controllers\CouponList;
use frontend\controllers\setOrder;
use common\models\LoginForm;
use frontend\models\AddImage;
use yii\web\UploadedFile;
use backend\models\Image;

class CustomerController extends Controller
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
        $pay = Pay::find()->asArray()->where(['deleted' => 0])->all(); //Массив с вариантами оплаты
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

        $image = new AddImage; //Новый аватар для клиента

        $editModel = new Customer;

        if($editModel->load(Yii::$app->request->post()) && $editModel->validate())
        {
            if($editModel->updateData())
            {
                Yii::$app->session->setFlash('success', 'Информация обновлена');
                return $this->refresh();
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
        
        $this->view->title = 'Мои настройки. Пиццерия Pizza Mia';
        $this->view->params['description'] = 'Мои настройки. Заказать вкусную пиццу';

        return $this->render('index', compact('editModel', 'image'));
	}

    public function actionValidate()
    {   
        $model = new Customer;
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    public function actionOrdervalidate()
    {   
        $model = new OrderForm;
        if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    public function actionUploadimage()
    {
        if(Yii::$app->request->isAjax)
        {
            $image = new AddImage; //Создаем новый обьект изображения
            $image->image = UploadedFile::getInstance($image, 'image'); //Достаем инфу про изображение

            $avatar = $image->upload(); //Загружаем изображение

            $size = getimagesize('images/avatar/' . $avatar);

            // if($size[0] <= $size[1])
            // {
            //     $width = $size[0];
            //     $height = $size[0];
            // }
            // else
            // {
            //     $width = $size[1];
            //     $height = $size[1];
            // }

            // $image = new Image;
            // $image->crop(0, 0, $width, $height, 'images/avatar/' . $avatar);
            // $image->save();

            $data = null;

            $class = substr($avatar, 0, -4);

            $data['text'] = '<div class="image ' . $class . '" style="background-image: url(images/avatar/' . $avatar . '?' . rand() . ');">
                            <div class="imageRun" nameImage="' . $avatar . '">
                                <i class="fas fa-crop-alt imageCrop" particularclass="' . $class . '" address="images/avatar/' . $avatar . '" imageWidth="' . $size[0] . '" imageHeight="' . $size[1] . '"  title="Кадрировать изображение"></i>
                            </div>
                        </div>';

            $data['img'] = $avatar;

            return json_encode($data);
        }
    }

    public function actionCrop()
    {
        if(Yii::$app->request->isAjax)
        {
            $gotData = Yii::$app->request->post('data');

            $pointX = $gotData['pointX'];
            $pointY = $gotData['pointY'];
            $areaWidth = $gotData['areaWidth'];
            $areaHeight = $gotData['areaHeight'];
            $source = $gotData['source'];

            $image = new Image;
            $image->crop($pointX, $pointY, $areaWidth, $areaHeight, $source);
            
            $image->save();

            return $source;
        }
    }
}