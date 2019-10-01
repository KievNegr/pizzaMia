<?php
namespace backend\models\setting;

use yii\base\Model;
use backend\components\validators\CurrencyNameValidator;

class AddCurrencyForm extends Model
{
	public $currencyName;
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
			['currencyName', 'required'],
			['currencyName', CurrencyNameValidator::className()],
			['currencyDefaultView', 'required'],
		];
	}

	public function update()
	{
		if($this->currencyDefaultView == 1)
			$this->updateView();

		$data = new Currency([
			'name' => $this->currencyName,
			'default_view' => $this->currencyDefaultView,
		]);

		return $data->save();
	}

	private function updateView()
	{
		Currency::updateAll(['default_view' => 0]);
	}
}