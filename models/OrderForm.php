<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 30.05.2017
 * Time: 10:06
 */

namespace app\models;

use yii\base\Model;
use app\common\validators\DualityValidator;

class OrderForm extends Model
{
    // идентификатор производителя, если выбран из списка
    public $manufacture_id;
    // название производителя, если указан вручную
    public $manufacture_name;
    // идентификатор модели, если выбран из списка
    public $model_id;
    // название модели, если указано вручную
    public $model_name;
    // идентификатор группы категорий
    public $group_id;
    // какой-то PartNo
    public $part_no;
    // какой-то PartDescription
    public $part_description;

    public function rules()
    {
        return [
            ['manufacture_id', DualityValidator::className(), 'pair' => 'manufacture_name', 'message' => 'Производитель должен быть либо выбран из списка, либо указан вручную'],
            ['model_id', DualityValidator::className(), 'pair' => 'model_name', 'message' => 'Модель должна быть либо выбрана из списка, либо указана вручную'],
            [['group_id', 'part_description'], 'required', 'message' => 'Поле не может быть пустым'],
            [['manufacture_name', 'model_name', 'part_no'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'manufacture_id' => 'Производитель',
            'manufacture_name' => 'Производитель',
            'model_id' => 'Модель',
            'model_name' => 'Модель',
            'group_id' => 'Группа категорий',
            'part_no' => 'Part No',
            'part_description' => 'Part Description'
        ];
    }

    public function order()
    {
        if ($this->validate()) {
            $order = new Order();
            $order->user_id = \Yii::$app->user->id;
            $order->status = Order::STATUS_UNCHECKED;
            $order->manufacture_id = $this->manufacture_id;
            $order->manufacture_name = $this->manufacture_name;
            if (empty($order->manufacture_name))
                $order->manufacture_name = null;
            $order->model_id = $this->model_id;
            $order->model_name = $this->model_name;
            if (empty($order->model_name))
                $order->model_name = null;
            $order->group_id = $this->group_id;
            $order->part_no = $this->part_no;
            $order->part_description = $this->part_description;
            return $order->save();
        }
        return false;
    }
}