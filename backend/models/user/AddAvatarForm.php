<?php

namespace backend\models\user;

use yii\base\Model;

class AddAvatarForm extends Model
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
			$uploadedImg = uniqid() . '.' . $this->image->extension;
			$this->image->saveAs($path . $uploadedImg);
			return $uploadedImg;
		}
	}
}