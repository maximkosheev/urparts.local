<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 26.05.2017
 * Time: 11:43
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $orders
 */

use yii\grid\GridView;
use yii\widgets\Pjax;

Pjax::begin([
    'id' => 'data',
    'enablePushState' => false
]);
echo GridView::widget([
    'dataProvider' => $orders,
    'emptyText' => 'Заявок нет',
    'summary' => 'Показано <b>{begin, number}-{end, number}</b> из <b>{totalCount, number}</b> {totalCount, plural, one{заявка} other{заявок}}.',
    'columns' => [
        'id',
        [
            'class' => \yii\grid\DataColumn::className(),
            'label' => 'Производитель',
            'value' => function(\app\models\Order $order) {
                return $order->manufacture !== null ? $order->manufacture->title : $order->manufacture_name;
            }
        ],
        [
            'class' => \yii\grid\DataColumn::className(),
            'label' => 'Модель',
            'value' => function(\app\models\Order $order) {
                return $order->model !== null ? $order->model->title : $order->model_name;
            }
        ],
        [
            'class' => \yii\grid\DataColumn::className(),
            'attribute' => 'group_id',
            'value' => function(\app\models\Order $order) {
                return !empty($order->group) ? $order->group->title : 'Группа категорий';
            }
        ],
        'part_no',
        'part_description',
        'create_dt'
    ]
]);
Pjax::end();