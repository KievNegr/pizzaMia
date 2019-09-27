<?php

namespace backend\models;

use yii\db\ActiveRecord;
use backend\components\validators\PageSefValidator;

class ModelIndex extends ActiveRecord
{
	public $username;

	public static function tableName()
	{
		return 'page';
	}

	public function rules()
	{
		return [
			['username', 'required'],
			['username', PageSefValidator::className()],
			//['test', PageSefValidator::className()],
		];
	}

	public function validateName($attribute)
	{
		if($this->attribute != 'Хуй')
			$this->addError($attribute, 'Введите хуй');
	}
}
?>