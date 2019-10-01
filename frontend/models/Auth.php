<?php

namespace frontend\models;

use yii\db\ActiveRecord;

class Auth extends ActiveRecord 
{
	public function getUser()
	{
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}
}
	