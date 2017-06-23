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
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin();
echo $form->field($model, 'title')->textInput();
echo $form->field($model, 'categories')->checkboxList($allCategories, []);
echo Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', [
    'class' => 'btn btn-success',
    'id' => 'submitModal'
]);
ActiveForm::end();