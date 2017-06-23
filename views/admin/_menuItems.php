<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 07.06.2017
 * Time: 12:35
 */

use yii\helpers\Url;

return [
    ['label' => 'Заказы', 'url' => Url::to(['/admin/orders'])],
    ['label' => 'Категории', 'url' => Url::to(['/admin/category'])],
    ['label' => 'Производители', 'url' => Url::to(['/admin/manufacture'])],
    ['label' => 'Модели', 'url' => Url::to(['/admin/models'])],
    ['label' => 'Группы категорий', 'url' => Url::to(['/admin/groups'])]
];