<?php

namespace backend\models\goods;

use yii\base\Model;

class NewImage extends Model
{
	public $image;

	public function rules()
	{
		return [
			['image', 'image', 'extensions' => 'jpg, png', 'maxFiles' => 1, 'maxWidth' => 10000, 'maxHeight' => 10000, 'minWidth' => 980, 'minHeight' => 450],
		];
	}

	public function upload($path, $thumb)
	{
		if($this->validate())
		{
			//$images = null;
			//foreach($this->image as $file)
			//{
				$uploadedImage = uniqid() . '.' . $this->image->extension;
				$this->image->saveAs($path. $uploadedImage);
				$size = getimagesize($path . $uploadedImage);
				$ratio = $size[0] / $size[1];

				$resize = new \backend\models\Image;
				$resize->resize(980, 980 / $ratio, $path . $uploadedImage);
				$resize->save();

				copy($path. $uploadedImage, $thumb . $uploadedImage);
				$resize = new \backend\models\Image;
				$resize->resize(300, 300 / $ratio, $thumb . $uploadedImage);
				$resize->save();
				
				$image = $uploadedImage;
			//}

			return $image;
		}
	}
}