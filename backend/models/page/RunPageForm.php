<?php
namespace backend\models\page;

use yii\base\Model;
use backend\models\page\Page;

class RunPageForm extends Model
{
	public $idPage;

	public function rules()
	{
		return [
			['idPage', 'trim'],
			['idPage', 'required'],
		];
	}

	public function delete()
	{
		return Page::updateAll(['deleted' => 1], ['id' => $this->idPage]);
	}

	public function restore()
	{
		return Page::updateAll(['deleted' => 0], ['id' => $this->idPage]);
	}
}