<?php
namespace backend\models\setting;

use yii\base\Model;

class NewOrderStatus extends Model
{
	public $orderName;
	public $orderColor;
	public $orderCancell;
	public $setRatio;
	public $orderEarned;
	public $orderExpected;
	public $orderHoped;

	public function attributeLabels()
	{
		return [
			'orderName' => 'Название состояния',
			'orderColor' => 'Цвет состояния',
			'orderCancell' => 'Отменять скидку при этом состоянии заказа',
			'setRatio' => 'Возможность оставлять оценку',
			'orderEarned' => 'Считать деньги как заработанные (для статистики)',
			'orderExpected' => 'Считать деньги как скоро появятся (для статистики)',
			'orderHoped' => 'Считать деньги как возможный заработок (для статистики)',
		];
	}

	public function rules()
	{
		return [
			['orderName', 'required'],
			['orderColor', 'required'],
			['orderCancell', 'required'],
			['setRatio', 'required'],
			['orderEarned', 'required'],
			['orderExpected', 'required'],
			['orderHoped', 'required'],
		];
	}

	public function save()
	{
		if($this->setRatio == 1)
			$this->updateRatio();

		if($this->cancel_promo == 1)
			$this->updateCancel();

		if($this->orderEarned == 1)
			$this->orderEarned();

		if($this->orderExpected == 1)
			$this->orderExpected();

		if($this->orderHoped == 1)
			$this->orderHoped();

		$newOrder = new OrderStatus;

		$newOrder->name = $this->orderName;
		$newOrder->color = $this->orderColor;
		$newOrder->cancel_promo = $this->orderCancell;
		$newOrder->set_ratio = $this->setRatio;
		$newOrder->earned = $this->orderEarned;
		$newOrder->expected = $this->orderExpected;
		$newOrder->hoped = $this->orderHoped;

		return $newOrder->save();
	}

	private function updateRatio()
	{
		OrderStatus::updateAll(['set_ratio' => 0]);
	}

	private function updateCancel()
	{
		OrderStatus::updateAll(['cancel_promo' => 0]);
	}

	private function orderEarned()
	{
		OrderStatus::updateAll(['earned' => 0]);
	}

	private function orderExpected()
	{
		OrderStatus::updateAll(['expected' => 0]);
	}

	private function orderHoped()
	{
		OrderStatus::updateAll(['hoped' => 0]);
	}
}