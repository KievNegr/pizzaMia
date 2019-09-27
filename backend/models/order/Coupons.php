<?php
namespace backend\models\order;

use yii\db\ActiveRecord;
use backend\models\user\User;

class Coupons extends ActiveRecord
{
	public function getForuser()
	{
		return $this->hasOne(User::className(), ['id' => 'user']);
	}
}