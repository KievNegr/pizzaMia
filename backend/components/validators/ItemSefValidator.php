<?php
namespace backend\components\validators;

use yii\validators\Validator;
use backend\models\goods\Goods;

class ItemSefValidator extends Validator
{
	public function validateAttribute($model, $attribute)
	{
		if( Goods::find()->where(['sef' => $model->$attribute])->asArray()->one() )
			$this->addError($model, $attribute, 'Такое имя ссылки уже существует');
	}
}