<?php

namespace backend\components\validators;

use yii\validators\Validator;
use backend\models\category\Category;

class CategoryNameValidator extends Validator 
{
	public function validateAttribute($model, $attribute)
    {
        if( Category::find()->where(['title' => $model->$attribute])->asArray()->one() )
        	$this->addError($model, $attribute, 'Категория ' . $model->$attribute . ' уже есть');
    }
}