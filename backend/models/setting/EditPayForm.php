<?php
namespace backend\models\setting;

use yii\base\Model;

class EditPayForm extends Model
{
	public $payName;
	public $payCard;
	public $payId;

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
			['payId', 'required'],
		];
	}

	public function update()
	{
		$pay['name'] = $this->payName;
		$pay['card'] = $this->payCard;

		return Pay::updateAll($pay, ['id' => $this->payId]);
	}
}