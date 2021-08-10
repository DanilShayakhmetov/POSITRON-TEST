<?php

namespace app\controllers;

use app\models\Contact;
use app\models\SignupForm;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\httpclient\debug\SearchModel;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
//        if (!Yii::$app->user->isGuest) {
            $model = new ContactForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $user_id = Yii::$app->user->getId();
                $contact = new Contact();
                $contact->name = ucfirst($model->name);
                $contact->sur_name = ucfirst($model->surName);
                $contact->phone_number = $model->phone;
                $contact->email = $model->email;
                $contact->text_message = $model->body;
                $contact->user_id = $user_id;
                $contact->save();
            } else {
                return $this->render('contact', [
                    'model' => $model,
                ]);
            }
            Yii::$app->session->setFlash('success', 'Thank you for you message');
            $contact->save();
            return $this->render('about');
//        }
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     * @throws \yii\base\Exception
     */
    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new SignupForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $user = new User();
            $user->username = $model->username;
            $user->email = $model->email;
            $user->password_hash = \Yii::$app->security->generatePasswordHash($model->password);
            $user->auth_key = \Yii::$app->security->generateRandomString();
            if($user->save()){
                Yii::$app->session->setFlash('success', 'Congratulations!
                 your account has been successfully created.');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('danger', 'Try to change email or name');
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}

