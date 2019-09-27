<?php
namespace backend\models\setting;

use yii\base\Model;
use backend\components\validators\DeliveryNameValidator;

class AddDeliveryForm extends Model
{
	public $deliveryName;
	public $deliveryValue;

	public function attributeLabels()
	{
		return [
			'deliveryName' => 'Название варианта доставки',
			'deliveryValue' => 'Стоимость доставки',
		];
	}

	public function rules()
	{
		return [
			['deliveryName', 'required'],
			['deliveryName', DeliveryNameValidator::className()],

			['deliveryValue', 'trim'],
		];
	}

	public function save()
	{
		$delivery = new Delivery;

		$delivery->name = $this->deliveryName;
		$delivery->value = $this->deliveryValue;

		return $delivery->save();
	}
}