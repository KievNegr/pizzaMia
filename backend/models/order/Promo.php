<?php
namespace backend\models\order;

use yii\db\ActiveRecord;

class Promo extends ActiveRecord
{
	public static function tableName()
	{
		return 'coupons';
	}
}