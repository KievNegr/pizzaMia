<?php
namespace backend\models\user;

use yii\base\Model;
use backend\models\user\User;

class RunUserForm extends Model
{
	public $idUser;

	public function rules()
	{
		return [
			['idUser', 'trim'],
			['idUser', 'required']
		];
	}

	public function removeUser()
	{
		return User::updateAll(['status' => 9], ['id' => $this->idUser]);
	}

	public function restoreUser()
	{
		return User::updateAll(['status' => 10], ['id' => $this->idUser]);
	}
}