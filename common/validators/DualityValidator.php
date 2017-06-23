<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 01.06.2017
 * Time: 9:08
 */
namespace app\common\validators;

use yii\validators\Validator;

class DualityValidator extends Validator
{
    public $skipOnEmpty = false;

    public $pair = null;

    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = "{attribute} должен быть выбран или задан вручную";
        }
    }

    public function validateAttribute($model, $attribute)
    {
        if (empty($model->{$attribute}) && empty($model->{$this->pair})) {
            $this->addError($model, $attribute, $this->message);
        }
    }
}