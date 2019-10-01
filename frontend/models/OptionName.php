<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class OptionName extends ActiveRecord
{
	public static function tableName()
	{
		return 'option_name';
	}
}