<?php
namespace backend\models\setting;

use yii\base\Model;

class RunPayForm extends Model
{
	public $payId;

	public function rules()
	{
		return [
			['payId', 'required'],
		];
	}

	public function delete()
	{
		return Pay::updateAll(['deleted' => 1], ['id' => $this->payId]);
	}

	public function restore()
	{
		return Pay::updateAll(['deleted' => 0], ['id' => $this->payId]);
	}
}