<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 30.05.2017
 * Time: 10:04
 */

namespace app\controllers;

use app\models\Group;
use app\models\Manufacture;
use app\models\Model;
use app\models\Order;
use app\models\OrderForm;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

class OrderController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true
                    ],
                    [
                        'actions' => ['create', 'result'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                    [
                        'actions' => ['models'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['status'],
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

    public function actionIndex() {
        $orders = new ActiveDataProvider([
            'query' => Order::find()
                ->with(['user', 'manufacture', 'model', 'group']),
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        return $this->render('index', [
            'orders' => $orders
        ]);
    }

    public function actionCreate($categoryId)
    {
        $model = new OrderForm();

        // формируем список производителей данной категории
        $manufactures = ArrayHelper::map(
            Manufacture::find()
                ->innerJoin('tbl_catman', 'tbl_catman.manufacture_id = tbl_manufacture.id')
                ->where(['tbl_catman.category_id' => $categoryId])
                ->all(),
            'id',
            'title'
        );

        $groups = ArrayHelper::map(Group::find()->all(), 'id', 'title');

        if ($model->load(\Yii::$app->request->post()) && $model->order()) {
            \Yii::$app->session->setFlash('actionSuccess', 'Ваш заказ успешно сформирован', false);
            return $this->redirect('/order/result');
        }

        return $this->render('create', [
            'model' => $model,
            'manufactures' => $manufactures,
            'groups' => $groups
        ]);
    }

    public function actionModels($categoryId, $manufactureId)
    {
        $models = ArrayHelper::map(
            Model::find()
                ->innerJoin('tbl_catman', 'tbl_catman.id = tbl_model.catman_id')
                ->where([
                    'tbl_catman.category_id' => $categoryId,
                    'tbl_catman.manufacture_id' => $manufactureId
                ])
                ->all(),
            'id',
            'title'
        );
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return json_encode($models);
    }

    public function actionResult()
    {
        return $this->render('result');
    }

    public function actionStatus($orderId, $value)
    {
        $order = Order::find()
            ->with('group')
            ->where(['id' => $orderId])
            ->one();

        if ($order === null)
            throw new NotFoundHttpException('Этот заказ не найден');

        $needToSend = false;

        $order->status = $value;
        if ($order->isAttributeChanged('status', false)) {
            if ($order->status == Order::STATUS_ACTIVE) {
                $needToSend = true;
            }
        }

        if (!$order->save(['status']))
            throw new ServerErrorHttpException('Статус не обновлен из-за ошибки на сервере');

        if ($needToSend)
            $order->group->sendEmails();

        return 'OK';
    }
}