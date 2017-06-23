<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class User
 * @package app\models
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $fristname
 * @property string $lastname
 * @property string $email
 * @property string $city
 * @property string $country
 * @property string $phone
 */
class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    private static $users = [
        '0' => [
            'id' => '0',
            'username' => 'admin',
            'password' => '21232f297a57a5a743894a0e4a801fc3',
            'firstname' => 'admin',
            'lastname' => 'admin',
            'email' => 'admin@mycompany.com'
        ],
    ];


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return isset(self::$users[$id]) ? new static(self::$users[$id]) : self::findOne(['id' => $id]);
    }

    /**
     * Авторизация по ключу не поддерживается
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        // ищем по списку внутренних пользователей
        foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }
        // ищем по списку в базе данных
        $user = self::findOne(['username' => $username]);
        return $user;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return 0;
    }

    /**
     * Авторизация по ключу не поддерживается
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return false;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }
}
