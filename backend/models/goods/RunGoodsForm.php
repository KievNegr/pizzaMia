<?php
namespace backend\models\goods;

use yii\base\Model;

class RunGoodsForm extends Model
{
	public $idItem;

	public function rules()
	{
		return [
			['idItem', 'trim'],
			['idItem', 'required'],
		];
	}

	public function delete()
	{
		return Goods::updateAll(['deleted' => 1], ['id' => $this->idItem]);
	}

	public function restore()
	{
		return Goods::updateAll(['deleted' => 0], ['id' => $this->idItem]);
	}
}