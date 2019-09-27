<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\option\Option;
use backend\models\option\AddOptionForm;
use backend\models\option\EditOptionForm;
use backend\models\option\RunOptionForm;
use backend\models\option\Image;
use backend\models\category\Category;
use yii\web\UploadedFile;

class OptionController extends Controller
{
	public $path = '../../frontend/web/images/option/';
	public $pathCategory = '../../frontend/web/images/category/';

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
        
		$path = $this->path;
		$pathCategory = $this->pathCategory;

		$newModel = new AddOptionForm;
		$editModel = new EditOptionForm;
		$removeModel = new RunOptionForm;
		$restoreModel = new RunOptionForm;
		$optionImage = new Image;

		if($newModel->load(Yii::$app->request->post()) && $newModel->validate())
		{
			if($newModel->save())
			{
				Yii::$app->session->setFlash('success', 'Новая опция создана');
				return $this->refresh();
			}
		}

		if($editModel->load(Yii::$app->request->post()) && $editModel->validate())
		{
			if($editModel->update())
			{
				Yii::$app->session->setFlash('success', 'Опция изменена');
				return $this->refresh();
			}
		}

		if($removeModel->load(Yii::$app->request->post()) && $removeModel->validate())
		{
			if($removeModel->delete())
			{
				Yii::$app->session->setFlash('success', 'Опция удалена');
				return $this->refresh();
			}
		}

		if($restoreModel->load(Yii::$app->request->post()) && $restoreModel->validate())
		{
			if($restoreModel->restore())
			{
				Yii::$app->session->setFlash('success', 'Опция восстановлена');
				return $this->refresh();
			}
		}

		$options = Category::find()
					->select('category.id, category.parent, category.title, category.image, category.deleted, option_name.title AS options')
					->joinWith('options', true, 'LEFT JOIN')
					->orderBy('category.id ASC')
					->asArray()
					->all();

		return $this->render('index', compact('newModel', 'editModel', 'removeModel', 'restoreModel', 'optionImage', 'options', 'path', 'pathCategory'));
	}

	public function actionUploadimage()
	{
		if(Yii::$app->request->isAjax)
		{
			$model = new Image;
			$model->image = UploadedFile::getinstance($model, 'image');
			$image = $model->upload($this->path);

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
}