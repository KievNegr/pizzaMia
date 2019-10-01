<?php
namespace backend\components\validators;

use yii\validators\Validator;
use backend\models\ingredient\Ingredient;

class IngredientNameValidator extends Validator 
{
	public function validateAttribute($model, $attribute)
    {
        if( Ingredient::find()->where(['name' => $model->$attribute])->asArray()->one() )
        	$this->addError($model, $attribute, 'Ингредиент ' . $model->$attribute . ' уже есть');
    }
}