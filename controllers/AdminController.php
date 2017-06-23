<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 26.05.2017
 * Time: 10:04
 */

namespace app\controllers;

use app\models\Category;
use app\models\Group;
use app\models\Manufacture;
use app\models\Model;
use app\models\Order;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class AdminController extends Controller
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

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionOrders()
    {
        $orders = new ActiveDataProvider([
            'query' => Order::find()
                ->with(['user', 'manufacture', 'model', 'group'])
                ->orderBy('create_dt ASC'),
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        return $this->render('orders', [
            'orders' => $orders
        ]);
    }

    public function actionCategory()
    {
        $categories = new ActiveDataProvider([
            'query' => Category::find()
                ->orderBy('title ASC'),
            'pagination' => [
                'pageSize' => '20'
            ]
        ]);

        return $this->render('category', [
            'categories' => $categories
        ]);
    }

    public function actionManufacture()
    {
        $manufactures = new ActiveDataProvider([
            'query' => Manufacture::find()
                ->orderBy('title ASC'),
            'pagination' => [
                'pageSize' => 20
            ]
        ]);
        return $this->render('manufacture', [
            'categories' => ArrayHelper::map(Category::find()->all(), 'id', 'title'),
            'manufactures' => $manufactures
        ]);
    }

    public function actionModels()
    {
        return $this->render('models');
    }

    public function actionGroups()
    {
        $groups = new ActiveDataProvider([
            'query' => Group::find(),
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        return $this->render('groups', [
            'groups' => $groups
        ]);
    }
}