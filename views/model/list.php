<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 19.06.2017
 * Time: 16:30
 * @var \yii\data\ActiveDataProvider $models
 */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

echo GridView::widget([
    'dataProvider' => $models,
    'emptyText' => 'Моделей нет',
    'summary' => 'Показано <b>{begin, number}-{end, number}</b> из <b>{totalCount, number}</b> {totalCount, plural, one{модель} other{моделей}}.',
    'columns' => [
        'id',
        'title',
        [
            'class' => \yii\grid\ActionColumn::className(),
            'template' => '{update}{delete}',
            'buttons' => [
                'update' => function($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::to(['/model/update', 'id' => $key]), [
                        'title' => Yii::t('yii', 'Редактировать'),
                        'onclick' => 'javaScript:onUpdateClicked($(this), event)'
                    ]);
                },
                'delete' => function($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::to(['/model/delete', 'id' => $key]), [
                        'title' => Yii::t('yii', 'Удалить'),
                        'confirm' => 'Удалить эту модель?',
                        'onclick' => 'javaScript:onDeleteClicked($(this), event)'
                    ]);
                }
            ]
        ]
    ],
]);
