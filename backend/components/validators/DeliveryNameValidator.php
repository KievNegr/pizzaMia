<?php

namespace backend\components\validators;

use yii\validators\Validator;
use backend\models\setting\Delivery;

class DeliveryNameValidator extends Validator 
{
	public function validateAttribute($model, $attribute)
    {
        if( Delivery::find()->where(['name' => $model->$attribute])->asArray()->one() )
        	$this->addError($model, $attribute, 'Такое название доставки уже существует');
    }
}