<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 13.06.2017
 * Time: 13:05
 * @var \yii\web\View $this
 * @var \app\models\Group $model
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin([]);

echo $form->field($model, 'title')->textInput();
echo $form->field($model, 'maillist')->textarea();

echo Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', [
    'class' => 'btn btn-success',
    'id' => 'submitModal'
]);
ActiveForm::end();