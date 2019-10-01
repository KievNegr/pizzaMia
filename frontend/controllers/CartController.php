<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\Goods;
use frontend\models\OptionItem;
use frontend\models\order\Cart;
use frontend\models\order\Delivery;
use frontend\models\order\Coupons;

class CartController extends Controller
{
	public function actionAdd()
	{
		if(Yii::$app->request->isAjax)
		{
			$idProduct = Yii::$app->request->post('itemId');
			$idOption = Yii::$app->request->post('optionId');
			$qty = Yii::$app->request->post('qty');
			
			if($idProduct < 1 || $idOption < 1) return false;
			
			$product = Goods::find()->where(['goods.id' => $idProduct])->joinWith('image', true, 'LEFT JOIN')->one();
			if(empty($product)) return false;

			$option = OptionItem::find()
								->where(['options_goods.id' => $idOption, 'options_goods.id_product' => $idProduct])
								->joinWith('optionname', true, 'LEFT JOIN')
								->one();

			if(empty($option)) return false;
			if(empty($option->optionname)) return 'false';

			$session = Yii::$app->session;
			$session->open();

			$cart = new Cart;
			if($qty == 1)
			{
				$cart->addToCart($product, $option, \yii\helpers\Url::to('@thumbs'));
			}
			elseif($qty == 0)
			{
				$cart->removeFromCart($product, $option);
			}
			else
			{
				return false;
			}

			if($session['cart'])
				return json_encode($session['cart']);

			return false;
		}
	}

	public function actionSelectdelivery()
	{
		if(Yii::$app->request->isAjax)
		{
			$idDelivery = Yii::$app->request->post('idDelivery');
			if($idDelivery < 1) return false;

			$delivery = Delivery::findOne($idDelivery);
			if(empty($delivery)) return false;

			$session = Yii::$app->session;
			$session->open();

			$cart = new Cart;

			$cart->updateDelivery($delivery->value, $delivery->id);

			if($session['cart'])
				return json_encode($session['cart']);

			return false;
		}
	}

	public function actionSelectdiscount()
	{
		if(Yii::$app->request->isAjax)
		{
			$idDiscount = Yii::$app->request->post('idDiscount');
			$idUser = Yii::$app->user->identity->id;

			$session = Yii::$app->session;
			$session->open();

			$cart = new Cart;

			if($idDiscount <= 0)
			{
				$cart->updateDiscount(0, 0);

				if($session['cart'])
					return json_encode($session['cart']);
			}

			$discount = Coupons::find()
								->where(['user' => Yii::$app->user->identity->id])
                                ->orWhere(['user' => 0])
                                ->andWhere(['id' => $idDiscount])
                                ->one();

			if(empty($discount)) return false;
			if($discount['expiration'] < date('Y-m-d')) return false;

			$cart->updateDiscount($discount->discount, $discount->id);

			if($session['cart'])
				return json_encode($session['cart']);

			return false;
		}
	}
}