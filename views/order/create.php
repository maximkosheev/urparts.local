<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 30.05.2017
 * Time: 10:05
 * @var \yii\web\View $this
 * @var \app\models\OrderForm $model
 * @var array $manufactures
 * @var array $groups
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use app\components\widgets\ActionStatusMessage;

$this->title = "Новый заказ";

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

$script = <<< JS

function getRequestParam(name)
{
    var results = new RegExp('[\?&]' + name + '=([^]*)').exec(window.location.href);
    if (results==null){
       return null;
    }
    else{
       return results[1] || 0;
    }
}

function changeModels(manufacture)
{
    $("#model_id").empty();
    $("#model_id").append($("<option></option>").val("").html("Выберете модель"));
    $.ajax({
        url:"/order/models",
        type:"get",
        data:{
            categoryId:getRequestParam("categoryId"),
            manufactureId:manufacture.value
        },
        success:function(data) {
            models = $.parseJSON(data);
            $.each(models, function(index, element){
                $("#model_id").append($("<option></option>").val(index).html(element))
            });
        }
    });
}

function enterManufacture()
{
    $("#manufacture_id").val("");
    $("#model_id").val("");
    $("#select-manufacture-group").hide();
    $("#enter-manufacture-group").show();
    $("#select-model-group").hide();
    $("#enter-model-group").show();
    $("#btn-select-model").hide();
    $("#btn-enter-model").hide();
}

function selectManufacture()
{
    $("#model_id").val("");
    $("#select-manufacture-group").show();
    $("#enter-manufacture-group").hide();
    $("#select-model-group").show();
    $("#enter-model-group").hide();
    $("#btn-select-model").show();
    $("#btn-enter-model").show();
}

function enterModel()
{
    $("#model_id").val(-1);
    $("#select-model-group").hide();
    $("#enter-model-group").show();
}

function selectModel()
{
    $("#select-model-group").show();
    $("#enter-model-group").hide();
}

function aaa(event)
{
    event.preventDefault();
    console.log("aaaa");
}
JS;

$this->registerJs($script, yii\web\View::POS_END);

$form = ActiveForm::begin([
    'id' => 'order-form',
]);
?>
<div class="duality-group required">
    <div id="select-manufacture-group">
        <?php
        echo $form->field($model, 'manufacture_id', [
            'options' => [
                'class' => 'duality-orderform-field'
            ]
        ])->dropDownList($manufactures, [
            'id'=>'manufacture_id',
            'onchange'=>'changeModels(this)',
            'prompt' => 'Выберете производителя'
        ]);
        echo Html::a('Нет в списке', 'javaScript:enterManufacture();', ['id'=>'btn-enter-manufacture', 'class' => 'btn btn-primary btn-duality']);
        echo '<div class="clearfix"></div>';
        ?>
    </div>
    <div id="enter-manufacture-group" style="display: none">
        <?php
        echo $form->field($model, 'manufacture_name', ['options' => ['class' => 'duality-orderform-field']])->textInput();
        echo Html::a('Выбрать из списке', 'javaScript:selectManufacture();', ['id'=>'btn-select-manufacture', 'class' => 'btn btn-primary btn-duality']);
        echo '<div class="clearfix"></div>';
        ?>
    </div>
</div>

<div class="duality-group required">
    <div id="select-model-group">
        <?php
        echo $form->field($model, 'model_id', ['options' => ['class' => 'duality-orderform-field']])->dropDownList([], [
            'id'=>'model_id',
            'prompt' => 'Выберете модель'
        ]);
        echo Html::a('Нет в списке', 'javaScript:enterModel();', ['id'=>'btn-enter-model', 'class' => 'btn btn-primary btn-duality']);
        echo '<div class="clearfix"></div>';
        ?>
    </div>
    <div id="enter-model-group" style="display: none">
        <?php
        echo $form->field($model, 'model_name', ['options' => ['class' => 'duality-orderform-field']])->textInput();
        echo Html::a('Выбрать из списке', 'javaScript:selectModel();', ['id'=>'btn-select-model', 'class' => 'btn btn-primary btn-duality']);
        echo '<div class="clearfix"></div>';
        ?>
    </div>
</div>


<?php
echo $form->field($model, 'group_id', ['options' => ['style' => 'width: 50%']])->label('Группы категорий')->dropDownList($groups);

echo $form->field($model, 'part_no', ['options' => ['style' => 'width: 50%']])->label('Part No')->textInput();

echo $form->field($model, 'part_description', ['options' => ['style' => 'width: 50%']])->label('Part Description')->textarea();

echo Html::submitButton('Заказать', ['class' => 'btn btn-primary']);
?>

<?php ActiveForm::end(); ?>
