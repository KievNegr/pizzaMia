<?php

namespace frontend\components\validators;

use yii\validators\Validator;
use backend\models\user\User;

class UserUserPhoneValidator extends Validator 
{
	public function validateAttribute($model, $attribute)
    {
        if( User::find()->where(['phone' => $model->$attribute])->orWhere(['another_phone' => $model->$attribute])->asArray()->one() )
        	$this->addError($model, $attribute, 'Введите свой номер телефона');
    }
}