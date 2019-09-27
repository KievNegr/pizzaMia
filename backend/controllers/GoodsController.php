<?php
namespace backend\controllers;

use yii;
use yii\web\Controller;
use backend\models\goods\AddNewForm;
use backend\models\goods\EditForm;
use backend\models\goods\RunGoodsForm;
use backend\models\goods\NewImage;
use backend\models\goods\Goods;
use backend\models\ingredient\Ingredient;
use backend\models\category\Category;
use backend\models\goods\Option;
use backend\models\goods\OptionName;
use yii\web\UploadedFile;
use yii\web\Response;
use yii\bootstrap4\ActiveForm;

class GoodsController extends Controller
{
	public $pathGoodsImages = '../../frontend/web/images/goods/';
	public $pathGoodsImagesThumbs = '../../frontend/web/images/goods/thumbs/';
	public $pathIngredientImages = '../../frontend/web/images/ingredient/';

	public function actionIndex()
	{
		if(Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }
        elseif(Yii::$app->user->identity->level == 3)
        {
            Yii::$app->user->logout();
            return $this->goHome();
        }
        
		$newModel = new AddNewForm;
		$editModel = new EditForm;
		$deleteModel = new RunGoodsForm;
		$restoreModel = new RunGoodsForm;
		$newImagesModel = new NewImage;
		$editImagesModel = new NewImage;

		$pathGoodsImages = $this->pathGoodsImages;
		$pathIngredientImages = $this->pathIngredientImages;

		if($deleteModel->load(Yii::$app->request->post()) && $deleteModel->validate())
		{
			if($deleteModel->delete())
			{
				Yii::$app->session->setFlash('success', 'Позиция удалена');
				return $this->refresh();
			}

		}

		if($restoreModel->load(Yii::$app->request->post()) && $restoreModel->validate())
		{
			if($restoreModel->restore())
			{
				Yii::$app->session->setFlash('success', 'Позиция восстановлена');
				return $this->refresh();
			}

		}

		if($newModel->load(Yii::$app->request->post()) && $newModel->validate())
		{
			if($newModel->save())
			{
				Yii::$app->session->setFlash('success', 'Новая позиция добавлена');
				return $this->refresh();
			}

		}

		if($editModel->load(Yii::$app->request->post()) && $editModel->validate())
		{
			if($editModel->update())
			{
				Yii::$app->session->setFlash('success', 'Позиция обновлена');
				return $this->refresh();
				//print_r(Yii::$app->request->post());
			}
		}

		$ingredients = Ingredient::find()->asArray()->all();
		$categories = Category::find()->asArray()->all();
		$goods = Goods::find()->asArray()->all();
		
		$goods = Goods::find()
					->joinWith('images', true, 'LEFT JOIN')
					->joinWith(['options' => function(yii\db\ActiveQuery $query){
						$query->joinWith('optionName', true, 'LEFT JOIN')->asArray()->all();
					}], true, 'LEFT JOIN')
					->orderBy(['goods.category' => SORT_DESC, 'goods.id' => SORT_ASC])
					->asArray()
					->all();

		foreach($goods as $key => $item)
		{
			foreach($categories as $category)
			{
				if( $item['category'] == $category['id'] )
				{
					foreach($categories as $parent)
					{
						if( $category['parent'] == $parent['id'] )
							$goods[$key]['category_name'] = '<span class="badge badge-secondary">' . $parent['title'] . '</span> ➜ <span class="badge badge-secondary">' . $category['title'] . '</span>';
					}	
				}
			}
		}

		//print_r($goods);

		return $this->render('index', compact('newModel', 'editModel', 'newImagesModel', 'editImagesModel', 'restoreModel', 'deleteModel', 'goods', 'ingredients', 'categories', 'pathGoodsImages', 'pathIngredientImages'));
	}

	public function actionGetoptions()
	{
		if(Yii::$app->request->isAjax)
		{
			$categoryId = Yii::$app->request->post('categoryId');
			$options = OptionName::find()->where(['id_category' => $categoryId, 'deleted' => 0])->asArray()->all();
			
			$data = null;
			foreach($options as $option)
			{
				if(Yii::$app->request->post('edit') == 0)
				{
					$data .= '<div class="form-group field-addnewform-itemprice-' . $option['id'] . ' required validating">
								<label for="addnewform-itemprice-' . $option['id'] . '">Цена (' . $option['title'] . ')</label>
								<input type="text" id="addnewform-itemprice-' . $option['id'] . '" class="form-control" name="AddNewForm[itemPrice][' . $option['id'] . ']" aria-invalid="true">

								<div class="invalid-feedback"></div>
							</div>';
				}
				else
				{
					$data .= '<div class="form-group field-editform-itemprice-' . $option['id'] . ' required validating">
								<label for="editform-itemprice-' . $option['id'] . '">Цена (' . $option['title'] . ')</label>
								<input type="text" id="editform-itemprice-' . $option['id'] . '" class="form-control" name="EditForm[itemPrice][' . $option['id'] . ']" aria-invalid="true">

								<div class="invalid-feedback"></div>
							</div>';
				}
			}
			
			return $data;
		}
	}

	public function actionUploadimage()
	{
		if(Yii::$app->request->isAjax)
		{
			$model = new NewImage;

			$model->image = UploadedFile::getInstance($model, 'image');

			$image = $model->upload($this->pathGoodsImages, $this->pathGoodsImagesThumbs);

			$data = null;

			//foreach($images as $image)
			//{
				$size = getimagesize($this->pathGoodsImages . $image);
				$class = substr($image, 0, -4);

				$data .= '<div class="image ' . $class . '" style="background-image: url(' . $this->pathGoodsImages . $image . '?);">
							<div class="imageRun" nameImage="' . $image . '">
								<i class="fas fa-crop-alt imageCrop" particularclass="' . $class . '" address="' . $this->pathGoodsImages . $image . '" imageWidth="' . $size[0] . '" imageHeight="' . $size[1] . '" title="Кадрировать изображение"></i>
								<i class="fas fa-trash-alt deleteImage" nameDelete="' . $class . '"></i>
								<input type="radio" name="newGoodsImages" value="' . $image . '" class="selectNewImageRadio" title="Установить по умолчанию" />
							</div>
						</div>';
			//}

			return $data;
		}
	}

	public function actionValidatenew()
	{
		$model = new AddNewForm;
		return $this->validation($model);
	}

	public function actionValidateedit()
	{
		$model = new EditForm;
		return $this->validation($model);
	}

	public function validation($model)
	{
		if( Yii::$app->request->isAjax  && $model->load(Yii::$app->request->post()) )
		{
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
	}
}