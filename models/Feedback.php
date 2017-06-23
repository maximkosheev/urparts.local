<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 29.05.2017
 * Time: 15:32
 */

namespace app\models;

use app\common\behaviors\CreateDateTimeBehavior;
use yii\db\ActiveRecord;

/**
 * Class Feedback
 * @package app\models
 * @property string $subject
 * @property string $user_name
 * @property string $user_email
 * @property string $user_phone
 * @property string $user_country
 * @property string $text
 * @property string $create_dt
 */
class Feedback extends ActiveRecord
{
    public function behaviors()
    {
        return [
            'create_dt' => [
                'class' => CreateDateTimeBehavior::className(),
                'resProp' => 'create_dt'
            ]
        ];
    }

    public function rules()
    {
        return [
            [['subject', 'user_name', 'user_email', 'user_phone', 'user_country'], 'required', 'message' => 'поле не может быть пустым'],
            [['user_name', 'user_email'], 'string', 'max' => 20, 'message' => 'длина поля не может превышать 20 символов'],
            ['subject', 'string', 'max' => 100, 'message' => 'длина поля не может превышать 100 символов'],
            ['user_email', 'email'],
            [['text', 'create_dt'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'subject' => 'Тема',
            'user_name' => 'Ваше имя',
            'user_email' => 'Ваш email',
            'user_phone' => 'Телефон',
            'user_country' => 'Страна',
            'text' => 'Сообщение'
        ];
    }
}