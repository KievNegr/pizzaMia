<?php
namespace backend\models\category;

use yii\base\Model;
use backend\models\category\Category;
use backend\components\validators\CategoryNameValidator;
use backend\components\validators\CategorySefValidator;
use backend\models\Image;

class AddForm extends Model
{
	public $categoryName;
	public $categorySeo;
	public $categoryText;
	public $categoryParent;
	public $categoryIcon;
	public $categoryMain;

	public function attributeLabels()
	{
		return [
			'categoryName' => 'Название категории',
			'categorySeo' => 'Генерация ссылки категории',
			'categoryText' => 'Текст категории',
			'categoryParent' => 'Выбор родительской категории',
			'categoryIcon' => 'Изображение категории (квадрат, формат PNG)',
			'categoryMain' => 'Отображать на главной',
		];
	}

	public function rules()
	{
		return [
			['categoryName', 'required'],
			['categoryName', CategoryNameValidator::className()],

			['categorySeo', 'required'],
			['categorySeo', CategorySefValidator::className()],

			['categoryText', 'trim'],

			['categoryParent', 'trim'],

			['categoryIcon', 'required'],

			['categoryMain', 'safe'],
		];
	}

	public function insert($path)
	{
		$image = new Image();
		$image->resize(100, 100, $path . $this->categoryIcon);
		$image->save();

		$category = new Category();

		$category->parent = $this->categoryParent;
		$category->sef = $this->categorySeo;
		$category->title = $this->categoryName;
		$category->text = $this->categoryText;
		$category->image = $this->categoryIcon;
		$category->main = $this->categoryMain;

		return $category->save();
	}
}