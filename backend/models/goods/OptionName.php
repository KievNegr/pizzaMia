<?php

namespace backend\models\goods;

use yii\db\ActiveRecord;

class OptionName extends ActiveRecord
{
	public static function tableName()
	{
		return 'option_name';
	}
}