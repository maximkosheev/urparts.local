<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 24.05.2017
 * Time: 11:06
 */

namespace app\controllers;

use app\models\Order;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ]
        ];
    }

    /**
     * Личный кабинет зарегистрированного пользователя
     * @return string
     */
    public function actionMy()
    {
        return $this->render('my');
    }

    /**
     * Возвращает список активных заказов
     */
    public function actionActive()
    {
        $orders = new ActiveDataProvider([
            'query' => Order::find()
                ->where(['status' => Order::STATUS_ACTIVE])
                ->with('model'),
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        return $this->renderAjax('active', [
            'orders' => $orders
        ]);
    }

    /**
     * Возвращает список отклоненных заказов
     */
    public function actionRejected()
    {
        $orders = new ActiveDataProvider([
            'query' => Order::find()
                ->where(['status' => Order::STATUS_REJECTED])
                ->with('model'),
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        return $this->renderAjax('active', [
            'orders' => $orders
        ]);
    }

    /**
     * Возвращает список архивных заказов
     */
    public function actionArchived()
    {
        $orders = new ActiveDataProvider([
            'query' => Order::find()
                ->where(['status' => Order::STATUS_ARCHIVED])
                ->with('model'),
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        return $this->renderAjax('active', [
            'orders' => $orders
        ]);
    }
}