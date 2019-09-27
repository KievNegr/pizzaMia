<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $name;


    /**
     * {@inheritdoc}
     */

    public function attributeLabels()
    {
        return [
            'username' => 'Ваше имя',
            'email' => 'Ваш електронный адрес',
            'password' => 'Пароль для входа',
        ];
    }

    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required', 'message' => 'Введите свое имя'],
            ['username', 'string', 'min' => 2, 'max' => 60, 'tooShort' => 'Имя слишком короткое, введите минимум 2 символа', 'tooLong' => 'Имя слишком длинное, введите максимум 60 символов'],

            //['name', 'trim'],
            //['name', 'required'],

            ['email', 'trim'],
            ['email', 'required', 'message' => 'Введите свой електронный адрес'],
            ['email', 'email', 'message' => 'Введите корректный електронный адрес'],
            ['email', 'string', 'max' => 50, 'tooLong' => 'Введите максимум 50 символов'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Пользователь с таким електронным адресом уже зарегистрирован.'],

            ['password', 'required', 'message' => 'Введите пароль'],
            ['password', 'string', 'min' => 6, 'tooShort' => 'Пароль слишком короткий, введите минимум 6 символов'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        //$user->name = $this->name;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        return $user->save();// && $this->sendEmail($user);

    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailersupport
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
