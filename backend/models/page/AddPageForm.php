<?php

namespace backend\models\page;

use yii\base\Model;
use backend\models\page\Page;
use backend\models\page\Image;
use backend\components\validators\PageSefValidator;

class AddPageForm extends Model
{
	public $newPageTitle;
	public $newPageDescription;
	public $newPageSef;
	public $newPageText;
	public $newPageImage;
	public $newPageShowHeader;
	public $newPageShowFooter;

	public function attributeLabels()
	{
		return [
			'newPageTitle' => 'Заголовок страницы',
			'newPageDescription' => 'Описание страницы',
			'newPageSef' => 'Генерированная ссылка на страницу',
			'newPageText' => 'Содержание страницы',
			'newPageShowHeader' => 'Отображать в шапке',
			'newPageShowFooter' => 'Отображать в подвале',
		];
	}

	public function rules()
	{
		return [
			['newPageTitle', 'trim'],
			['newPageTitle', 'required'],

			['newPageDescription', 'trim'],
			['newPageDescription', 'required'],

			['newPageSef', 'trim'],
			['newPageSef', 'required'],
			['newPageSef', PageSefValidator::className()],

			['newPageText', 'trim'],
			['newPageText', 'required'],

			['newPageImage', 'trim'],

			['newPageShowHeader', 'safe'],
			['newPageShowFooter', 'safe'],
		];
	}

	public function save()
	{
		$data = new Page;

		$data->sef = $this->newPageSef;
		$data->title = $this->newPageTitle;
		$data->description = $this->newPageDescription;
		$data->text = $this->newPageText;
		$data->data = date("Y-m-d H:i:s");
		$data->showheader = $this->newPageShowHeader;
		$data->showfooter = $this->newPageShowFooter;

		$inserted = false;

		if($data->save())
		{
			$inserted = true;

			if($this->newPageImage)
			{
				$this->newPageImage = substr($this->newPageImage, 0, -1);
				$listImage = explode(',', $this->newPageImage);
				
				foreach($listImage as $file)
				{
					$dataImage = new Image;

					$dataImage->id_page = $data->id;
					$dataImage->name = $file;

					if(!$dataImage->save())
					{
						$inserted = false;
					}
				}
			}
		}

		return $inserted;
	}
}