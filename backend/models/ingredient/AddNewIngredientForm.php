<?php

namespace backend\models\ingredient;

use yii\base\Model;
use backend\models\ingredient\Ingredient;
use backend\components\validators\IngredientNameValidator;

class AddNewIngredientForm extends Model
{
	public $nameIngredient;
	public $ingredientImage;

	public function attributeLabels()
	{
		return [
			'nameIngredient' => 'Название ингредиента',
		];
	}

	public function rules()
	{
		return [
			['nameIngredient', 'required'],
			['nameIngredient', IngredientNameValidator::className()],
			['ingredientImage', 'required'],
		];
	}

	public function save()
	{
		$ingredient = new Ingredient;

		$ingredient->name = $this->nameIngredient;
		$ingredient->image = $this->ingredientImage;

		return $ingredient->save();
	}
}