<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\user\User;
use backend\models\user\Level;
use backend\models\user\EditForm;
use backend\models\user\RunUserForm;
use backend\models\user\AddAvatarForm;
use yii\web\UploadedFile;
use yii\db\Query;
use yii\web\Response;
use yii\bootstrap4\ActiveForm;


class UserController extends Controller {

	public $path = '../../frontend/web/images/avatar/';

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
        
		$editModel = new EditForm();
		$removeModel = new RunUserForm();
		$restoreModel = new RunUserForm();
		$userAvatar = new AddAvatarForm();

		$addError = 0; //Ошибка добавлениея нового админа, по умолчанию = 0 (отсутствуют ошибки)
		$editError = ['error' => 0, 'id' => null];

		$path = $this->path;

		if($removeModel->load(Yii::$app->request->post()))
		{
			if(!$removeModel->removeUser())
			{
				print_r($removeModel);
			}
			else
			{
				Yii::$app->session->setFlash('success', 'Пользователь удален');
				return $this->refresh(); // Перегружаем страницу
			}
		}

		if($restoreModel->load(Yii::$app->request->post()))
		{
			if($restoreModel->restoreUser())
			{
				Yii::$app->session->setFlash('success', 'Пользователь восстановлен');
				return $this->refresh(); // Перегружаем страницу
			}
		}

		if($editModel->load(Yii::$app->request->post()) && $editModel->validate()) //Если получили данные то 
		{
			if($editModel->update($path))
			{
				Yii::$app->session->setFlash('success', 'Пользователь изменен');
				return $this->refresh(); // Перегружаем страницу
			}
		}

		$levels = Level::find()->asArray()->all();
		$users = User::find()->where(['!=', 'level', 3])->asArray()->all();
		
		$this->view->title = 'Administrator list';

		return $this->render('index', compact('users', 'levels', 'editModel', 'removeModel', 'restoreModel', 'path', 'userAvatar'));
	}

	public function actionUploadimage()
	{
		if(Yii::$app->request->isAjax)
		{
			$userAvatar = new AddAvatarForm();
			
			$userAvatar->image = UploadedFile::getInstance($userAvatar, 'image');
			
			$image = $userAvatar->upload($this->path);

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

	public function actionValidate()
	{
		$model = new EditForm;

		if( Yii::$app->request->isAjax  && $model->load(Yii::$app->request->post()) )
		{
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}
	}
}