<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 02.04.2017
 * Time: 11:27
 */

namespace app\common\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;

abstract class SetDateTimeBehavior extends Behavior
{
    public $resProp;

    public function setDT()
    {
        $dt = date('Y-m-d H:i:s', time());
        $this->owner->{$this->resProp} = $dt;
    }
}