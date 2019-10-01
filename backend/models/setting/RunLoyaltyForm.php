<?php
namespace backend\models\setting;

use yii\base\Model;

class RunLoyaltyForm extends Model
{
	public $loyaltyId;

	public function rules()
	{
		return [
			['loyaltyId', 'required'],
		];
	}

	public function delete()
	{
		return Loyalty::updateAll(['deleted' => 1], ['id' => $this->loyaltyId]);
	}

	public function restore()
	{
		return Loyalty::updateAll(['deleted' => 0], ['id' => $this->loyaltyId]);
	}
}