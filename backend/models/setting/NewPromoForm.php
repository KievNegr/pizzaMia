<?php
namespace backend\models\setting;

use yii\base\Model;
use backend\components\validators\couponValidator;

class NewPromoForm extends Model
{
	public $promoCode;
	public $promoExpire;
	public $promoUser;
	public $promoDiscount;
	public $applying;

	public function attributeLabels()
	{
		return [
			'promoCode' => 'Новый промокод',
			'promoExpire' => 'Дата истечения',
			'promoUser' => 'Для клиента',
			'promoDiscount' => 'Размер скидки %',
			'applying' => 'Многоразовое использование',
		];
	}

	public function rules()
	{
		return [
			['promoCode', 'required'],
			['promoCode', couponValidator::className()],
			['promoExpire', 'required'],
			['promoUser', 'required'],
			['promoDiscount', 'required'],
			['applying', 'required'],
		];
	}

	public function save()
	{
		$expire = explode('.', $this->promoExpire);
		$promo = new Promo([
			'code' => $this->promoCode,
			'expiration' => $expire[2] . '-' . $expire[1] . '-' . $expire[0],
			'user' => $this->promoUser,
			'discount' => $this->promoDiscount,
			'applying' => $this->applying,
		]);

		return $promo->save();
	}
}