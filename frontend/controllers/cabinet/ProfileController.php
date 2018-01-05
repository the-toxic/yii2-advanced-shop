<?php

namespace frontend\controllers\cabinet;

use shop\forms\User\ProfileEditForm;
use shop\services\cabinet\ProfileService;
use Yii;
use shop\entities\User\User;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ProfileController extends Controller
{
    private $service;

    public function __construct($id, $module, ProfileService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    public function actionEdit()
    {
        $user = $this->findModel(Yii::$app->user->id);
        $form = new ProfileEditForm($user);

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($user->id, $form);
                Yii::$app->session->setFlash('success','Профиль успешно обновлен');
                return $this->redirect(['/cabinet/default/index', 'id' => $user->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('edit', [
            'model' => $form,
            'user' => $user,
        ]);
    }

    public function actionRequestConfirmPhone()
    {
        if(!Yii::$app->request->isAjax)
            throw new BadRequestHttpException('Bad request!');

        $post = Yii::$app->request->post();

        try {
            $this->service->requestConfirmPhone($post);
            $result = ['status' => 'ok'];
        } catch (\DomainException $e) {
            $result = ['status' => 'error','message' => $e->getMessage()];
        }
        return $this->asJson($result);
    }

    public function actionConfirmPhone()
    {
        if(!Yii::$app->request->isAjax)
            throw new BadRequestHttpException('Bad request!');

        $user = $this->findModel(Yii::$app->user->id);
        $post = Yii::$app->request->post();

        try {
            $this->service->confirmPhone($user->id, $post['code']);
            $result = ['status' => 'ok'];
        } catch (\DomainException $e) {
            $result = ['status' => 'error','message' => $e->getMessage()];
        }
        return $this->asJson($result);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}