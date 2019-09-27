<?php
namespace backend\models\order;
use common\models\User;

use yii\db\ActiveRecord;

class Discount extends ActiveRecord
{
	public function getManager()
	{
		return $this->hasOne(User::className(), ['id' => 'from_admin']);
	}
}