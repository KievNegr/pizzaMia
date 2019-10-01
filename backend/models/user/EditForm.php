<?php

namespace backend\models\user;

use yii\base\Model;
use backend\models\user\User;
use backend\components\validators\UserUserEmailValidator;
use backend\components\validators\UserUserPhoneValidator;
use backend\models\Image;

class EditForm extends Model {

	public $userName;
	public $phone;
    public $anotherPhone;
    public $noCheckPhone;
    public $noCheckAnotherPhone;
	public $email;
	public $noCheckEmail;
	public $password;
	public $avatar;
	public $userId;
	public $userLevel;


	public function attributeLabels() {
    	return [
    		'userName' => 'Имя',
    		'email' => 'Существующий e-mail (для авторизации)',
    		'password' => 'Пароль для входа',
    		'avatar' => 'Аватарка',
    		'phone' => 'Номер телефона',
    		'anotherPhone' => 'Дополнительный номер телефона',
    	];
    }

	public function rules() {
		return [
			['userName', 'trim'],
			['userName', 'required'],

    		['email', 'trim'],
    		['email', 'required'],
    		['email', 'email'],
    		['noCheckEmail', 'safe'],
    		['email', UserUserEmailValidator::className(), 'when' => function(){return $this->email != $this->noCheckEmail;}],

    		['phone', 'trim'],
    		['noCheckPhone', 'safe'],
    		['phone', UserUserPhoneValidator::className(), 'when' => function(){return $this->phone != $this->noCheckPhone;}],

    		['anotherPhone', 'safe'],
    		['noCheckAnotherPhone', 'safe'],
    		['anotherPhone', UserUserPhoneValidator::className(), 'when' => function(){return $this->anotherPhone != $this->noCheckAnotherPhone;}],

    		['password', 'safe'],

    		['avatar', 'trim'],

    		['userLevel', 'required', 'message' => 'Необходимо назначить уровень доступа'],

            ['userId', 'safe'],
		];
	}

	public function update($path)
	{
        $data = [
			'username' => $this->userName, 
			'email' => $this->email,
			'phone' => $this->phone,
			'another_phone' => $this->anotherPhone,
			'level' => $this->userLevel,
		];

        if($this->password)
        {
            $data['password_hash'] = User::setPassword($this->password);
        } 

		if($this->avatar)
        {
            $image = new Image(); // Создаем объект картинки
            $image->resize(100, 100, $path . $this->avatar);
            $image->save();
            $data['image'] = $this->avatar;
        }
        else
        {
            $data['image'] = '';
        }

		return User::updateAll($data, ['id' => $this->userId]);
	}
}