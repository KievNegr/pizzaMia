<?php
namespace backend\models\Category;

use yii\base\Model;
use backend\models\category\Category;
use backend\components\validators\CategoryNameValidator;
use backend\components\validators\CategorySefValidator;
use backend\models\Image;

class EditForm extends Model {

	public $editName;
	public $editNoCheckName;
	public $editSeo;
	public $editNoCheckSef;
	public $editText;
	public $editParent;
	public $editIcon;
	public $editId;
	public $categoryMain;

	public function attributeLabels()
	{
		return [
			'editName' => 'Название категории',
			'editSeo' => 'Генерация ссылки категории',
			'editText' => 'Текст категории',
			'editParent' => 'Выбор родительской категории',
			'editIcon' => 'Изображение категории (квадрат, формат PNG)',
			'categoryMain' => 'Отображать на главной',
		];
	}

	public function rules()
	{
		return [
			['editNoCheckName', 'trim'],
			['editNoCheckSef', 'trim'],

			['editName', 'trim'],
			['editName', 'required'],
			['editName', CategoryNameValidator::className(), 'when' => function($editModel){return $editModel->editName != $editModel->editNoCheckName;}],

			['editSeo', 'trim'],
			['editSeo', 'required'],
			['editSeo', CategorySefValidator::className(), 'when' => function($editModel){return $editModel->editSeo != $editModel->editNoCheckSef;}],

			['editText', 'trim'],

			['editParent', 'trim'],
			['editParent', 'required'],

			['editId', 'safe'],

			['editIcon', 'trim'],

			['categoryMain', 'safe'],
		];
	}

	public function update($path)
	{
		$data = [
			'title' => $this->editName, 
			'sef' => $this->editSeo,
			'text' => $this->editText,
			'parent' => $this->editParent,
			'main' => $this->categoryMain,
		];

		if($this->editIcon)
		{
			$image = new Image();
			$image->resize(100, 100, $path . $this->editIcon);
			$image->save();
			$data['image'] = $this->editIcon;
		}

		return Category::updateAll($data, ['id' => $this->editId]);
	}
}