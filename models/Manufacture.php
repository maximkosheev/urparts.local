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
 * Class Manufacture
 * @package app\models
 * @property integer $id - идентификатор производителя
 * @property string $title - наименование производителя
 */
class Manufacture extends ActiveRecord
{
    public $categories;

    public function rules()
    {
        return [
            ['title', 'required', 'message' => 'Поле не может быть пустым'],
            ['title', 'unique'],
            ['categories', 'safe']
        ];
    }

    /**
     * Возвращает список моделей данного производителя
     * @return \yii\db\ActiveQuery
     */
    public function getModels()
    {
        return $this->hasMany(Model::className(), ['manufacture_id' => 'id']);
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Наименование',
            'categories' => 'Категории'
        ];
    }

    /**
     * устанавливает связи между производителем и категориями
     */
    protected function associateWithCategories()
    {
        foreach ($this->categories as $categoryId) {
            try {
                $catman = new Catman();
                $catman->category_id = $categoryId;
                $catman->manufacture_id = $this->id;
                $catman->save();
            }
            catch (\Exception $e) {
                // do nothing
            }
        }
    }

    public function afterSave($insert, $changedAttributes) {
        if ($insert) {
            // связь между производителем и категориями
            $this->associateWithCategories();
        }
        else {
            try {
                // удаляем все связи этого производителя с категориями, с которыми не связаны модели
                // связи производителя с категориями, с которыми связаны модели, мы не трогаем
                $db = \Yii::$app->db;
                $sql = $db->createCommand("DELETE FROM tbl_catman WHERE id NOT IN (SELECT catman_id FROM tbl_model) AND manufacture_id = {$this->id}");
                $sql->execute();
                // пересоздаем связи производителя с категориями
                $this->associateWithCategories();
            }
            catch (\Exception $e) {
                // do nothing
            }
        }
        parent::afterSave($insert, $changedAttributes);
    }
}
