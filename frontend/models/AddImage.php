<?php
namespace frontend\models;

use yii\base\Model;
use frontend\models\Customer;

class AddImage extends Model
{
	public $image;
	
	public function rules()
	{
		return [
			['image', 'image'],
		];
	}

	public function upload()
	{
		if($this->validate()) //Если форма прошла валидацию
		{
			$uploadedImg = uniqid() . '.' . $this->image->extension; //Присваиваем новое уникальное имя изображению
			$this->image->saveAs('images/avatar/' . $uploadedImg); //Сохраняем в папку с аватарами
			Customer::updateAvatar($uploadedImg);
			return $uploadedImg; //Возвращаем новое имя картинки
		}
	}
}