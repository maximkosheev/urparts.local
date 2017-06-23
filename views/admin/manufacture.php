<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 07.06.2017
 * Time: 14:33
 * @var \yii\web\View $this
 * @var array $categories
 * @var array $manufactures
 */

use app\components\widgets\ActionStatusMessage;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$this->title = 'Производители';

$this->params['menuItems'] = require(__DIR__.'/_menuItems.php');

$script = <<<JS
function onCategoryChanged(sender)
{
    $("form").submit();
}
JS;

$this->registerJs($script, \yii\web\View::POS_END);

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
        'id' => 'btn-add',
        'class' => 'btn btn-lg btn-success modalButton',
        'modalHeader' => 'Новый производитель',
        'contentUrl' => Url::to(['/manufacture/create']),
        'style' => 'margin: 10px 0;'
    ]
);
echo GridView::widget([
    'dataProvider' => $manufactures,
    'emptyText' => 'Производителей нет',
    'summary' => 'Показано <b>{begin, number}-{end, number}</b> из <b>{totalCount, number}</b> {totalCount, plural, one{производитель} other{производителей}}.',
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
                'update' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 'javaScript:void(0)', [
                        'class' => 'modalButton',
                        'modalHeader' => 'Редактировать категорию',
                        'title' => Yii::t('yii', 'Редактировать'),
                        'contentUrl' => Url::to(['/manufacture/update', 'id' => $key])
                    ]);
                },
                'delete' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::to(['/manufacture/delete', 'id' => $key]), [
                        'title' => Yii::t('yii', 'Удалить'),
                        'data-confirm' => Yii::t('yii', 'Удалить этого производителя? Внимание! Будут удалены все модели, связанные с ним!')
                    ]);
                }
            ],
            'headerOptions' => [
                'width' => '50px'
            ]
        ]
    ]
]);

