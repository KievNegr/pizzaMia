<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\bootstrap4\ActiveForm;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\User;
use yii\db\Query;
use backend\models\SinginForm;
use backend\models\user\Level;
use backend\models\LoyaltyForm;
use backend\models\ModelIndex;
use yii\helpers\Inflector;
use backend\models\Image;
use backend\models\order\Order;
use backend\models\order\OrderStatus;
use backend\models\setting\Currency;
use backend\models\category\Category;
use backend\models\goods\Goods;
use backend\models\Login;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'seo', 'crop', 'img', 'validation', 'shedule'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }


    /**
     * Displays homepage.
     *
     * @return string
     */
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
        
        $users = User::find()->asArray()->all();
        $userLevel = Level::find()->asArray()->all();

        $orders = Order::find()
                        ->joinWith(['orderlist', 'discount', 'coupon'], true, 'LEFT JOIN')
                        ->orderBy(['orders.id' => 'SORT_DESC'])
                        ->asArray()
                        ->all();

        $orderStatus = OrderStatus::find()->asArray()->all();
        $currency = Currency::find()->where(['default_view' => 1])->asArray()->one();

        $categoryCount = Category::find()->count();
        $goodsCount = Goods::find()->count();

        $this->view->title = 'Администраторская панель сайта';

        return $this->render('index', compact('users', 'userLevel', 'orders', 'orderStatus', 'currency', 'categoryCount', 'goodsCount'));
    }

    public function actionImg()
    {
        if(Yii::$app->request->isAjax)
        {

            $file = Yii::$app->request->get('file');

            $img = new Image;
            $img->resize(2000, 1335, \yii\helpers\Url::to('@backend/web/images/photos/' . $file));
            $img->save();
            return \yii\helpers\Url::to('@web/images/photos/' . $file);
            //echo $file;
        }
    }

    public function actionValidation()
    {
        $model = new ModelIndex();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $this->layout = 'login';
        
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            $login = Login::find()->where(['ip' => Yii::$app->request->getUserIP()])->one();

            $attempt = 3;

            if($login)
            {
                $timeDifference = time() - $login->time;

                if($login->count > 2 && $timeDifference < 900)
                {
                    echo 'К сожалению вы не можете войти в систему сейчас, попробуйте зайти через ' . round((900 - $timeDifference) / 60) . ' минут.';
                    die;
                }
                elseif($timeDifference > 900)
                {
                    $login->delete();
                }
                else
                {
                    $attempt = $attempt - $login->count;
                }
            }

            return $this->render('login', [
                'model' => $model,
                'attempt' => $attempt,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSeo()
    {
        if(Yii::$app->request->getIsAjax())
        {
            $gotUrl = Yii::$app->request->get('text');

            $gotUrl = mb_strtolower($gotUrl);

            $letters = array(
                ' ' => '-',
                ',' => '-',
                '.' => '-',
                'а' => 'a',
                'б' => 'b',
                'в' => 'v',
                'г' => 'g',
                'д' => 'd',
                'е' => 'e',
                'ж' => 'zh',
                'з' => 'z',
                'и' => 'i',
                'й' => 'yi',
                'к' => 'k',
                'л' => 'l',
                'м' => 'm',
                'н' => 'n',
                'о' => 'o',
                'п' => 'p',
                'р' => 'r',
                'с' => 's',
                'т' => 't',
                'у' => 'u',
                'ф' => 'f',
                'х' => 'h',
                'ц' => 'c',
                'ч' => 'ch',
                'ш' => 'sh',
                'щ' => 'shch',
                'ъ' => '',
                'ы' => 'ui',
                'ь' => '',
                'э' => 'e',
                'ю' => 'yu',
                'я' => 'ya',
                '#' => '-',
                '№' => '-'
            );


            foreach($letters as $key => $letter)
            {
                $gotUrl = str_replace($key, $letter, $gotUrl);
            }

            echo $gotUrl;
        }
    }

    public function actionCrop()
    {
        if(Yii::$app->request->isAjax)
        {
            $gotData = Yii::$app->request->post('data');

            $pointX = $gotData['pointX'];
            $pointY = $gotData['pointY'];
            $areaWidth = $gotData['areaWidth'];
            $areaHeight = $gotData['areaHeight'];
            $source = $gotData['source'];

            $image = new Image;
            $image->crop($pointX, $pointY, $areaWidth, $areaHeight, $source);
            
            $image->save();

            return $source;
        }
    }

    public function actionShedule()
    {
        //Ищем заказы в которых дата доставки позже чем сегодня
        $orders = Order::find()
                        ->where(['>', 'deliveryTime', date('d.m.Y H:i')])
                        ->joinWith('orderStatus', true, 'LEFT JOIN')
                        ->asArray()
                        ->all();

        $deliveryCount = 0; //Счетчик количества доставок присваиваем 0
        $message = ''; //Создаем пустое сообщение

        //Перечисляем все полученные заказы
        foreach($orders as $order)
        {
            $data = explode('.', substr($order['deliveryTime'], 0, 10)); //Разбиваем дату на массив
            $difference = strtotime(implode('-', array_reverse($data))) - strtotime(date("Y-m-d")); //Сравниваем дату доставки с сегодняшним днем

            //Если заказ не отменен и дата доставки уже завтра то
            if($order['orderStatus']['cancel_promo'] == 0 && $difference == 86400)
            {
                $message .= 'Заказ №' . $order['id'] . ' Дата ' . $order['deliveryTime'] . PHP_EOL;
                $deliveryCount++;
            }
        }

        if($deliveryCount > 0)
        {
            $message = 'Заказов на завтра: ' . $deliveryCount . PHP_EOL . $message;
 
            Yii::$app->telegram->sendMessage([
                'chat_id' => '-380199054',
                'disable_notification' => true, //Отключение звука уведомления
                'text' => $message,
            ]);
        }
    }
}
