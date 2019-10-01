<?php

namespace backend\models\ingredient;

use yii\base\Model;

class ImageForm extends Model
{
	public $image;

	public function rules()
	{
		return [
			['image', 'image'],
		];
	}

	public function upload($path)
	{
		if($this->validate())
		{
			$image = uniqid() . '.' . $this->image->extension;
			$this->image->saveAs($path . $image);
			return $image;
		}
	}
}