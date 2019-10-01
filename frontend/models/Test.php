<?php

	namespace frontend\models;

	use yii\db\ActiveRecord;

	class Test extends ActiveRecord {

		public function getAction()
		{
			return $this->hasMany(Action::className(), ['user' => 'id']);
		}
	}
	