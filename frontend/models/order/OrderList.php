<?php
namespace frontend\models\order;

use yii\db\ActiveRecord;
use frontend\models\Goods;

class OrderList extends ActiveRecord
{
	public static function tableName()
	{
		return 'orderlist';
	}

	public function getOptiongoods()
	{
		return $this->hasOne(OptionGoods::className(), ['id' => 'size']);
	}

	public function getItem()
	{
		return $this->hasOne(Goods::className(), ['id' => 'id_product']);
	}

	public function getItemImage()
	{
		return $this->hasOne(ItemImage::className(), ['id_product' => 'id_product']);
	}
}