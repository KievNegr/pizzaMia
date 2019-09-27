<?php
namespace backend\components\validators;

use yii\validators\Validator;
use backend\models\page\Page;

class PageSefValidator extends Validator
{
	public function validateAttribute($model, $attribute)
	{	
		if( Page::find()->where(['sef' => $model->$attribute])->asArray()->one() )
			$this->addError($model, $attribute, 'Такое имя ссылки уже существует');
	}
}