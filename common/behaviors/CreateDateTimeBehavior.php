<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 30.05.2017
 * Time: 9:46
 */

namespace app\common\behaviors;

use yii\db\ActiveRecord;

class CreateDateTimeBehavior extends SetDateTimeBehavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'setDT'
        ];
    }
}