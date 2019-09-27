<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\TestForm;
use frontend\models\Test;
use frontend\models\Action;
use yii\db\Query;
/**
 * Filter controller
 */

class FilterController extends Controller
{
    
    public function actionIndex()
    {
        $model = new TestForm();

        if($model->load(Yii::$app->request->post()))
        {
            if($model->validate())
            {
                echo 'd';
            }
        }

        $this->view->title = 'Form';

        return $this->render('index', compact('model'));
    }

    public function actionDb()
    {
        $getFromDb = Test::find()->where(['id' => [1, 3]])->asArray()->all();
        
        return $this->render('db', compact('getFromDb'));
    }

    public function actionUser()
    {
        //$user = Test::find()->with('action')->asArray()->all();
        //$user = Test::find()->join('LEFT JOIN', 'action', ['id' => 'user'])->asArray()->all();

        $query = new Query;

        $query->select('*')->from(['action'])->rightJoin('test', 'action.user = test.id');

        $user = $query->all();
        $action = true;// = Action::findOne(1);//::find()->where(['user' => 1])->one();

        return $this->render('user', compact('user', 'action'));
    }
}
