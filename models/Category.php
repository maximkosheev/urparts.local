<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 25.05.2017
 * Time: 15:00
 */
namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class Category
 * @package app\models
 * @property integer $id - идентификатор категории
 * @property string $title - наименование категории
 */
class Category extends ActiveRecord
{
    public function rules()
    {
        return [
            ['title', 'required', 'message' => 'Поле не может быть пустым'],
            ['title', 'unique', 'message' => 'Категория {value} уже сущетсвует']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Наименование'
        ];
    }

    /**
     * Возвращает список производителей данной категории
     * @return \yii\db\ActiveQuery
     */
    public function getManufacture()
    {
        return $this->hasMany(Manufacture::className(), ['id' => 'manufacture_id'])->viaTable('tbl_catman', ['category_id' => 'id']);
    }
}
