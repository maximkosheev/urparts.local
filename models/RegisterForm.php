<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 23.05.2017
 * Time: 15:32
 */

namespace app\models;

use yii\base\Model;

class RegisterForm extends Model
{
    public $username;
    public $password;
    public $password_repeat;
    public $firstname;
    public $lastname;
    public $email;
    public $city;
    public $country;
    public $phone;

    public function rules()
    {
        return [
            [['username', 'password', 'firstname', 'lastname', 'email', 'country', 'phone'], 'required', 'message' => 'Поле не может быть пустым'],
            ['username', 'validateUsername'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],
            ['email', 'email'],
            ['city', 'safe'],
            // TODO: ['phone', 'phone']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'firstname' => 'Имя',
            'lastname' => 'Отчество',
            'password_repeat' => 'Пароль еще раз',
            'email' => 'Email',
            'city' => 'Город',
            'country' => 'Страна',
            'phone' => 'Телефон'
        ];
    }

    /**
     * Проверка введеного имени на уникальность
     * @param $attribute
     * @param $params
     */
    public function validateUsername($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = User::findByUsername($this->username);
            if ($user !== null)
                $this->addError($attribute, 'Это имя уже занято, выберете другое');
        }
    }

    /**
     * Регистрация нового пользователя
     * @return bool - результат регистрации. true - успешно, false - неудачно
     */
    public function register()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->password = md5($this->password);
            $user->firstname = $this->firstname;
            $user->lastname = $this->lastname;
            $user->email = $this->email;
            $user->city = $this->city;
            $user->country = $this->country;
            $user->phone = $this->phone;
            return $user->save();
        }
        return false;
    }
}