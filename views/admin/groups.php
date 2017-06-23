<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 05.06.2017
 * Time: 14:54
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $groups
 */

use yii\grid\GridView;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use app\components\widgets\ActionStatusMessage;

$this->title = 'Группы категорий';

$this->params['menuItems'] = require(__DIR__.'/_menuItems.php');

echo Breadcrumbs::widget([
    'homeLink' => [
        'label' => 'Главная',
        'url' => ['/']
    ],
    'links'=>[
        ['label' => 'Панель администратора', 'url' => ['/admin']],
        ['label' => $this->title],
    ]
]);

echo ActionStatusMessage::widget([]);

Modal::begin([
    'header' => '<h4 id="modalHeader"></h4>',
    'id' => 'modalDialog',
    'size' => 'modal-lg'
]);
echo '<div id="modalContent">Content</div>';
Modal::end();

echo Html::button('Добавить', [
    'class' => 'btn btn-lg btn-success modalButton',
    'modalHeader' => 'Новая группа категорий',
    'contentUrl' => Url::to(['/group/create'])
]);

echo GridView::widget([
    'dataProvider' => $groups,
    'emptyText' => 'Групп категорий нет',
    'summary' => 'Показано <b>{begin, number}-{end, number}</b> из <b>{totalCount, number}</b> {totalCount, plural, one{группа категорий} other{группы категорий}}.',
    'columns' => [
        [
            'class' => \yii\grid\DataColumn::className(),
            'attribute' => 'id',
            'headerOptions' => [
                'width' => '50px'
            ]
        ],
        'title',
        [
            'attribute' => 'maillist',
            'format' => 'raw',
            'value' => function(\app\models\Group $model) {
                return str_replace("\n", '<br>', Html::encode($model->maillist));
            }
        ],
        [
            'class' => \yii\grid\ActionColumn::className(),
            'template' => '{update}{delete}',
            'buttons' => [
                'update' => function($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 'javaScript:void(0)', [
                        'class' => 'modalButton',
                        'modalHeader' => 'Редактировать группу категорий',
                        'title' => Yii::t('yii', 'Редактировать'),
                        'contentUrl' => Url::to(['/group/update', 'id'=>$key])
                    ]);
                },
                'delete' => function($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::to(['/group/delete', 'id' => $key]), [
                        'title' => Yii::t('yii', 'Удалить'),
                        'data-confirm' => Yii::t('yii', 'Удалить эту группу категорий?')
                    ]);
                }
            ],
            'headerOptions' => [
                'width' => '50px'
            ]
        ]
    ]
]);