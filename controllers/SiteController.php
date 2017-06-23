<?php

namespace app\controllers;

use app\models\Category;
use app\models\Feedback;
use app\models\LoginForm;
use app\models\Order;
use app\models\RegisterForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $categories = Category::find()->all();

        $orders = new ActiveDataProvider([
            'query' => Order::find()
                ->with(['user', 'manufacture', 'model', 'group'])
                ->limit(10),
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        return $this->render('index', [
            'categories' => $categories,
            'orders' => $orders
        ]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionRegister()
    {
        $model = new RegisterForm();

        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            Yii::$app->session->setFlash('actionSuccess', 'Поздравляем, Вы успешно зарегистрировались', false);
            return $this->redirect('login');
        }

        return $this->render('register', [
            'model' => $model
        ]);
    }

    public function actionFeedback()
    {
        $model = new Feedback();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('actionSuccess', 'Ваш сообщение успешно отправлено администратору', false);
        }

        return $this->render('feedback', [
            'model' => $model
        ]);
    }

}
