<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 25.05.2017
 * Time: 16:11
 */
namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class Catman
 * @package app\models
 * @property integer $id
 * @property integer $category_id
 * @property integer $manufacture_id
 */
class Catman extends ActiveRecord
{
    public function getCategory()
    {
        return $this->hasMany(Category::className(), ['id' => 'category_id']);
    }

    public function getManufacture()
    {
        return $this->hasMany(Manufacture::className(), ['id' => 'manufacture_id']);
    }
}