<?php
namespace backend\models\setting;

use yii\db\ActiveRecord;
use backend\models\user\User;

class Promo extends ActiveRecord
{
	public static function tableName()
	{
		return 'coupons';
	}

	public function getForuser()
	{
		return $this->hasOne(User::className(), ['id' => 'user']);
	}
}