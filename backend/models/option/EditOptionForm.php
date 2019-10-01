<?php
namespace backend\models\option;

use yii\base\Model;

class EditOptionForm extends Model
{
	public $editTitle;
	public $editDescription;
	public $editCategory;
	public $editImage;
	public $editOptionId;

	public function attributeLabels()
	{
		return [
			'editTitle' => 'Название опции',
			'editDescription' => 'Описание опции',
			'editCategory' => 'Категория опции',
		];
	}

	public function rules()
	{
		return [
			['editTitle', 'required'],
			['editDescription', 'required'],
			['editCategory', 'required'],
			['editImage', 'required'],
			['editOptionId', 'required'],
		];
	}

	public function update()
	{
		$data = [
			'title' => $this->editTitle,
			'description' => $this->editDescription,
			'id_category' => $this->editCategory,
			'image' => $this->editImage,
		];

		return Option::updateAll($data, ['id' => $this->editOptionId]);
	}
}