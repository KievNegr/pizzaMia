<?php

namespace backend\models\ingredient;

use yii\base\Model;
use backend\models\ingredient\Ingredient;

class RunIngredientForm extends Model
{
	public $idIngredient;

	public function rules()
	{
		return [
			['idIngredient', 'required'],
		];
	}

	public function delete()
	{
		$data['deleted'] = 1;

		return Ingredient::updateAll($data, ['id' => $this->idIngredient]);
	}

	public function restore()
	{
		$data['deleted'] = 0;

		return Ingredient::updateAll($data, ['id' => $this->idIngredient]);
	}
}