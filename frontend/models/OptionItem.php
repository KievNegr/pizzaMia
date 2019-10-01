<?php
namespace frontend\models;

use yii\db\ActiveRecord;

class OptionItem extends ActiveRecord
{
	public static function tableName()
	{
		return 'options_goods';
	}

	public function getOptionname()
	{
		return $this->hasOne(OptionName::className(), ['id' => 'id_option'])->orderBy('option_name.order_by ASC');
	}
}