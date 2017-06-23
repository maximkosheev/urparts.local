<?php

/**
 * @var yii\web\View $this
 * @var array $categories
 * @var \yii\data\ActiveDataProvider $orders
 */

use yii\grid\GridView;
use yii\widgets\Breadcrumbs;

$this->title = 'Заказы';

echo Breadcrumbs::widget([
    'homeLink' => [
        'label' => 'Главная',
        'url' => ['/']
    ],
    'links'=>[
        ['label' => $this->title],
    ]
]);
?>
<h1>Заказы:</h1>
<?php
echo GridView::widget([
    'dataProvider' => $orders,
    'emptyText' => 'Заказов нет',
    'summary' => 'Показано <b>{begin, number}-{end, number}</b> из <b>{totalCount, number}</b> {totalCount, plural, one{заказ} other{заказов}}.',
    'columns' => [
        'id',
        [
            'class' => \yii\grid\DataColumn::className(),
            'attribute' => 'status',
            'value' => function(\app\models\Order $order) {
                return $order->statusLabel;
            }
        ],
        [
            'class' => \yii\grid\DataColumn::className(),
            'attribute' => 'user_id',
            'value' => function(\app\models\Order $order) {
                return !empty($order->user) ? $order->user->firstname.' '.$order->user->lastname : 'Пользователь';
            }
        ],
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

