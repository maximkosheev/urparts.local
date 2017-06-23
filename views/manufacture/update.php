<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 06.06.2017
 * Time: 10:32
 * @var \yii\web\View $this
 * @var \app\models\Manufacture $model
 * @var array $allCategories
 */

echo $this->renderAjax('_form', [
    'model' => $model,
    'allCategories' => $allCategories
]);