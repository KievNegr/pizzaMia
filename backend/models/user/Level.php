<?php

namespace backend\models\user;

use yii\db\ActiveRecord;

class Level extends ActiveRecord 
{
	public static function tableName() {
		return 'level_user';
	}
}