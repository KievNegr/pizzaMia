<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\ingredient\Ingredient;
use backend\models\ingredient\ImageForm;
use backend\models\ingredient\AddNewIngredientForm;
use backend\models\ingredient\EditNewIngredientForm;
use backend\models\ingredient\RunIngredientForm;
use yii\web\UploadedFile;
use yii\web\Response;
use yii\bootstrap4\ActiveForm;

class IngredientController extends Controller
{
	public $path = '../../frontend/web/images/ingredient/';

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
        
		$ingredientImage = new ImageForm;
		$newModel = new AddNewIngredientForm;
		$editModel = new EditNewIngredientForm;
		$removeModel = new RunIngredientForm;
		$restoreModel = new RunIngredientForm;

		$path = $this->path;

		if($newModel->load(Yii::$app->request->post()) && $newModel->validate())
		{
			if($newModel->save())
			{
				Yii::$app->session->setFlash('success', 'Новый ингредиент создан');
				return $this->refresh();
			}
		}

		if($editModel->load(Yii::$app->request->post()) && $editModel->validate())
		{
			if($editModel->update())
			{
				Yii::$app->session->setFlash('success', 'Ингредиент изменен');
				return $this->refresh();
			}
		}

		if($removeModel->load(Yii::$app->request->post()) && $removeModel->validate())
		{
			if($removeModel->delete())
			{
				Yii::$app->session->setFlash('success', 'Ингредиент удален');
				return $this->refresh();
			}
		}

		if($restoreModel->load(Yii::$app->request->post()) && $restoreModel->validate())
		{
			if($restoreModel->restore())
			{
				Yii::$app->session->setFlash('success', 'Ингредиент восстановлен');
				return $this->refresh();
			}
		}

		$ingredients = Ingredient::find()->asArray()->all();

		return $this->render('index', compact('ingredients', 'newModel', 'editModel', 'removeModel', 'restoreModel', 'ingredientImage', 'path'));
	}

	public function actionUploadimage()
	{
		if(Yii::$app->request->isAjax)
		{
			$ingredientImage = new ImageForm;

			$ingredientImage->image = UploadedFile::getInstance($ingredientImage, 'image');

			$image = $ingredientImage->upload($this->path);

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

	public function actionValidationnew()
	{
		$model = new AddNewIngredientForm;
		return $this->validation($model);
	}

	public function actionValidationedit()
	{
		$model = new EditNewIngredientForm;
		return $this->validation($model);
	}

	public function validation($model)
	{
		if(Yii::$app->request->isAjax && $model->load(Yii::$app->request->post()))
		{
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
	}
}