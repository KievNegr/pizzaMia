<?php
namespace backend\models;

use yii\base\Model;
use Imagine\Imagick\Imagine;
use Imagine\Image\Box;
use Imagine\Image\PointInterface;
use Imagine\Image\Point;

class Image extends Model
{
	public $imageSession;
	public $image;
	public $imagePath;

	public function open($imagePath)
	{
		$this->imageSession = new Imagine();
		return $this->image = $this->imageSession->open($imagePath);
	}

	public function resize($width, $height, $imagePath)
	{
		if(!$this->image)
		{
			$this->image = $this->open($imagePath);
		}

		return $this->image->resize(new Box($width, $height));
	}

	public function crop($x, $y, $width, $height, $imagePath)
	{
		if(!$this->image)
		{
			$this->image = $this->open($imagePath);
		}

		if($x < 0) $x = 0;
		if($y < 0) $y = 0;

		return $this->image->crop(new Point($x, $y), new Box($width, $height));
	}

	public function save()
	{
		return $this->image->save($this->imagePath);
	}
}