<?php

namespace backend\models;

use yii\base\Model;

class LoyaltyForm extends Model {

	public $discountFrom;
	public $discountTo;
	public $discountSize;
	public $editLoyaltyId;

	public function attributeLabels() {
		return [
			'discountFrom' => 'Сумма от, грн',
			'discountTo' => 'Сумма до, грн',
			'discountSize' => 'Размер скидки, %',
		];
	}

	public function rules() {
		return [
			['discountFrom', 'required'],
			['discountTo', 'required'],
			['discountSize', 'required'],
			['editLoyaltyId', 'safe'],
		];
	}
}