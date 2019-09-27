<?php
namespace backend\models\option;

use yii\base\Model;

class RunOptionForm extends Model
{
	public $idOption;

	public function rules()
	{
		return [
			['idOption', 'required'],
		];
	}

	public function delete()
	{
		$data['deleted'] = 1;
		return Option::updateAll($data, ['id' => $this->idOption]);
	}

	public function restore()
	{
		$data['deleted'] = 0;
		return Option::updateAll($data, ['id' => $this->idOption]);
	}
}