<?php
namespace frontend\models\order;

use yii\db\ActiveRecord;

class Cart extends ActiveRecord
{
	public function addToCart($product, $option, $pathToItemThumbsImage, $qty = 1)
	{
		if(isset($_SESSION['cart']['list'][$product->id . '_' .$option->id]))
		{
			if($_SESSION['cart']['list'][$product->id . '_' .$option->id]['id_option'] == $option->id)
			{
				$this->updatePrice($option);
				return $_SESSION['cart']['list'][$product->id . '_' .$option->id]['qty'] += $qty;
			}
			else
			{
				$this->updatePrice($option);
				return $this->createNewItem($product, $option, $pathToItemThumbsImage);
			}
		}
		else
		{
			$this->updatePrice($option);
			return $this->createNewItem($product, $option, $pathToItemThumbsImage);
		}
	}

	public function removeFromCart($product, $option, $qty = 1)
	{
		if(isset($_SESSION['cart']['list'][$product->id . '_' .$option->id]))
		{
			if($_SESSION['cart']['list'][$product->id . '_' .$option->id]['id_option'] == $option->id)
			{
				$_SESSION['cart']['list'][$product->id . '_' .$option->id]['qty'] -= $qty;
				if($_SESSION['cart']['list'][$product->id . '_' .$option->id]['qty'] == 0)
				{
					unset($_SESSION['cart']['list'][$product->id . '_' .$option->id]);
					return $this->deletePrice($option);
				}
				else
				{
					return $this->deletePrice($option);
				}
			}
			return false;
		}

		return false;
	}

	private function createNewItem($product, $option, $pathToItemThumbsImage, $qty = 1)
	{
		return $_SESSION['cart']['list'][$product->id . '_' .$option->id] = [
					'qty' => $qty,
					'id_product' => $product->id,
					'product_name' => $product->title,
					'product_sef' => $product->sef,
					'product_image' => $pathToItemThumbsImage . '/' . $product->image->name,
					'id_option' => $option->id,
					'option_name' => $option->optionname->title,
					'price' => $option->price,					
		];
	}

	private function updatePrice($option, $qty = 1, $delivery = 0, $idDelivery = 0, $discount = 0, $idDiscount = 0)
	{
		if(isset($_SESSION['cart']['sum']))
		{
			$_SESSION['cart']['sum'] = $_SESSION['cart']['sum'] + $option->price * $qty;
		}
		else
		{
			$_SESSION['cart']['sum'] = $option->price * $qty;
		}

		if(!isset($_SESSION['cart']['delivery']))
		{
			$_SESSION['cart']['delivery']['value'] = $delivery;
			$_SESSION['cart']['delivery']['id'] = $idDelivery;
		}

		if(!isset($_SESSION['cart']['discount']))
		{
			$_SESSION['cart']['discount']['value'] = $discount;
			$_SESSION['cart']['discount']['id'] = $idDiscount;
		}

		if(isset($_SESSION['cart']['qty']))
		{
			$_SESSION['cart']['qty'] = $_SESSION['cart']['qty'] + $qty;
		}
		else
		{
			$_SESSION['cart']['qty'] = $qty;
		}
		return true;
	}

	private function deletePrice($option, $qty = 1)
	{
		$_SESSION['cart']['sum'] = $_SESSION['cart']['sum'] - $option->price * $qty;

		$_SESSION['cart']['qty'] = $_SESSION['cart']['qty'] - $qty;

		if($_SESSION['cart']['sum'] <= 0 || $_SESSION['cart']['qty'] <= 0)
		{
			unset($_SESSION['cart']);
		}

		return true;
	}

	public function updateDelivery($value, $id)
	{
		if(isset($_SESSION['cart']['delivery']))
		{
			$_SESSION['cart']['delivery']['value'] = $value;
			$_SESSION['cart']['delivery']['id'] = $id;
		}
	}

	public function updateDiscount($value, $id)
	{
		if(isset($_SESSION['cart']['discount']))
		{
			$_SESSION['cart']['discount']['value'] = $value;
			$_SESSION['cart']['discount']['id'] = $id;
		}
	}
}