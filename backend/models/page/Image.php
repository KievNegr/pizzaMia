<?php

namespace backend\models\page;

use Yii\db\ActiveRecord;

class Image extends ActiveRecord
{
	public static function tableName()
	{
		return 'images_page';
	}
}