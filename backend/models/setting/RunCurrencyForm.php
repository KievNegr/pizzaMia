<?php
namespace backend\models\setting;

use yii\base\Model;

class RunCurrencyForm extends Model
{
	public $currencyId;

	public function rules()
	{
		return [
			['currencyId', 'required'],
		];
	}

	public function delete()
	{
		return Currency::updateAll(['deleted' => 1], ['id' => $this->currencyId]);
	}

	public function restore()
	{
		return Currency::updateAll(['deleted' => 0], ['id' => $this->currencyId]);
	}
}