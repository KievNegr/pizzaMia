<?php
namespace common\models;

use Yii;
use yii\base\Model;
use backend\models\Login;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $_user;


    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить',
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // email and password are both required
            ['email', 'required', 'message' => 'Введите e-mail'],
            ['password', 'required', 'message' => 'Введите пароль'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверный логин или пароль.');
                
                $login = Login::find()->where(['ip' => Yii::$app->request->getUserIP()])->one();

                if(!$login)
                {
                    $dataBan = new Login([
                                    'count' => 1, 
                                    'ip' => Yii::$app->request->getUserIP(), 
                                    'time' => time(),
                                ]);

                    $dataBan->save();
                }
                else
                {
                    $count = $login->count + 1;
                    Login::updateAll(['count' => $count, 'time' => time()], ['id' => $login->id]);
                }
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }
}
