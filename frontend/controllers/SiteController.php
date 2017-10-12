<?php
namespace frontend\controllers;

use yii\web\Controller;

class SiteController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = 'home';
        return $this->render('index');
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
    public function actionPrivacyPolicy()
    {
        return $this->render('privacy_policy');
    }
    public function actionTermsOfService()
    {
        return $this->render('terms_of_service');
    }
}
