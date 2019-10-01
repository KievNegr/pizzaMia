<?php
	
	namespace frontend\models;

	use yii\db\ActiveRecord;

	class Action extends ActiveRecord {

		public function getTest()
		{
			return $this->hasOne(Test::className(), ['id' => 'user']);
		}
	}