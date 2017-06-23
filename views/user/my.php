<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 24.05.2017
 * Time: 14:41
 * @var \yii\web\View $this
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\jui\Tabs;

$this->title = 'Личный кабинет';

echo Tabs::widget([
    'items' => [
        [
            'label' => 'Активные',
            'url' => Url::to(['/user/active'])
        ],
        [
            'label' => 'Отклоненные',
            'url' => Url::to(['/user/rejected'])
        ],
        [
            'label' => 'Архивные',
            'url' => Url::to(['/user/archived'])
        ],
    ],
    'options' => ['tag' => 'div'],
    'itemOptions' => ['tag' => 'div'],
    'clientOptions' => ['collapsible' => false],
]);

Pjax::begin(['id' => 'orders']);
Pjax::end();
