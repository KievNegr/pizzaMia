<?php
namespace backend\models\setting;

use yii\base\Model;
use backend\components\validators\DeliveryNameValidator;

class EditDeliveryForm extends Model
{
	public $deliveryName;
	public $deliveryNameNoCheck;
	public $deliveryValue;
	public $deliveryId;

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
			['deliveryName', DeliveryNameValidator::className(), 'when' => function($editModel){return $editModel->deliveryNameNoCheck != $editModel->deliveryName;}],
			['deliveryNameNoCheck', 'safe'],
			['deliveryValue', 'trim'],
			['deliveryId', 'required'],
		];
	}

	public function update()
	{
		$delivery['name'] = $this->deliveryName;
		$delivery['value'] = $this->deliveryValue;

		return Delivery::updateAll($delivery, ['id' => $this->deliveryId]);
	}
}