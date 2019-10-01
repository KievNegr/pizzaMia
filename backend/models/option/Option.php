<?php

namespace backend\models\option;

use yii\db\ActiveRecord;

class Option extends ActiveRecord
{
	public static function tableName()
	{
		return 'option_name';
	}
}