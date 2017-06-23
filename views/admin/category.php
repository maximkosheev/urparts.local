<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 05.06.2017
 * Time: 14:54
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $categories
 */

use yii\grid\GridView;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use app\components\widgets\ActionStatusMessage;

$this->title = 'Категории';

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
    'modalHeader' => 'Новая категория',
    'contentUrl' => Url::to(['/category/create'])
]);

echo GridView::widget([
    'dataProvider' => $categories,
    'emptyText' => 'Категорий нет',
    'summary' => 'Показано <b>{begin, number}-{end, number}</b> из <b>{totalCount, number}</b> {totalCount, plural, one{категория} other{категорий}}.',
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
            'class' => \yii\grid\ActionColumn::className(),
            'template' => '{update}{delete}',
            'buttons' => [
                'update' => function($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 'javaScript:void(0)', [
                        'class' => 'modalButton',
                        'modalHeader' => 'Редактировать категорию',
                        'title' => Yii::t('yii', 'Редактировать'),
                        'contentUrl' => Url::to(['/category/update', 'id'=>$key])
                    ]);
                },
                'delete' => function($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::to(['/category/delete', 'id' => $key]), [
                        'title' => Yii::t('yii', 'Удалить'),
                        'data-confirm' => Yii::t('yii', 'Удалить эту категорию?')
                    ]);
                }
            ],
            'headerOptions' => [
                'width' => '50px'
            ]
        ]
    ]
]);