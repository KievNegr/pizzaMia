<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class GoodsImages extends ActiveRecord
{
	public static function tableName()
	{
		return 'images_goods';
	}
}