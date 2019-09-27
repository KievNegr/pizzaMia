<?php

namespace backend\components\validators;

use yii\validators\Validator;
use backend\models\user\User;

class UserUserEmailValidator extends Validator 
{
	public function validateAttribute($model, $attribute)
    {
        if( User::find()->where(['email' => $model->$attribute])->asArray()->one() )
        	$this->addError($model, $attribute, 'Почта ' . $model->$attribute . ' уже есть');
    }
}