<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 19.06.2017
 * Time: 14:57
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $models
 */

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

$this->title = 'Категории';

$this->params['menuItems'] = require(__DIR__.'/_menuItems.php');

echo Breadcrumbs::widget([
    'homeLink' => [
        'label' => 'Главная',
        'url' => ['/']
    ],
    'links'=>[
        ['label' => 'Панель администратора', 'url' => ['/admin']],
        ['label' => $this->title],
    ]
]);
?>

<div id="alert-success" class="alert alert-success" style="display: none">
    <span>Сообщение</span>
</div>

<div id="alert-danger" class="alert alert-danger" style="display: none">
    <span>Сообщение</span>
</div>

<?php
Modal::begin([
    'header' => '<h4 id="modalHeader"></h4>',
    'id' => 'modalDialog',
    'size' => 'modal-lg'
]);
echo '<div id="modalContent">Content</div>';
Modal::end();

$script = <<<JS
function getCategories() {
    $.ajax({
        url: "/category/list",
        success: function(data) {
            var items = $.parseJSON(data);
            $("#category-id").empty();
            $("#manufacture-id").empty();
            $.each(items, function(index, element) {
                $("#category-id").append(
                    $("<option></option>").val(index).html(element)
                );
            });
            onCategoryChanged($("#category-id").val());
        }
    });
}

function onCategoryChanged(categoryId) {
    $.ajax({
        url: "/manufacture/list",
        data: {
            categoryId: categoryId
        },
        success: function(data) {
            var items = $.parseJSON(data);
            $("#manufacture-id").empty();
            $.each(items, function(index, element) {
                $("#manufacture-id").append(
                    $("<option></option>").val(index).html(element)
                );
            });
            onManufactureChanged($("#category-id").val(), $("#manufacture-id").val());
        }
    });
}

function onManufactureChanged(categoryId, manufactureId) {
    $.ajax({
        url: "/model/list",
        data: {
            categoryId: categoryId,
            manufactureId: manufactureId
        },
        success: function(data) {
            $("#models-list").html(data);
        }
    });
}

function showActionStatusMessage(message, ok) {
    $("#alert-danger").hide();
    $("#alert-success").hide();
    if (ok) {
        $("#alert-success > span").html(message);
        $("#alert-success").show()
    }
    else {
        $("#alert-danger > span").html(message);
        $("#alert-danger").show()
    }
}

function onCreateClicked() {
    var categoryId = $("#category-id").val();
    var manufactureId = $("#manufacture-id").val();

    $.ajax({
        url: "/model/create",
        type: "POST",
        data: {
            "categoryId": categoryId,
            "manufactureId": manufactureId
        },
        success: function(data) {
            $("#modalHeader").html("Новая модель");
            $("#modalDialog").modal("show").find("#modalContent").html(data)
            $("#modalDialog").find("#btnModalOk").on('click', function(event){
                // отправляем ajax-запрос на создание новой модели
                $.ajax({
                    url: "/model/create",
                    type: "POST",
                    data: {
                        "categoryId": categoryId,
                        "manufactureId": manufactureId,
                        "Model[title]": $("#modalDialog").find("#model-title").val()
                    },
                    // модель успешно создана
                    success: function(data) {
                        $("#modalDialog").modal("hide");
                        // модель создана успешно - обновляем список моделей
                        $("#models-list").html(data);
                        showActionStatusMessage("Модель успешно создана", true);
                    },
                    error: function(message) {
                        responseText = message.responseText;
                        $("#modalDialog").modal("hide");
                        showActionStatusMessage(responseText, false);
                    }
                })
            });
        },
        error: function(data) {
            console.log(data);
        }
    });
}

function onUpdateClicked(sender, event) {
    event.preventDefault();
    var categoryId = $("#category-id").val();
    var manufactureId = $("#manufacture-id").val();

    $.ajax({
        url: sender.attr("href"),
        type: "POST",
        data: {
            "categoryId": categoryId,
            "manufactureId": manufactureId
        },
        success: function(data) {
            $("#modalHeader").html("Редактирование модели");
            $("#modalDialog").modal("show").find("#modalContent").html(data)
            $("#modalDialog").find("#btnModalOk").on('click', function(event){
                // отправляем ajax-запрос на создание новой модели
                $.ajax({
                    url: sender.attr("href"),
                    type: "POST",
                    data: {
                        "categoryId": categoryId,
                        "manufactureId": manufactureId,
                        "Model[title]": $("#modalDialog").find("#model-title").val()
                    },
                    // модель успешно создана
                    success: function(data) {
                        $("#modalDialog").modal("hide");
                        // модель создана успешно - обновляем список моделей
                        $("#models-list").html(data);
                        showActionStatusMessage("Модель успешно создана", true);
                    },
                    error: function(message) {
                        responseText = message.responseText;
                        $("#modalDialog").modal("hide");
                        showActionStatusMessage(responseText, false);
                    }
                })
            });
        },
        error: function(data) {
            console.log(data);
        }
    });
}

function onDeleteClicked(sender, event) {
    event.preventDefault();
    var categoryId = $("#category-id").val();
    var manufactureId = $("#manufacture-id").val();

    if (confirm(sender.attr("confirm"))) {
        $.ajax({
            url: sender.attr("href"),
            type: "POST",
            data: {
                "categoryId": categoryId,
                "manufactureId": manufactureId,
            },
            success: function(data) {
                // модель удалена успешно - обновляем список моделей
                $("#models-list").html(data);
                showActionStatusMessage("Модель успешно создана", true);
            },
            error: function(message) {
                responseText = message.responseText;
                showActionStatusMessage(responseText, false);
            }
        });
    }
}
getCategories();
JS;

$this->registerJs($script, \yii\web\View::POS_END);
?>

<div class="form-group">
    <label class="control-label" for="category-id">Категории</label>
    <?= Html::dropDownList('categoryId', null, [], [
        'id' => 'category-id',
        'class' => 'form-control',
        'onchange' => 'javaScript:onCategoryChanged($(this).val())'
    ]); ?>
</div>

<div class="form-group">
    <label class="control-label" for="manufacture-id">Производители</label>
    <?= Html::dropDownList('manufactureId', null, [], [
        'id' => 'manufacture-id',
        'class' => 'form-control',
        'onchange' => 'javaScript:onManufactureChanged($("#category-id").val(), $(this).val())'
    ]); ?>
</div>

<?= Html::button('Добавить', [
    'class' => 'btn btn-lg btn-success',
    'onclick' => 'javaScript:onCreateClicked()'
]);
?>

<div id="models-list"></div>