<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 31.05.2017
 * Time: 14:13
 */

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class Group
 * @package app\models
 * @property integer $id
 * @property string $title
 * @property string $maillist
 */
class Group extends ActiveRecord
{
    public function rules()
    {
        return [
            ['title', 'required', 'message'=>'Поле не может быть пустым'],
            ['title', 'unique', 'message' => 'Такая группа категорий уже существует'],
            ['maillist', 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Наименование',
            'maillist' => 'Список рассылки'
        ];
    }

    public function sendEmails()
    {
        $emails = preg_split('/\s+/', $this->maillist);
        foreach ($emails as $email) {
            \Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom(\Yii::$app->params['adminEmail'])
                ->setSubject("Subject")
                ->setTextBody("letter body 1")
                ->send();
        }
    }
}