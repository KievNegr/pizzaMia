<?php
namespace backend\models\order;

use yii\db\ActiveRecord;

class OptionGoods extends ActiveRecord
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