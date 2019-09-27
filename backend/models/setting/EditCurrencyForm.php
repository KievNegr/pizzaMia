<?php
namespace backend\models\setting;

use yii\base\Model;
use backend\components\validators\CurrencyNameValidator;

class EditCurrencyForm extends Model
{
	public $currencyId;
	public $currencyName;
	public $currencyNoCheckName;
	public $currencyDefaultView;

	public function attributeLabels()
	{
		return [
			'currencyName' => 'Название валюты (грн, уе)',
			'currencyDefaultView' => 'Отображение на сайте',
		];
	}

	public function rules()
	{
		return [
			['currencyId', 'required'],
			['currencyName', 'required'],
			['currencyName', CurrencyNameValidator::className(), 'when' => function(){return $this->currencyName != $this->currencyNoCheckName;}],
			['currencyNoCheckName', 'required'],
			['currencyDefaultView', 'required'],
		];
	}

	public function update()
	{
		if($this->currencyDefaultView == 1)
			$this->updateView();

		return Currency::updateAll(['name' => $this->currencyName, 'default_view' => $this->currencyDefaultView], ['id' => $this->currencyId]);
	}

	private function updateView()
	{
		Currency::updateAll(['default_view' => 0]);
	}
}