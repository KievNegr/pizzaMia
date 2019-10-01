<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class Goods extends ActiveRecord
{
	public function getImage()
	{
		return $this->hasOne(GoodsImages::className(), ['id_product' => 'id'])->andWhere(['main_pic' => 1]);
	}

	public function getImages()
	{
		return $this->hasMany(GoodsImages::className(), ['id_product' => 'id']);
	}

	public function getOptions()
	{
		return $this->hasMany(OptionItem::className(), ['id_product' => 'id']);
	}
}