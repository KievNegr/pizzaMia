<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\category\AddForm;
use backend\models\category\EditForm;
use backend\models\category\RunCategoryForm;
use backend\models\category\Category;
use backend\models\category\AddIconForm;
use yii\web\UploadedFile;
use yii\web\Response;
use yii\bootstrap4\ActiveForm;

class CategoryController extends Controller
{
	public $path = '../../frontend/web/images/category/'; //Путь к картинкам категорий

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
        
		$model = new AddForm(); //Форма создания категорий
		$editModel = new EditForm(); //Форма редактирования категорий
		$removeModel = new RunCategoryForm(); //Форма удаления категорий
		$restoreModel = new RunCategoryForm(); //Форма восстановления категорий
		$categoryIcon = new AddIconForm();

		$allcategory = null; //Если категорий нет то присваиваем null
		$parent = null; //Родительские категории присваиваем null

		$path = $this->path;

		//Удаление категорий
		if( $removeModel->load(yii::$app->request->post()) && $removeModel->validate() )
		{
			if( $removeModel->delete() )
			{
				Yii::$app->session->setFlash('success', 'Категория удалена'); // Выводим сообщение что категория создана
				return $this->refresh(); // Перегружаем страницу
			}
		}

		//Восстановление категорий
		if( $restoreModel->load(yii::$app->request->post()) && $restoreModel->validate() )
		{
			if( $restoreModel->restore() )
			{
				Yii::$app->session->setFlash('success', 'Категория востановленна'); // Выводим сообщение что категория создана
				return $this->refresh(); // Перегружаем страницу
			}
		}

		if($model->load(yii::$app->request->post()) && $model->validate())
		{
			if($model->insert($path))
			{
				Yii::$app->session->setFlash('success', 'Новая категория создана');
				return $this->refresh(); // Перегружаем страницу
			}
		}

		if($editModel->load(yii::$app->request->post()) && $editModel->validate())
		{
			if($editModel->update($path))
			{
				Yii::$app->session->setFlash('success', 'Категория изменена');
				return $this->refresh(); // Перегружаем страницу
			}
		}

		$Category = Category::find()->orderBy('id ASC')->asArray()->all();

		foreach($Category as $parentItem)
		{
			$child = null;
			if($parentItem['parent'] == 0)
			{
				foreach($Category as $childItem)
				{
					if($parentItem['id'] == $childItem['parent'])
					{
						$child[] = $childItem;
					}
				}
				$parent[] = $parentItem;

				if($child) $parentItem['child'] = $child;
				$allcategory[] = $parentItem;
			}
		}

		
		return $this->render('index', compact('model', 'categoryIcon', 'editModel', 'removeModel', 'restoreModel', 'parent', 'allcategory', 'path'));
	}

	public function actionUploadimage()
	{
		if(Yii::$app->request->isAjax)
		{
			$categoryIcon = new AddIconForm();
			
			$categoryIcon->image = UploadedFile::getInstance($categoryIcon, 'image');
			
			$image = $categoryIcon->upload($this->path);

			$data = null;

			$class = substr($image, 0, -4);

			$size = getimagesize($this->path . $image);

			$data['text'] = '<div class="image ' . $class . '" style="background-image: url(' . $this->path . $image . ');">
							<div class="imageRun" nameImage="' . $image . '">
								<i class="fas fa-crop-alt imageCrop" particularclass="' . $class . '" address="' . $this->path . $image . '" imageWidth="' . $size[0] . '" imageHeight="' . $size[1] . '"  title="Кадрировать изображение"></i>
							</div>
						</div>';

			$data['img'] = $image;

			return json_encode($data);
		}
	}

	public function actionValidatenew()
	{
		$model = new AddForm;
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