<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 26.05.2017
 * Time: 10:05
 * @var \yii\web\View $this
 */

use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$this->title = 'Панель администратора';

$this->params['menuItems'] = require(__DIR__.'/_menuItems.php');

echo Breadcrumbs::widget([
    'homeLink' => [
        'label' => 'Главная',
        'url' => ['/']
    ],
    'links' => [
        ['label' => $this->title],
    ]
]);

?>
Добро пожаловать в панель администрирования! Здесь вы можете управлять всеми элементами сервиса.
