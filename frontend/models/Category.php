<?php
namespace frontend\models;;

use yii\db\ActiveRecord;

class Category extends ActiveRecord 
{
	public function getGoods()
	{
		return $this->hasMany(Goods::className(), ['category' => 'id'])->andWhere(['goods.deleted' => 0]);
	}
}