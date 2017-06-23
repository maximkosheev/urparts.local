<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 26.05.2017
 * Time: 11:24
 */

namespace app\models;

use app\common\behaviors\CreateDateTimeBehavior;
use app\common\behaviors\UpdateDateTimeBehavior;
use yii\db\ActiveRecord;

/**
 * Class Order
 * @package app\models
 * @property integer $id
 * @property integer $status
 * @property integer $user_id
 * @property integer $manufacture_id
 * @property string $manufacture_name
 * @property integer $model_id
 * @property string $model_name
 * @property integer $group_id
 * @property string $part_no
 * @property string $part_description
 * @property string $create_dt
 * @property string $update_dt
 */
class Order extends ActiveRecord
{
    const STATUS_UNCHECKED      = 0;
    const STATUS_ACTIVE         = 1;
    const STATUS_REJECTED       = 2;
    const STATUS_ARCHIVED       = 3;

    public function behaviors()
    {
        return [
            'create_dt' => [
                'class' => CreateDateTimeBehavior::className(),
                'resProp' => 'create_dt'
            ],
            'update_dt' => [
                'class' => UpdateDateTimeBehavior::className(),
                'resProp' => 'update_dt'
            ]
        ];
    }

    public function statusLabels()
    {
        return [
            self::STATUS_UNCHECKED  => 'Непроверенные',
            self::STATUS_ACTIVE     => 'Активные',
            self::STATUS_REJECTED   => 'Отклоненные',
            self::STATUS_ARCHIVED   => 'Архивные',
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Статус',
            'user_id' => 'Пользователь',
            'manufacture_id' => 'Производитель',
            'manufacture_name' => 'Производитель',
            'model_id' => 'Модель',
            'model_name' => 'Модель',
            'group_id' => 'Группа категорий',
            'part_no' => 'Part No',
            'part_description' => 'Part Description',
            'create_dt' => 'Дата/Время заказа',

        ];
    }

    public function getStatusLabel()
    {
        return isset($this->statusLabels()[$this->status]) ? $this->statusLabels()[$this->status] : 'Неопределенный';
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getManufacture()
    {
        return $this->hasOne(Manufacture::className(), ['id' => 'manufacture_id']);
    }

    public function getModel()
    {
        return $this->hasOne(Model::className(), ['id' => 'model_id']);
    }

    public function getGroup()
    {
        return $this->hasOne(Group::className(), ['id' => 'group_id']);
    }
}