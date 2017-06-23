<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 13.06.2017
 * Time: 13:01
 */

namespace app\controllers;

use app\models\Group;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class GroupController extends Controller
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
        $model = new Group();

        if ($model->load(\Yii::$app->request->post())) {
            try {
                if (!$model->validate())
                    throw new \Exception($model->hasErrors('title') ? $model->getFirstError('title') : 'Что-то не так');
                $model->save();
                \Yii::$app->session->setFlash('actionSuccess', 'Группа категорий успешно сохранена', false);
            }
            catch(\Exception $e) {
                \Yii::$app->session->setFlash('actionFailed', $e->getMessage(), false);
            }
            $this->redirect(['/admin/groups']);
        }

        return $this->renderAjax('create', [
            'model' => $model
        ]);
    }

    public function actionUpdate($id) {
        $model = Group::findOne(['id' => $id]);

        if ($model === null)
            throw new NotFoundHttpException('Группа категорий не найдена');

        if ($model->load(\Yii::$app->request->post())) {
            try {
                if (!$model->validate())
                    throw new \Exception($model->hasErrors('title') ? $model->getFirstError('title') : 'Что-то не так');
                $model->save();
                \Yii::$app->session->setFlash('actionSuccess', 'Группа категорий успешно сохранена', false);
            }
            catch(\Exception $e) {
                \Yii::$app->session->setFlash('actionFailed', $e->getMessage(), false);
            }
            $this->redirect(['/admin/groups']);
        }

        return $this->renderAjax('update', [
            'model' => $model
        ]);
    }

    public function actionDelete($id) {
        $model = Group::findOne(['id' => $id]);

        if ($model === null)
            throw new NotFoundHttpException('Группа категорий не найдена');

        try {
            if ($model->delete()) {
                \Yii::$app->session->setFlash('actionSuccess', 'Группа категорий успешно удалена');
            } else {
                throw new \Exception('Группа категорий не удалена');
            }
        }
        catch (\Exception $e) {
            \Yii::$app->session->setFlash('actionFailed', "Группа категорий не удалена из-за ошибки на сервере.<br> Возможно, имеются заказы, связанные с этой группой категорий", false);
        }

        return $this->redirect(['/admin/groups']);
    }
}