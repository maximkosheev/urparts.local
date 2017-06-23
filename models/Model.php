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
 * Class Model
 * @package app\models
 * @property integer $id - идентификатор модели
 * @property integer $catman_id - ссылка на категорию и производителя
 * @property string $title - наименование модели
 */
class Model extends ActiveRecord
{
    public $categoryId;
    public $manufactureId;

    public function rules() {
        return [
            ['title', 'required', 'message' => 'Поле не может быть пустым'],
            ['title', 'validateUnique', 'params' => ['message' => 'Такая модель уже существует']],
            [['categoryId', 'manufactureId'], 'safe']
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'title' => 'Наименование'
        ];
    }

    public function validateUnique($attribute, $params) {
        if ($this->hasErrors($attribute))
            return;

        // Проверка существования пары "категория-производитель"
        $catman = Catman::findOne(['category_id' => $this->categoryId, 'manufacture_id' => $this->manufactureId]);
        if ($catman === null) {
            $this->addError($attribute, 'Категория или производитель для новой модели не найдены');
            return;
        }
        // проверка уникальности наименования модели в рамках "категория-производитель"
        $models = Model::find()
            ->where(['catman_id' => $catman->id])
            ->andWhere(['like', $attribute, $this->{$attribute}])
            ->all();
        if (count($models) > 0)
            $this->addError($attribute, $params['message']);
    }

    public function getCategory() {
        return $this->hasOne(Category::className(), ['id' => 'category_id'])->viaTable('tbl_catman', ['id' => 'catman_id']);
    }

    /**
     * Возвращает производителя данной модели
     * @return \yii\db\ActiveQuery
     */
    public function getManufacture()
    {
        return $this->hasOne(Manufacture::className(), ['id' => 'manufacture_id'])->viaTable('tbl_catman', ['id' => 'catman_id']);
    }
}
