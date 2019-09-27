<?php
namespace backend\components\validators;

use yii\validators\Validator;
use backend\models\setting\Currency;

class CurrencyNameValidator extends Validator
{
	public function validateAttribute($model, $attribute)
	{	
		if( Currency::find()->where(['name' => $model->$attribute])->asArray()->one() )
			$this->addError($model, $attribute, 'Такая валюта уже существует');
	}
}