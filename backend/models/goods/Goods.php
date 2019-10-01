<?php
namespace backend\models\goods;

use yii\db\ActiveRecord;

class Goods extends ActiveRecord
{
	public function getOptions()
	{
		return $this->hasmany(Option::className(), ['id_product' => 'id']);
	}

	public function getImages()
	{
		return $this->hasMany(Image::className(), ['id_product' => 'id']);
	}
}