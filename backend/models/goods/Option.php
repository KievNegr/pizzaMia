<?php
namespace backend\models\goods;

use yii\db\ActiveRecord;

class Option extends ActiveRecord
{
	public static function tableName()
	{
		return 'options_goods';
	}

	public function getOptionName()
	{
		return $this->hasOne(OptionName::className(), ['id' => 'id_option']);
	}
}