<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 06.06.2017
 * Time: 10:32
 * @var \yii\web\View $this
 * @var \app\models\Category $model
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin();

echo $form->field($model, 'title')->textInput();

echo Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', [
    'class' => 'btn btn-success',
    'id' => 'submitModal'
]);
ActiveForm::end();