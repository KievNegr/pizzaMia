<?php
namespace backend\models\setting;

use yii\base\Model;

class DeleteCalendarForm extends Model
{
	public $timeId;

	public function rules()
	{
		return [
			['timeId', 'required'],
		];
	}

	public function delete()
	{
		$delete = Deliverytime::findOne($this->timeId);
		return $delete->delete();
	}
}