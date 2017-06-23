<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 02.06.2017
 * Time: 23:17
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $orders
 */

use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;


$this->title = 'Заказы';

$this->params['menuItems'] = require(__DIR__.'/_menuItems.php');

echo Breadcrumbs::widget([
    'homeLink' => [
        'label' => 'Главная',
        'url' => ['/']
    ],
    'links' => [
        ['label' => 'Панель администратора', 'url' => ['/admin']],
        ['label' => $this->title],
    ]
]);
?>
<div id="alert-success" class="alert alert-success" style="display: none">
    <span>Сообщение</span>
</div>

<div id="alert-danger" class="alert alert-danger" style="display: none">
    <span>Сообщение</span>
</div>

<?php
$script = <<<JS
function updateOrderStatus(orderId, status)
{
    $.ajax({
        url: "/order/status",
        type: "get",
        data: {orderId:orderId, value:status},
        success:function() {
            $("#alert-success > span").html("Статус успешно изменен");
            $("#alert-success").show()
        },
        error: function(error) {
            $("#alert-danger > span").html(error.responseText);
            $("#alert-danger").show()
        }
    });
}
JS;

$this->registerJs($script, \yii\web\View::POS_END);

echo GridView::widget([
    'dataProvider' => $orders,
    'emptyText' => 'Заказов нет',
    'summary' => 'Показано <b>{begin, number}-{end, number}</b> из <b>{totalCount, number}</b> {totalCount, plural, one{заказ} other{заказов}}.',
    'columns' => [
        'id',
        'create_dt',
        [
            'class' => \yii\grid\DataColumn::className(),
            'attribute' => 'user_id',
            'value' => function(\app\models\Order $order) {
                return !empty($order->user) ? $order->user->firstname.' '.$order->user->lastname : 'Пользователь';
            }
        ],
        [
            'class' => \yii\grid\DataColumn::className(),
            'attribute' => 'manufacture_id',
            'value' => function(\app\models\Order $order) {
                return $order->manufacture !== null ? $order->manufacture->title : $order->manufacture_name;
            }
        ],
        [
            'class' => \yii\grid\DataColumn::className(),
            'attribute' => 'model_id',
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
        [
            'class' => \yii\grid\DataColumn::className(),
            'attribute' => 'status',
            'value' => function(\app\models\Order $order) {
                return Html::dropDownList('status', $order->status, $order->statusLabels(), [
                    'class' => 'form-control',
                    'onchange'=>'javaScript:updateOrderStatus('.$order->id.', $(this).val())'
                ]);
            },
            'format' => 'raw',
        ],
    ]
]);
