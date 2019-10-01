<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use frontend\components\validators\UserUserEmailValidator;
use frontend\components\validators\UserUserPhoneValidator;

class Customer extends ActiveRecord
{
	public $email;
    public $phone;
    public $anotherPhone;
    public $address;
    public $additional;

	public static function tableName()
	{
		return 'user';
	}

	public function attributeLabels()
	{
		return [
			'email' => 'Электронная почта',
    		'phone' => 'Основной номер телефона',
    		'anotherPhone' => 'Дополнительный номер телефона',
    		'address' => 'Адрес доставки',
    		'additional' => 'Дополнительная информация',
		];
	}

	public function rules()
	{
		return [
			['email', 'required'],
			['email', 'email'],
			['email', UserUserEmailValidator::className(), 'when' => function(){return $this->email != Yii::$app->user->identity->email;}],
			
			['phone', 'required'],
			['phone', UserUserPhoneValidator::className(), 'when' => function(){return $this->phone != Yii::$app->user->identity->phone;}],

			['anotherPhone', 'safe'],
			['anotherPhone', UserUserPhoneValidator::className(), 'when' => function(){return $this->anotherPhone != Yii::$app->user->identity->another_phone;}],

			['address', 'safe'],
			['additional', 'safe'],
		];
	}

	public function updateData()
	{
		$data = [
			'order_email' => $this->email,
			'phone' => $this->phone,
			'another_phone' => $this->anotherPhone,
			'address' => $this->address,
			'additional' => $this->additional,
		];

		return Customer::updateAll($data, ['id' => Yii::$app->user->identity->id]);
	}

	public static function updateAvatar($avatar)
	{
		$data = ['image' => $avatar,];
		return Customer::updateAll($data, ['id' => Yii::$app->user->identity->id]);
	}
}