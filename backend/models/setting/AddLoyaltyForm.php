<?php
namespace backend\models\setting;

use yii\base\Model;

class AddLoyaltyForm extends Model
{
	public $fromSum;
	public $toSum;
	public $discount;

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
		];
	}

	public function save()
	{
		$data = new Loyalty;

		$data->from_sum = $this->fromSum;
		$data->to_sum = $this->toSum;
		$data->discount = $this->discount;

		return $data->save();
	}
}