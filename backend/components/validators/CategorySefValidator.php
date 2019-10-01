<?php
namespace backend\components\validators;

use yii\validators\Validator;
use backend\models\category\Category;

class CategorySefValidator extends Validator
{
	public function validateAttribute($model, $attribute)
	{	
		if( Category::find()->where(['sef' => $model->$attribute])->asArray()->one() )
			$this->addError($model, $attribute, 'Такое имя ссылки уже существует');
	}
}