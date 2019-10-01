<?php
namespace backend\models\goods;

use Yii;
use yii\base\Model;
use yii\validators\EachValidator;
use backend\components\validators\ItemSefValidator;

class AddNewForm extends Model
{
	public $newItemTitle;
	public $newItemSef;
	public $newItemDescription;
	public $newItemText;
	public $newItemIngredient;
	public $newItemCategory;
	public $itemPrice = [];
	public $newItemPopular;
	public $newItemNew;
	public $newItemOnline;
	public $newItemImage;
	public $newItemMainImage;

	public function attributeLabels()
	{
		return [
			'newItemTitle' => 'Название товара',
			'newItemSef' => 'Генерация ссылки',
			'newItemDescription' => 'Описание товара',
			'newItemText' => 'Немного слов о товаре',
			'newItemIngredient' => 'Добавить ингредиенты в описание',
			'newItemCategory' => 'Категория товара',
			'newItemPopular' => 'Отметить, если популярный',
			'newItemNew' => 'Отметить, если товар новинка',
			'newItemOnline' => 'Отметить, если товар доступен для заказа через интернет',
		];
	}

	public function rules()
	{
		return [
			['newItemTitle', 'required'],
			['newItemSef', 'required'],
			['newItemSef', ItemSefValidator::className()],
			['newItemDescription', 'required'],
			['newItemText', 'required'],
			['newItemIngredient', 'safe'],
			['newItemCategory', 'required'],
			['newItemCategory', 'number', 'min' => 1, 'message' => 'Необходимо выбрать нужную категорию'],
			['itemPrice', 'safe'],
			['newItemPopular', 'safe'],
			['newItemNew', 'safe'],
			['newItemOnline', 'safe'],
			['newItemImage', 'required', 'message' => 'Необходимо добавить изображения'],
			['newItemMainImage', 'required', 'message' => 'Необходимо выбрать изображение по умолчанию'],
		];
	}

	public function save()
	{
		$item = new Goods;

		$item->title = $this->newItemTitle;
		$item->sef = $this->newItemSef;
		$item->description = $this->newItemDescription;
		$item->text = $this->newItemText;
		$item->category = $this->newItemCategory;
		if($this->newItemIngredient)
			$item->ingredients = implode(',', $this->newItemIngredient);
		$item->popular = $this->newItemPopular;
		$item->new = $this->newItemNew;
		$item->online_order = $this->newItemOnline;
		$item->data = date("Y-m-d H:i:s");
		$item->id_admin = Yii::$app->user->identity->id;

		if($item->save())
		{
			$images = explode(',', substr($this->newItemImage, 0, -1));
			foreach($images as $image)
			{
				$dataImage = new Image;
				$dataImage->name = $image;
				$dataImage->id_product = $item->id;
				if($this->newItemMainImage == $image)
					$dataImage->main_pic = 1;

				$dataImage->save();
			}

			foreach($this->itemPrice as $key => $value)
			{
				$dataValue = new Option;
				$dataValue->id_product = $item->id;
				$dataValue->id_option = $key;
				if(empty($value))
				{
					$dataValue->price = 0;
				}
				else
				{
					$dataValue->price = $value;
				}

				$dataValue->save();
			}
			return true;
		}
		else
		{
			return false;
		}
	}
}