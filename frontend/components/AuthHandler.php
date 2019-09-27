<?php
namespace frontend\components;


use frontend\models\Auth;
use frontend\models\User;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;

/**
 * AuthHandler handles successful authentification via Yii auth component
 */
class AuthHandler
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function handle()
    {
        if(!Yii::$app->user->isGuest)
        {
            return;
        }

        $attributes = $this->client->getUserAttributes();

        $auth = $this->findUser($attributes);

        if($auth)
        {
            $user = $auth->user;
            return Yii::$app->user->login($user);
        }

        if($user = $this->createAccount($attributes))
        {
            return Yii::$app->user->login($user);
        }
    }

    private function findUser($attributes)
    {
        $params = [
            'source_id' => ArrayHelper::getValue($attributes, 'id'),
            'source' => $this->client->getId(),
        ];

        return Auth::find()->where($params)->one();
    }

    private function createAccount($attributes)
    {
        $id = ArrayHelper::getValue($attributes, 'id');
        $email = ArrayHelper::getValue($attributes, 'email');
        $username = ArrayHelper::getValue($attributes, 'name');

        if( $email !== null && User::find()->where(['email' => $email])->exists() )
        {
            Yii::$app->session->setFlash('user', 'Пользователь с таким e-mail уже существует');
            return;
        }

        $user = $this->createUser($email, $username);

        $transaction = User::getDb()->beginTransaction();
        
        if($user->save())
        {
            $auth = $this->createAuth($user->id, $id);
            if($auth->save())
            {
                $transaction->commit();
                return $user;
            }
        }
        $transaction->rollBack();
    }

    private function createUser($email, $username)
    {
        return New User([
            'username' => $username,
            'email' => $email,
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash(Yii::$app->security->generateRandomString()),
            'created_at' => $time = time(),
            'updated_at' => $time,
            'order_email' => $email,
        ]);
    }

    private function createAuth($userId, $id)
    {
        return new Auth([
            'user_id' => $userId,
            'source' => $this->client->getId(),
            'source_id' => $id,
        ]);
    }
}