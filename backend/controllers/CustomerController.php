<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\models\User;
use backend\models\customer\EditCustomerForm;
use yii\web\Response;
use yii\bootstrap4\ActiveForm;
use backend\models\user\Level;
use backend\models\order\Order;
use backend\models\order\OrderStatus;
use backend\models\order\Coupons;
use backend\models\setting\Currency;

class CustomerController extends Controller
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

		$editModel = new EditCustomerForm;

		if($editModel->load(Yii::$app->request->post()) && $editModel->validate())
		{
			if($editModel->update())
			{
				Yii::$app->session->setFlash('success', 'Данные клиента изменены');
				return $this->refresh();
			}
		}

		$currency = Currency::find()->where(['default_view' => 1])->asArray()->one();
		$userList = User::find()->orderBy(['level' => 'SORT_DESC'])->asArray()->all();
		$levels = Level::find()->asArray()->all();

		return $this->render('index', compact('userList', 'editModel', 'levels', 'currency'));
	}

	public function actionInfo($id)
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

        $user = User::findOne($id);

        if(!$user)
        	return $this->redirect(['index']);

        $orders = Order::find()
        				->where(['user_id' => $id])
        				->joinWith(['orderlist', 'discount'], true, 'LEFT JOIN')
        				->orderBy(['orders.id' => 'SORT_DESC'])
        				->asArray()
        				->all();

        $orderStatus = OrderStatus::find()->asArray()->all();
        $couponList = Coupons::find()->where(['user' => $id])->orWhere(['user' => 0])->asArray()->all();
        $currency = Currency::find()->where(['default_view' => 1])->asArray()->one();

		return $this->render('info', compact('user', 'orders', 'orderStatus', 'couponList', 'currency'));
	}

	public function actionOrder($id)
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

        $order = Order::find()
        				->where(['orders.id' => $id])
        				->joinWith(['orderlist' => 
        								function(yii\db\ActiveQuery $query)
        								{
        									$query->joinWith(['optiongoods' => 
        														function(yii\db\ActiveQuery $query)
        														{
        															$query->joinWith('optionName', true, 'LEFT JOIN');
        														}, 
        														'itemImage', 'item'], true, 'LEFT JOIN');
        								}, 'discount', 'orderStatus', 'coupon', 'pay', 'delivery'], true, 'LEFT JOIN')
        				->asArray()
        				->one();

        if(!$order)
        	return $this->redirect(['index']);

        $currency = Currency::find()->where(['default_view' => 1])->asArray()->one();

		return $this->render('order', compact('order', 'currency'));
	}

	public function actionValidate()
	{
		$model = new EditCustomerForm;
		if( Yii::$app->request->isAjax  && $model->load(Yii::$app->request->post()) )
		{
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
	}
}