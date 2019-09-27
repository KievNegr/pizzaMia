<?php
namespace backend\models\setting;

use yii\base\Model;

class RunOrderStatus extends Model
{
	public $orderId;

	public function rules()
	{
		return [
			['orderId', 'required'],
		];
	}

	public function delete()
	{
		return OrderStatus::updateAll(['deleted' => 1], ['id' => $this->orderId]);
	}

	public function restore()
	{
		return OrderStatus::updateAll(['deleted' => 0], ['id' => $this->orderId]);
	}
}