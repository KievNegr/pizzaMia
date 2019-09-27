<?php
namespace backend\models\option;

use yii\base\Model;

class Image extends Model
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
		$image = uniqid() . '.' . $this->image->extension;
		$this->image->saveAs($path . $image);
		return $image;
	}
}