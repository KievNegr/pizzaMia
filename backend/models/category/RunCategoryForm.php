<?php
namespace backend\models\category;

use yii\base\Model;
use backend\models\category\Category;

class RunCategoryForm extends Model
{
	public $idCategory;

	public function rules()
	{
		return [
			['idCategory', 'trim'],
			['idCategory', 'required'],
		];
	}

	public function delete()
	{
		return Category::updateAll(['deleted' => 1], ['id' => $this->idCategory]);
	}

	public function restore()
	{
		return Category::updateAll(['deleted' => 0], ['id' => $this->idCategory]);
	}
}