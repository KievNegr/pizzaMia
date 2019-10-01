<?php
namespace backend\models\option;

use yii\base\Model;

class AddOptionForm extends Model
{
	public $newTitle;
	public $newDescription;
	public $newCategory;
	public $newImage;

	public function attributeLabels()
	{
		return [
			'newTitle' => 'Название опции',
			'newDescription' => 'Короткое описание',
			'newCategory' => 'Категория',
		];
	}

	public function rules()
	{
		return [
			['newTitle', 'required'],
			['newDescription', 'trim'],
			['newCategory', 'required'],
			['newImage', 'required', 'message' => 'Изображение опции должно быть загружено'],
		];
	}

	public function save()
	{
		$data = new Option;

		$data->title = $this->newTitle;
		$data->description = $this->newDescription;
		$data->id_category = $this->newCategory;
		$data->image = $this->newImage;

		return $data->save();
	}
}