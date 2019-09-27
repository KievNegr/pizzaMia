<?php
namespace backend\models\setting;

use yii\base\Model;

class EditLoyaltyForm extends Model
{
	public $fromSum;
	public $toSum;
	public $discount;
	public $loyaltyId;

	public function attributeLabels()
	{
		return [
			'fromSum' => 'Общая сумма заказов от',
			'toSum' => 'Общая сумма заказов до',
			'discount' => 'Размер скидки %',
		];
	}

	public function rules()
	{
		return [
			['fromSum', 'required'],
			['toSum', 'required'],
			['discount', 'required'],
			['loyaltyId', 'required'],
		];
	}

	public function update()
	{
		$data = [
			'from_sum' => $this->fromSum,
			'to_sum' => $this->toSum,
			'discount' => $this->discount,
		];

		return Loyalty::updateAll($data, ['id' => $this->loyaltyId]);
	}
}