<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\order\AddOrderForm;
use backend\models\order\EditOrderForm;
use backend\models\order\Order;
use backend\models\setting\Delivery;
use backend\models\setting\Pay;
use backend\models\order\Coupons;
use backend\models\category\Category;
use backend\models\setting\OrderStatus;
use backend\models\setting\Currency;
use backend\models\user\User;
use yii\web\Response;
use yii\bootstrap4\ActiveForm;

class OrderController extends Controller
{
	public function actionIndex()
	{
		if(Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }
        elseif(Yii::$app->user->identity->level == 3)
        {
            Yii::$app->user->logout();
            return $this->goHome();
        }

		$pathToItemImage = '../../frontend/web/images/goods/thumbs/';
		$pathToItemOption = '../../frontend/web/images/option/';

		$currency = Currency::find()->where(['default_view' => 1])->asArray()->one(); //Текущая валюта сайта

		$newModel = new AddOrderForm;
		$editModel = new EditOrderForm;

		$orders = Order::find()
				  ->joinWith(['orderlist' => function(yii\db\ActiveQuery $query)
				  							{
				  								$query->joinWith(['optiongoods' => function(yii\db\ActiveQuery $query)
		  																	{
		  																		$query->joinWith('optionName', true, 'LEFT JOIN');
		  																	}, 'itemImage'], true, 'LEFT JOIN');
				  							}, 'pay', 'delivery', 'discount' => function(yii\db\ActiveQuery $query)
				  																{
				  																	$query->joinWith('manager', true, 'LEFT JOIN');
				  																}], true, 'LEFT JOIN')
				  ->orderBy(['orders.status' => SORT_ASC, 'orders.updated' => SORT_DESC])
				  ->asArray()
				  ->all();

		$coupons = Coupons::find()->asArray()->all();

		foreach($orders as $key => $order)
		{
			foreach($coupons as $coupon)
			{
				if($coupon['user'] == $order['user_id'] || $coupon['user'] == 0)
				{
					$check = Order::find()
							->where(['coupon' => $coupon['id'], 'user_id' => $order['user_id']])
							->andWhere(['!=', 'id', $order['id']])
							->asArray()
							->all();
					//print_r($check);
					//if($coupon['expiration'] >= date('Y-m-d'))	
					//{
						if(!$check || $coupon['applying'] == 1)
						{
							$orders[$key]['coupons'][] = $coupon;
						}
					//}
				}

			}
		}
		$delivery = Delivery::find()->asArray()->all();
		$pay = Pay::find()->asArray()->all();
		$allOrderStatus = OrderStatus::find()->asArray()->all();
		$goods = Category::find()
				->joinWith(['goods' => function(yii\db\ActiveQuery $query)
								{
									$query->joinWith(['options' => function(yii\db\ActiveQuery $query)
														{
															$query->joinWith('optionName', true, 'LEFT JOIN');
														},
														'images'], true, 'LEFT JOIN');
								},], true, 'LEFT JOIN')
				->asArray()
				->all();
		// echo '<pre>';
		// print_r($orders);
		// //print_r($coupons);
		// echo '</pre>';
		// die;
		foreach($delivery as $item)
		{
			$deliveryList[$item['id']] = $item['name'];
		}

		foreach($pay as $item)
		{
			$payList[$item['id']] = $item['name'];
		}

		$orderStatus = Array();
		foreach($allOrderStatus as $status)
		{
			$orderStatus[$status['id']] = $status['name'];
		}

		$listCategories = Array();
		foreach($goods as $category)
		{
            if(!empty($category['goods']))
            {
	            foreach($category['goods'] as $item)
	            {
	                if($item['online_order'] == 1 && $item['deleted'] == 0)
	                {
	                	$listCategories[$category['title']][$item['id']] = $item['title'];
	                	$goodsList[] = $item;
	                }
	            }
	        }
		}

		if($newModel->load(Yii::$app->request->post()) && $newModel->validate())
		{
			if($newModel->save($currency['name']))
			{
				Yii::$app->session->setFlash('success', 'Новый заказ создан');

				//Отправляем email c заказом
				foreach($delivery as $item)
				{
					if($newModel->orderDelivery == $item['id'])
					{
						$newModel->deliveryName = $item['name'];
						$newModel->deliveryValue = $item['value'];
					}
				}

				foreach($pay as $item)
				{
					if($newModel->orderPay == $item['id'])
						$newModel->payName = $item['name'];
				}

				$newModel->discountValue = 0;

				if($newModel->orderDiscount)
				{
					$newModel->discountValue = $newModel->orderDiscount;
					echo $newModel->orderDiscount;
				}

				if($newModel->couponId)
				{
					foreach($coupons as $coupon)
					{
						if($newModel->couponId == $coupon['id'])
							$newModel->discountValue = $coupon['discount'];
					}
				}

				foreach ($newModel->table as $key => $value) {
					foreach($goodsList as $item)
					{
						if($value[0] == $item['id'])
						{
							$newModel->table[$key]['product_name'] = $item['title'];
							$newModel->table[$key]['qty'] = $value[1];
							$newModel->table[$key]['price'] = $value[2];
						}
					}
				}

                $newModel->sendEmail($newModel);

                //Информируем администратора о новом заказе через телегу
				$newModel->sendTelegram($newModel);

				$newModel->sendSms($newModel);

				return $this->refresh();
			}
		}

		if($editModel->load(Yii::$app->request->post()) && $editModel->validate())
		{
			if($editModel->update())
			{
				Yii::$app->session->setFlash('success', 'Заказ изменен');
				return $this->refresh();
			}
		}
		
		return $this->render('index', compact('newModel', 'editModel', 'orders', 'deliveryList', 'payList', 'listCategories', 'goodsList', 'pathToItemImage', 'pathToItemOption', 'allOrderStatus', 'orderStatus', 'currency'));
	}

