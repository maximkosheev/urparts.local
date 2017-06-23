<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 29.05.2017
 * Time: 14:27
 * @var \yii\web\View $this
 * @var \app\models\FeedbackForm $model
 */

use app\components\widgets\ActionStatusMessage;
use app\models\Country;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;

$this->title = 'Обратная связь';

echo Breadcrumbs::widget([
    'homeLink' => [
        'label' => 'Главная',
        'url' => ['/']
    ],
    'links'=>[
        ['label' => $this->title],
    ]
]);

echo ActionStatusMessage::widget([]);

$form = ActiveForm::begin([
    'id' => 'feedback_form'
]);

echo $form->field($model, 'subject')->textInput();
echo $form->field($model, 'user_name')->textInput();
echo $form->field($model, 'user_email')->textInput();
echo $form->field($model, 'user_phone')->textInput();
echo $form->field($model, 'user_country')->dropDownList(Country::getCoutries(), []);
echo $form->field($model, 'text')->textarea();
echo Html::submitButton('Отправить', ['class' => 'btn btn-primary']);
ActiveForm::end();