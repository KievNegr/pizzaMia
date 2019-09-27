<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\bootstrap4\ActiveForm;
use backend\models\page\Page;
use backend\models\page\AddPageForm;
use backend\models\page\EditPageForm;
use backend\models\page\AddImagesPageForm;
use backend\models\page\RunPageForm;
use yii\web\UploadedFile;
use yii\helpers\Url;

class PageController extends Controller
{
	public $path = '../../frontend/web/images/page/';

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
        
		$newPageModel = new AddPageForm;
		$pageImagesModel = new AddImagesPageForm;
		$editPageModel = new EditPageForm;
		$editPageImagesModel = new AddImagesPageForm;
		$removeModel = new RunPageForm;
		$restoreModel = new RunPageForm;

		$path = $this->path;

		if($newPageModel->load(Yii::$app->request->post()) && $newPageModel->validate())
		{
			if($newPageModel->save())
			{
				Yii::$app->session->setFlash('success', 'Страница создана'); // Выводим сообщение что категория создана
				return $this->refresh(); // Перегружаем страницу
			}
		}

		if($editPageModel->load(Yii::$app->request->post()) && $editPageModel->validate())
		{
			if($editPageModel->update())
			{
				Yii::$app->session->setFlash('success', 'Страница обновлена'); // Выводим сообщение что категория создана
				return $this->refresh(); // Перегружаем страницу
			}
		}

		if($removeModel->load(Yii::$app->request->post()) && $removeModel->validate())
		{
			if($removeModel->delete())
			{
				Yii::$app->session->setFlash('success', 'Страница удалена'); // Выводим сообщение что категория создана
				return $this->refresh(); // Перегружаем страницу
			}
		}

		if($restoreModel->load(Yii::$app->request->post()) && $restoreModel->validate())
		{
			if($restoreModel->restore())
			{
				Yii::$app->session->setFlash('success', 'Страница восстановлена'); // Выводим сообщение что категория создана
				return $this->refresh(); // Перегружаем страницу
			}
		}
		
		$pageList = Page::find()
					->select('page.*, images_page.name AS image')
					->joinWith('images', true, 'LEFT JOIN')
					->orderBy('page.sort ASC')
					->asArray()
					->all();

		return $this->render('index', compact('pageList', 'newPageModel', 'pageImagesModel', 'editPageImagesModel', 'editPageModel', 'path', 'removeModel', 'restoreModel'));
	}

	public function actionValidatenew()
	{
		$model = new AddPageForm();
		return $this->validation($model);
	}

	public function actionValidateedit()
	{
		$model = new EditPageForm();
        return $this->validation($model);
	}

	public function validation($model)
	{
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) 
		{
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
	}

	public function actionUploadimage()
	{
		if(Yii::$app->request->isAjax)
		{
			$model = new AddImagesPageForm;

			$model->image = UploadedFile::getInstances($model, 'image');
				
			$images = $model->upload($this->path);

			$data = null;

			foreach($images as $image)
			{
				$size = getimagesize($this->path . $image);

				$class = substr($image, 0, -4);

				$data .= '<div class="image ' . $class . '" style="background-image: url(' . $this->path . $image . ');">
							<div class="imageRun" nameImage="' . $image . '">
								<i class="fas fa-crop-alt imageCrop" particularclass="' . $class . '" address="' . $this->path . $image . '" imageWidth="' . $size[0] . '" imageHeight="' . $size[1] . '"  title="Кадрировать изображение"></i>
								<i class="fas fa-code imageInsert" title="Вставить изображение в текст" address="' . Url::to('@page/' . $image) . '"></i>
								<i class="fas fa-trash-alt deleteImage" nameDelete="' . $class . '"></i>
							</div>
						</div>';
			}

			return $data;
		}
	}

	public function actionSortpage()
	{
		if(Yii::$app->request->isAjax)
		{
			$id = Yii::$app->request->post('id');
			$val = Yii::$app->request->post('val');

			Page::updateAll(['sort' => $val], ['id' => $id]);

			return $id;
		}
	}
}