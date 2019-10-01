<?php
namespace backend\models\setting;

use yii\base\Model;
use backend\components\validators\couponValidator;

class EditPromoForm extends Model
{
	public $promoCode;
	public $noCheckPromoCode;
	public $promoExpire;
	public $promoDiscount;
	public $promoId;
	public $promoApplying;

	public function attributeLabels()
	{
		return [
			'promoCode' => 'Новый промокод',
			'promoExpire' => 'Дата истечения',
			'promoDiscount' => 'Размер скидки %',
			'promoApplying' => 'Многоразовое использование',
		];
	}

	public function rules()
	{
		return [
			['promoCode', 'required'],
			['promoCode', couponValidator::className(), 'when' => function($editModel){return $editModel->promoCode != $editModel->noCheckPromoCode;}],

			['noCheckPromoCode', 'safe'],
			['promoExpire', 'required'],
			['promoDiscount', 'required'],
			['promoId', 'required'],
			['promoApplying', 'safe'],
		];
	}

	public function update()
	{
		$expire = explode('.', $this->promoExpire);
		$promo = [
			'code' => $this->promoCode,
			'expiration' => $expire[2] . '-' . $expire[1] . '-' . $expire[0],
			'discount' => $this->promoDiscount,
			'applying' => $this->promoApplying,
		];

		return Promo::updateAll($promo, ['id' => $this->promoId]);
	}
}