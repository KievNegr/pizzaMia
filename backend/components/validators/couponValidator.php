<?php
namespace backend\components\validators;

use yii\validators\Validator;
use backend\models\setting\Promo;

class couponValidator extends Validator
{
	public function validateAttribute($model, $attribute)
	{
		if(Promo::find()->where(['code' => $model->$attribute])->asArray()->one())
			$this->addError($model, $attribute, 'Такой промокод уже существует');
	}
}