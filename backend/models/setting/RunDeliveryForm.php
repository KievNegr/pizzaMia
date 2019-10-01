<?php
namespace backend\models\setting;

use yii\base\Model;

class RunDeliveryForm extends Model
{
	public $deliveryId;

	public function rules()
	{
		return [
			['deliveryId', 'required'],
		];
	}

	public function delete()
	{
		return Delivery::updateAll(['deleted' => 1], ['id' => $this->deliveryId]);
	}

	public function restore()
	{
		return Delivery::updateAll(['deleted' => 0], ['id' => $this->deliveryId]);
	}
}