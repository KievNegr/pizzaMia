<?php
namespace frontend\models\order;

use yii\db\ActiveRecord;

class Order extends ActiveRecord
{
	public static function tableName()
	{
		return 'orders';
	}

	public function getOrderlist()
	{
		return $this->hasMany(OrderList::className(), ['id_order' => 'id']);
	}

	public function getPay()
	{
		return $this->hasOne(Pay::className(), ['id' => 'pay']);
	}

	public function getDelivery()
	{
		return $this->hasOne(Delivery::className(), ['id' => 'delivery']);
	}

	public function getOrderStatus()
	{
		return $this->hasOne(OrderStatus::className(), ['id' => 'status']);
	}

	public function getDiscount()
	{
		return $this->hasOne(Discount::className(), ['id' => 'discount']);
	}
}