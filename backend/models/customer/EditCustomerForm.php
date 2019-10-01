<?php
namespace backend\models\customer;

use yii\base\Model;
use backend\models\user\User;
use backend\components\validators\UserUserEmailValidator;
use backend\components\validators\UserUserPhoneValidator;

class EditCustomerForm extends Model
{
	public $userId;
	public $email;
	public $phone;
	public $anotherPhone;
	public $noCheckPhone;
	public $noCheckEmail;
	public $noCheckAnotherPhone;
	public $userLevel;

	public function attributeLabels()
	{
		return [
			'email' => 'Електропочта клиента',
			'phone' => 'Основной номер телефона клиента',
			'anotherPhone' => 'Дополнительный номер телефона клиента',
		];
	}

	public function rules()
	{
		return [
			['email', 'email'],
			['email', UserUserEmailValidator::className(), 'when' => function($editModel){return $editModel->email != $editModel->noCheckEmail;}],
			['phone', UserUserPhoneValidator::className(), 'when' => function($editModel){return $editModel->phone != $editModel->noCheckPhone;}],
			//['phone', 'required'],
			['anotherPhone', 'safe'],
			['anotherPhone', UserUserPhoneValidator::className(), 'when' => function($editModel){return $editModel->anotherPhone != $editModel->noCheckAnotherPhone;}],

			['noCheckPhone', 'safe'],
			['noCheckAnotherPhone', 'safe'],
			['noCheckEmail', 'safe'],
			['userId', 'safe'],
			['userLevel', 'safe'],
		];
	}

	public function update()
	{
		$user = [
			'email' => $this->email,
			'phone' => $this->phone,
			'another_phone' => $this->anotherPhone,
			'level' => $this->userLevel,
		];

		return User::updateAll($user, ['id' => $this->userId]);
	}
}