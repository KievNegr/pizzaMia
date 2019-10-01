<?php
namespace backend\models\user;

use Yii;
use yii\db\ActiveRecord;
use backend\models\setting\Promo;

class User extends ActiveRecord {

	public static function setPassword($password)
    {
        return Yii::$app->security->generatePasswordHash($password);
    }

    public function getCoupons()
    {
    	return $this->hasMany(Promo::className(), ['user' => 'id']);
    }
}