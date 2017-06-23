<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 06.06.2017
 * Time: 10:29
 */

namespace app\controllers;

use app\models\Category;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class CategoryController extends Controller
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
                        'matchCallback' => function() {
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
        $model = new Category();

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->save())
                \Yii::$app->session->setFlash('actionSuccess', 'Категория успешно создана', false);
            else {
                if ($model->hasErrors('title'))
                    \Yii::$app->session->setFlash('actionFailed', $model->getFirstError('title'), false);
                else
                    \Yii::$app->session->setFlash('actionFailed', 'Категория не создана из-за ошибки на сервере', false);
            }
            return $this->redirect(['/admin/category']);
        }
        return $this->renderAjax('create', [
            'model' => $model
        ]);
    }

    public function actionUpdate($id)
    {
        $model = Category::findOne(['id' => $id]);

        if ($model === null)
            throw new NotFoundHttpException('Категория не найдена');

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->save())
                \Yii::$app->session->setFlash('actionSuccess', 'Категория успешно сохранена', false);
            else {
                if ($model->hasErrors('title'))
                    \Yii::$app->session->setFlash('actionFailed', $model->getFirstError('title'), false);
                else
                    \Yii::$app->session->setFlash('actionFailed', 'Категория не сохранена из-за ошибки на сервере', false);
            }
            return $this->redirect(['/admin/category']);
        }

        return $this->renderAjax('update', [
            'model' => $model
        ]);
    }

    public function actionDelete($id)
    {
        $category = Category::findOne(['id' => $id]);

        if ($category === null)
            throw new NotFoundHttpException('Категория не найдена');

        try {
            if ($category->delete()) {
                \Yii::$app->session->setFlash('actionSuccess', 'Категория успешно удалена');
            } else {
                throw new \Exception('Категория не удалена');
            }
        }
        catch (\Exception $e) {
            \Yii::$app->session->setFlash('actionFailed', 'Категория не удалена из-за ошибки на сервере. Возможно, для удаляемой категории есть производители', false);
        }

        return $this->redirect(['/admin/category']);
    }

    public function actionList($manufactureId = null) {
        if (!\Yii::$app->request->isAjax) {
            throw new \HttpRequestException('Неверный формат запроса');
        }

        if ($manufactureId !== null) {
            $query = Category::find()
                ->innerJoin('tbl_catman', 'tbl_category.id = tbl_catman.category_id')
                ->where(['tbl_catman.manufacture_id' => $manufactureId]);
        }
        else {
            $query = Category::find();
        }
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return json_encode(ArrayHelper::map($query->all(), 'id', 'title'));
    }
}