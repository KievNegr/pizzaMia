<?php
namespace backend\models\setting;

use yii\base\Model;

class RunPromoForm extends Model
{
	public $promoId;

	public function rules()
	{
		return [
			['promoId', 'required'],
		];
	}

	public function delete()
	{
		return Promo::updateAll(['deleted' => 1], ['id' => $this->promoId]);
	}

	public function restore()
	{
		return Promo::updateAll(['deleted' => 0], ['id' => $this->promoId]);
	}
}