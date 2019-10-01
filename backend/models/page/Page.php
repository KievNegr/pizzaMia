<?php

namespace backend\models\page;

use yii\db\ActiveRecord;

class Page extends ActiveRecord
{
	public function getImages()
	{
		return $this->hasmany(Image::className(), ['id_page' => 'id']);
	}
}