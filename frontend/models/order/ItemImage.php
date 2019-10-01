<?php
namespace frontend\models\order;

use yii\db\ActiveRecord;

class ItemImage extends ActiveRecord
{
	public static function tableName()
	{
		return 'images_goods';
	}
}