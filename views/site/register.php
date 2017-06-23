<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 24.05.2017
 * Time: 10:49
 * @var \app\models\RegisterForm $model
 */

use app\components\widgets\ActionStatusMessage;
use app\models\Country;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;


echo Breadcrumbs::widget([
    'homeLink' => [
        'label' => 'Главная',
        'url' => ['/']
    ],
    'links'=>[
        ['label' => 'Регистрация пользователя'],
    ]
]);

echo ActionStatusMessage::widget();

$form = ActiveForm::begin([
    'id' => 'form-register'
]);

echo $form->field($model, 'username')->textInput();
echo $form->field($model, 'password')->passwordInput();
echo $form->field($model, 'password_repeat')->passwordInput();
echo $form->field($model, 'firstname')->textInput();
echo $form->field($model, 'lastname')->textInput();
echo $form->field($model, 'email')->textInput();
echo $form->field($model, 'city')->textInput();
echo $form->field($model, 'country')->dropDownList(Country::getCoutries(), []);
echo $form->field($model, 'phone')->textInput();
echo Html::submitButton('Регистрация', ['class' => 'btn btn-primary', 'name' => 'register-button']);
?>
<?php ActiveForm::end(); ?>
