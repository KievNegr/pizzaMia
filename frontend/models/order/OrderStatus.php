<?php
namespace frontend\models\order;

use yii\db\ActiveRecord;

class OrderStatus extends ActiveRecord
{
	public static function tableName()
	{
		return 'status_order';
	}
}