<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 20.06.2017
 * Time: 9:21
 * @var \yii\web\View $this
 * @var \app\models\Model $model
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin();
echo Html::hiddenInput('categoryId', $model->categoryId, [
    'id' => 'category-id'
]);
echo Html::hiddenInput('manufactureId', $model->manufactureId, [
    'id' => 'manufacture_id'
]);
echo $form->field($model, 'title')->textInput(['id' => 'model-title']);

echo Html::button($model->isNewRecord ? "Создать" : "Сохранить", [
    'id' => 'btnModalOk',
    'class' => 'btn btn-success',
]);
ActiveForm::end();