	public function actionGetcustomer()
	{
		if(Yii::$app->request->isAjax)
		{
			$phone = Yii::$app->request->post('phone');

			$user = (new \yii\db\Query())
				    ->select('user.id, user.username, user.phone, user.another_phone, user.email, user.address, user.additional, coupons.code, coupons.expiration, coupons.discount, coupons.applying, coupons.deleted, coupons.id AS couponID')
				    ->from('user')
				    ->where(['phone' => $phone])
					->orWhere(['another_phone' => $phone])
					->join('LEFT JOIN', 'coupons', 'coupons.user = user.id OR coupons.user = 0')
				    ->all();
			
			foreach($user as $key => $value)
			{
				$coupons['id'] = $value['id'];
				$coupons['username'] = $value['username'];
				$coupons['another_phone'] = $value['another_phone'];
				if($value['another_phone'] == $phone)
				$coupons['another_phone'] = $value['phone'];
				$coupons['email'] = $value['email'];
				$coupons['address'] = $value['address'];
				$coupons['additional'] = $value['additional'];

				if($value['deleted'] == 0)
				{
					if($value['expiration'] > date('Y-m-d'))
					{
						$check = Order::find()->where(['coupon' => $value['couponID'], 'user_id' => $value['id']])->exists();
						if(!$check || $value['applying'] == 1)
						{
							$dateExpiration = substr($value['expiration'], 8, 2) . '.' . substr($value['expiration'], 5, 2) . '.' . substr($value['expiration'], 0, 4);

							$coupons['coupons'][$key] = [
														'id' => $value['couponID'], 
														'code' => $value['code'], 
														'expiration' => $dateExpiration, 
														'discount' => $value['discount'], 
													];
						}
					}
				}
			}
			return json_encode($coupons);
		}
	}

	public function actionGetdelivery()
	{
		if(Yii::$app->request->isAjax)
		{
			$id = Yii::$app->request->post('id');

			$delivery = Delivery::findOne($id);

			return $delivery->value;
		}
	}

	public function actionValidate()
	{
		$modelNew = new AddOrderForm;
		$modelEdit = new EditOrderForm;

		if( Yii::$app->request->isAjax)
		{
			Yii::$app->response->format = Response::FORMAT_JSON;

			if($modelNew->load(Yii::$app->request->post()))
			{
				return ActiveForm::validate($modelNew);
			}
			elseif($modelEdit->load(Yii::$app->request->post()))
			{
				return ActiveForm::validate($modelEdit);
			}
			
		}
	}
}