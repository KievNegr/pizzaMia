<?php

namespace backend\models\page;

use yii\base\Model;

class AddImagesPageForm extends Model
{
	public $image;

	public function rules()
	{
		return [
			['image', 'image', 'maxFiles' => 4],
		];
	}

	public function upload($path)
	{
		if($this->validate())
		{
			$images = null;

			foreach($this->image as $file)
			{
				$uploadedImg = uniqid() . '.' . $file->extension;
				$file->saveAs($path . $uploadedImg);
				$images[] = $uploadedImg;
			}

			return $images;
		}
	}
}