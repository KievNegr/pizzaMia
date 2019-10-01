<?php

namespace frontend\models;

use yii\base\Model;


class TestForm extends Model {

	public $name;
	public $email;
	public $text;

	public function attributeLabels() {
		return [
			'name' => 'Имя',
			'email' => 'Почта',
			'text' => 'Текст',
		];
	}

	public function rules() {
		return [
			[ ['name', 'email'], 'required'],
			['email', 'email'],
			['name', 'string', 'min' => 2],
		];
	}
}