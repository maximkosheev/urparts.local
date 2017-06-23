<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 07.06.2017
 * Time: 9:46
 */

namespace app\controllers;

use app\models\Category;
use app\models\Catman;
use app\models\Manufacture;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ManufactureController extends Controller
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

    public function actionCreate()
    {
        $model = new Manufacture();

        if ($model->load(\Yii::$app->request->post())) {
            try {
                // валидация данных
                if (!$model->validate())
                    throw new \Exception($model->hasErrors('title') ? $model->getFirstError('title'): 'Что-то не так');
                // создание нового производителя
                if (!$model->save())
                    throw new \Exception('Производитель не был сохранет из-за ошибки на сервере');
                \Yii::$app->session->setFlash('actionSuccess', 'Производитель успешно сохранен', false);
            }
            catch(\Exception $e) {
                \Yii::$app->session->setFlash('actionFailed', $e->getMessage(), false);
            }
            return $this->redirect(['/admin/manufacture']);
        }

        return $this->renderAjax('create', [
            'model' => $model,
            'allCategories' => ArrayHelper::map(Category::find()->all(), 'id', 'title')
        ]);
    }

    public function actionUpdate($id)
    {
        $model = Manufacture::findOne(['id' => $id]);

        if ($model === null)
            throw new NotFoundHttpException('Производитель не найден');

        $model->categories = ArrayHelper::getColumn(Catman::find()
            ->select('category_id')
            ->where(['manufacture_id' => $id])
            ->all(),
            'category_id');

        if ($model->load(\Yii::$app->request->post())) {
            try {
                // валидация данных
                if (!$model->validate())
                    throw new \Exception($model->hasErrors('title') ? $model->getFirstError('title'): 'Что-то не так');
                if (!$model->save())
                    throw new \Exception('Производитель не был сохранет из-за ошибки на сервере');
                \Yii::$app->session->setFlash('actionSuccess', 'Производитель успешно сохранен', false);
            }
            catch(\Exception $e) {
                \Yii::$app->session->setFlash('actionFailed', $e->getMessage(), false);
            }
            return $this->redirect(['/admin/manufacture']);
        }

        return $this->renderAjax('update', [
            'model' => $model,
            'allCategories' => ArrayHelper::map(Category::find()->all(), 'id', 'title')
        ]);
    }

    public function actionDelete($id)
    {
        $model = Manufacture::findOne(['id' => $id]);

        if ($model === null)
            throw new NotFoundHttpException('Производитель не найден');

        try {
            if ($model->delete()) {
                \Yii::$app->session->setFlash('actionSuccess', 'Производитель успешно удален');
            } else {
                throw new \Exception('Производитель не удален');
            }
        }
        catch (\Exception $e) {
            \Yii::$app->session->setFlash('actionFailed', 'Производитель не удален из-за ошибки на сервере', false);
        }

        return $this->redirect(['/admin/manufacture']);
    }

    public function actionList($categoryId = null) {
        if (!\Yii::$app->request->isAjax) {
            throw new \HttpRequestException('Неверный формат запроса');
        }

        if ($categoryId !== null) {
            $query = Manufacture::find()
                ->innerJoin('tbl_catman', 'tbl_manufacture.id = tbl_catman.manufacture_id')
                ->where(['tbl_catman.category_id' => $categoryId]);
        }
        else {
            $query = Manufacture::find();
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return json_encode(ArrayHelper::map($query->all(), 'id', 'title'));
    }
}