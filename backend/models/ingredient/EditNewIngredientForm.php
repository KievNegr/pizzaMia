<?php

namespace backend\models\ingredient;

use yii\base\Model;
use backend\models\ingredient\Ingredient;
use backend\components\validators\IngredientNameValidator;

class EditNewIngredientForm extends Model
{
	public $nameEditIngredient;
	public $ingredientEditImage;
	public $ingredientEditId;
	public $nameNoCheckEditIngredient;

	public function attributeLabels()
	{
		return [
			'nameEditIngredient' => 'Название ингредиента',
		];
	}

	public function rules()
	{
		return[
			['nameEditIngredient', 'required'],
			['nameEditIngredient', IngredientNameValidator::className(), 'when' => function($editModel){return $editModel->nameEditIngredient != $editModel->nameNoCheckEditIngredient;}],

			['ingredientEditImage', 'required'],
			['ingredientEditId', 'required'],
			['nameNoCheckEditIngredient', 'required'],
		];
	}

	public function update()
	{
		if($this->validate())
		{
			$data = [
				'name' => $this->nameEditIngredient,
				'image' => $this->ingredientEditImage,
			];

			return Ingredient::updateAll($data, ['id' => $this->ingredientEditId]);
		}
	}
}