<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 20.06.2017
 * Time: 9:21
 * @var \yii\web\View $this
 * @var \app\models\Model $model
 */

echo $this->renderAjax('_form', [
    'model'=>$model
]);