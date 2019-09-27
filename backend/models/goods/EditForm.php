<?php
namespace backend\models\goods;

use Yii;
use yii\base\Model;
use backend\components\validators\ItemSefValidator;

class EditForm extends Model
{
	public $editItemTitle;
	public $editItemSef;
	public $editNoCheckItemSef;
	public $editItemDescription;
	public $editItemText;
	public $editItemIngredient;
	public $editItemCategory;
	public $editCategoryNoCheck;
	public $itemPrice = [];
	public $editItemPopular;
	public $editItemNew;
	public $editItemOnline;
	public $editItemImage;
	public $editItemImageNoCheck;
	public $editItemMainImage;
	public $editItemId;

	public function attributeLabels()
	{
		return [
			'editItemTitle' => 'Название товара',
			'editItemSef' => 'Генерация ссылки',
			'editItemDescription' => 'Описание товара',
			'editItemText' => 'Немного слов о товаре',
			'editItemIngredient' => 'Добавить ингредиенты в описание',
			'editItemCategory' => 'Категория товара',
			'editItemPopular' => 'Отметить, если популярный',
			'editItemNew' => 'Отметить, если товар новинка',
			'editItemOnline' => 'Отметить если товар доступен для заказа через интернет',
		];
	}

	public function rules()
	{
		return [
			['editItemTitle', 'required'],
			['editNoCheckItemSef', 'required'],
			['editItemSef', 'required'],
			['editItemSef', ItemSefValidator::className(), 'when' => function($editModel){return $editModel->editItemSef != $editModel->editNoCheckItemSef;}],
			['editItemDescription', 'required'],
			['editItemText', 'required'],
			['editItemIngredient', 'safe'],
			['editItemCategory', 'required'],
			['editCategoryNoCheck', 'required'],
			['editItemCategory', 'number', 'min' => 1, 'message' => 'Необходимо выбрать нужную категорию'],
			['itemPrice', 'safe'],
			['editItemPopular', 'safe'],
			['editItemNew', 'safe'],
			['editItemOnline', 'safe'],
			['editItemImage', 'required', 'message' => 'Необходимо добавить изображения'],
			['editItemImageNoCheck', 'required'],
			['editItemMainImage', 'required', 'message' => 'Необходимо выбрать изображение по умолчанию'],
			['editItemId', 'required'],
		];
	}

	public function update()
	{
		$item = [
			'title' => $this->editItemTitle,
			'sef' => $this->editItemSef,
			'description' => $this->editItemDescription,
			'text' => $this->editItemText,
			'category' => $this->editItemCategory,
			'popular' => $this->editItemPopular,
			'new' => $this->editItemNew,
			'online_order' => $this->editItemOnline,
			'update_admin' => Yii::$app->user->identity->id,
		];

		if($this->editItemIngredient) //Если есть какието ингредиенты то разбиваем на массив
		{
			$item['ingredients'] = implode(',', $this->editItemIngredient);
		}
		else
		{
			$item['ingredients'] = null;
		}

		Goods::updateAll($item, ['id' => $this->editItemId]);

		//Создаем массив из нового списка изображений
		$imagesNewList = explode(',', substr($this->editItemImage, 0, -1));

		//Создаем массив из старого списка изображений
		$imagesOldList = explode(',', substr($this->editItemImageNoCheck, 0, -1));
		
		//Прогоняем новые изображения
		foreach($imagesNewList as $image)
		{
			//Если в массиве со старым списком изображений отсутствует какаято картинка то добавляем ее в базу
			if(!in_array($image, $imagesOldList))
			{
				$dataImage = new Image;
				$dataImage->name = $image;
				$dataImage->id_product = $this->editItemId;

				$dataImage->save();
			}
		}

		//Прогоняем старые изображения
		foreach($imagesOldList as $image)
		{
			//Если в массиве с новым списком изображений отсутствует какаято картинка то удаляем ее из базы
			if(!in_array($image, $imagesNewList))
			{
				$deleteImage = Image::find()->where(['name' => $image])->one();
				$deleteImage->delete();
			}
		}

		//Обозначаем картинку по умолчанию
		Image::updateAll(['main_pic' => 0], ['id_product' => $this->editItemId]);
		Image::updateAll(['main_pic' => 1], ['name' => $this->editItemMainImage]);

		if( $this->editItemCategory == $this->editCategoryNoCheck )
		{
			foreach($this->itemPrice as $key => $value)
			{
				$dataValue['price'] = $value;
				Option::updateAll($dataValue, ['and', ['id_option' => $key], ['id_product' => $this->editItemId]]);
			}
		}
		else
		{
			Option::deleteAll(['id_product' => $this->editItemId]);
			
			foreach($this->itemPrice as $key => $value)
			{
				echo $value . ', ';
				$dataValue = new Option;
				$dataValue->id_product = $this->editItemId;
				$dataValue->id_option = $key;
				$dataValue->price = $value;

				$dataValue->save();
			}
		}
		
		return true;
	}
}