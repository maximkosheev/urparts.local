<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 19.06.2017
 * Time: 14:32
 */

namespace app\controllers;

use app\models\Catman;
use app\models\Model;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class ModelController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            if (\Yii::$app->user->identity->username === 'admin')
                                return true;
                            return false;
                        }
                    ]
                ]
            ]
        ];
    }

    public function actionCreate() {
        $model = new Model();
        $model->categoryId = \Yii::$app->request->post('categoryId', null);
        $model->manufactureId = \Yii::$app->request->post('manufactureId', null);

        if ($model->load(\Yii::$app->request->post())) {
            if (!$model->validate())
                throw new ServerErrorHttpException($model->hasErrors('title') ? $model->getFirstError('title'): 'Что-то не так');
            $catman = Catman::findOne(['category_id' => $model->categoryId, 'manufacture_id' => $model->manufactureId]);
            $model->catman_id = $catman->id;
            try {
                if (!$model->save())
                    throw new Exception();
                // все ОК - возвращаем список моделей
                return $this->actionList($model->categoryId, $model->manufactureId);
            }
            catch(\Exception $e) {
                throw new ServerErrorHttpException('Модель не была создана. Возможно такая модель уже существует.');
            }
        }
        return $this->renderAjax('create', [
            'model' => $model
        ]);
    }

    public function actionUpdate($id) {
        $model = Model::findOne(['id' => $id]);
        $model->categoryId = \Yii::$app->request->post('categoryId', null);
        $model->manufactureId = \Yii::$app->request->post('manufactureId', null);

        if ($model === null)
            throw new NotFoundHttpException('Модель не найдена');

        if ($model->load(\Yii::$app->request->post())) {
            if (!$model->validate())
                throw new ServerErrorHttpException($model->hasErrors('title') ? $model->getFirstError('title'): 'Что-то не так');
            try {
                if (!$model->save())
                    throw new Exception();
                // все ОК - возвращаем список моделей
                return $this->actionList($model->categoryId, $model->manufactureId);
            }
            catch(\Exception $e) {
                throw new ServerErrorHttpException('Модель не была сохранена. Возможно такая модель уже существует.');
            }
        }

        return $this->renderAjax('update', [
            'model' => $model
        ]);
    }

    public function actionDelete($id) {
        $model = Model::findOne(['id' => $id]);
        $model->categoryId = \Yii::$app->request->post('categoryId', null);
        $model->manufactureId = \Yii::$app->request->post('manufactureId', null);

        if ($model === null)
            throw new NotFoundHttpException('Модель не найдена');

        try {
            $model->delete();
            // все ОК - возвращаем список моделей
            return $this->actionList($model->categoryId, $model->manufactureId);
        }
        catch (\Exception $e) {
            throw new ServerErrorHttpException('Модель не была создана. Возможно такая модель уже существует.');
        }
    }

    public function actionList($categoryId, $manufactureId) {
        $models = new ActiveDataProvider([
            'query' => Model::find()
                ->innerJoin('tbl_catman', 'tbl_catman.id = tbl_model.catman_id')
                ->where(['tbl_catman.category_id' => $categoryId, 'tbl_catman.manufacture_id' => $manufactureId]),
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        return $this->renderAjax('list', [
            'models' => $models
        ]);
    }
}