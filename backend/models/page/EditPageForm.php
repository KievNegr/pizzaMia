<?php

namespace backend\models\page;

use yii\base\Model;
use backend\models\page\Page;
use backend\models\page\Image;
use backend\components\validators\PageSefValidator;

class EditPageForm extends Model
{
	public $editPageTitle;
	public $editPageDescription;
	public $editPageSef;
	public $editPageNoCheckSef;
	public $editPageText;
	public $editPageImage;
	public $editInsertedImages;
	public $editPageShowHeader;
	public $editPageShowFooter;
	public $editPageId;

	public function attributeLabels()
	{
		return [
			'editPageTitle' => 'Заголовок страницы',
			'editPageDescription' => 'Описание страницы',
			'editPageSef' => 'Генерированная ссылка на страницу',
			'editPageText' => 'Содержание страницы',
			'editPageShowHeader' => 'Отображать в шапке',
			'editPageShowFooter' => 'Отображать в подвале',
		];
	}

	public function rules()
	{
		return [
			['editPageTitle', 'trim'],
			['editPageTitle', 'required'],

			['editPageDescription', 'trim'],
			['editPageDescription', 'required'],

			['editPageSef', 'trim'],
			['editPageSef', 'required'],
			['editPageSef', PageSefValidator::className(), 'when' => function($editPageModel){return $editPageModel->editPageSef != $editPageModel->editPageNoCheckSef;}],
			['editPageNoCheckSef', 'trim'],

			['editPageText', 'trim'],
			['editPageText', 'required'],

			['editPageImage', 'trim'],
			['editInsertedImages', 'trim'],

			['editPageShowHeader', 'safe'],
			['editPageShowFooter', 'safe'],

			['editPageId', 'trim'],
		];
	}

	public function update()
	{
		$data = [
			'sef' => $this->editPageSef,
			'title' => $this->editPageTitle,
			'description' => $this->editPageDescription,
			'text' => $this->editPageText,
			'showheader' => $this->editPageShowHeader,
			'showfooter' => $this->editPageShowFooter,
		];

		Page::updateAll($data, ['id' => $this->editPageId]);

		$imagesNewList = explode(',', substr($this->editPageImage, 0, -1));

		$imagesOldList = explode(',', substr($this->editInsertedImages, 0, -1));

		if(!empty($this->editPageImage))
		{
			foreach($imagesNewList as $image)
			{
				if(!in_array($image, $imagesOldList))
				{
					$dataImage = new Image;

					$dataImage->id_page = $this->editPageId;
					$dataImage->name = $image;

					if(!$dataImage->save())
					{
						return false;
					}
				}
			}
		}

		if(!empty($this->editInsertedImages))
		{
			foreach($imagesOldList as $image)
			{
				if(!in_array($image, $imagesNewList))
				{
					$deleteImage = Image::find()->where(['name' => $image])->one();

					$deleteImage->delete();
				}
			}
		}

		return true;
	}
}