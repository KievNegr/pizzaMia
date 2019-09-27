<?php
namespace backend\models\goods;

use yii\db\ActiveRecord;

class Image extends ActiveRecord
{
	public static function tableName()
	{
		return 'images_goods';
	}
}