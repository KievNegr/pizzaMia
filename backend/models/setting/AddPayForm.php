<?php
namespace backend\models\setting;

use yii\base\Model;

class AddPayForm extends Model
{
	public $payName;
	public $payCard;

	public function attributeLabels()
	{
		return [
			'payName' => 'Название варианта оплаты',
			'payCard' => 'Возможность оплаты картой',
		];
	}

	public function rules()
	{
		return [
			['payName', 'required'],
			['payCard', 'safe'],
		];
	}

	public function save()
	{
		$pay = new Pay;

		$pay->name = $this->payName;
		$pay->card = $this->payCard;

		if($pay->save())
		{
			return true;
		}
	}
}