<?php
namespace backend\models\category;

use yii\db\ActiveRecord;
use backend\models\option\Option;
use backend\models\goods\Goods;

class Category extends ActiveRecord 
{
	public static function tableName()
	{
		return 'category';
	}

	public function getOptions()
	{
		return $this->hasmany(Option::className(), ['id_category' => 'id']);
	}

	public function getGoods()
	{
		return $this->hasmany(Goods::className(), ['category' => 'id']);
	}
}