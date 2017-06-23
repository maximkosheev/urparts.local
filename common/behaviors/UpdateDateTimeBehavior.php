<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 30.05.2017
 * Time: 9:48
 */

namespace app\common\behaviors;

use yii\db\ActiveRecord;

class UpdateDateTimeBehavior extends SetDateTimeBehavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'setDT',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'setDT'
        ];
    }
}